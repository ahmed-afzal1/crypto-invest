<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('razorpay')->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->displayCurrency = 'INR';
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function store(Request $request)
    {
        if($request->currency_code != "INR")
        {
            return redirect()->back()->with('unsuccess','Please Select INR Currency For Rezorpay.');
        }
        
        $settings = Generalsetting::findOrFail(1);
        $order = new Order();
        $input = $request->all();
        $item_name = $settings->title." Order";
        $item_number = Str::random(4).time();
        $item_amount = $request->invest;

        $order['item_name'] = $settings->title." Order";
        $order['item_number'] = Str::random(4).time();
        $order['item_amount'] = round($item_amount,2);
        $cancel_url = route('payment.cancle');
        $notify_url = route('user.razorpay.notify');


        $orderData = [
            'receipt'         => $order['item_number'],
            'amount'          => $order['item_amount'] * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        
        $razorpayOrder = $this->api->order->create($orderData);

        $input['user_id'] = auth()->user()->id;
        
        Session::put('input_data',$input);
        Session::put('order_data',$order);
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $orderData['amount'];
                    
        if ($this->displayCurrency !== 'INR')
        {
            $url = "https://api.fixer.io/latest?symbols=$this->displayCurrency&base=INR";
            $exchange = json_decode(file_get_contents($url), true);
        
            $displayAmount = $exchange['rates'][$this->displayCurrency] * $amount / 100;
        }
        
        $checkout = 'automatic';
        
        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
        {
            $checkout = $_GET['checkout'];
        }
        
        $data = [
            "key"               => $this->keyId,
            "amount"            => $amount,
            "name"              => $order['item_name'],
            "description"       => $order['item_name'],
            "prefill"           => [
                "name"              => $request->customer_name,
                "email"             => $request->customer_email,
                "contact"           => $request->customer_phone,
            ],
            "notes"             => [
                "address"           => $request->customer_address,
                "merchant_order_id" => $order['item_number'],
            ],
            "theme"             => [
                "color"             => "{{$settings->colors}}"
            ],
            "order_id"          => $razorpayOrder['id'],
        ];
        
        if ($this->displayCurrency !== 'INR')
        {
            $data['display_currency']  = $this->displayCurrency;
            $data['display_amount']    = $displayAmount;
        }
        
        $json = json_encode($data);
        $displayCurrency = $this->displayCurrency;
        
        return view( 'front.razorpay-checkout', compact( 'data','displayCurrency','json','notify_url' ) );
    }

    public function notify(Request $request)
    {
        $input = Session::get('input_data');
        $order_data = Session::get('order_data');
        $success_url = route('payment.return');
        $cancel_url = route('payment.cancle');
        $input_data = $request->all();
        $order = new Order();

        $payment_id = Session::get('order_payment_id');

        $success = true;

        if (empty($input_data['razorpay_payment_id']) === false)
        {
        
            try
            {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $input_data['razorpay_payment_id'],
                    'razorpay_signature' => $input_data['razorpay_signature']
                );
        
                $this->api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
            }
        }

        if ($success === true){
        
            $order['pay_amount'] = $input['total'];
            $order['user_id'] = Auth::user()->id;
            $order['invest'] = $input['invest'];
            $order['method'] = $input['method'];
            $order['customer_email'] = $input['customer_email'];
            $order['customer_name'] = $input['customer_name'];
            $order['customer_phone'] = $input['customer_phone'];
            $order['order_number'] = Str::random(4).time();
            $order['customer_address'] = $input['customer_address'];
            $order['customer_city'] = $input['customer_city'];
            $order['customer_zip'] = $input['customer_zip'];
            $order['payment_status'] = "completed";
            $order['currency_sign'] = $input['currency_sign'];
            $order['subtitle'] = $input['subtitle'];
            $order['title'] = $input['title'];
            $order['details'] = $input['details'];
            $order['status'] = "pending";
            $order['txnid'] = $payment_id;

            $date = Carbon::now();
            $date = $date->addDays($request->days);
            $date = Carbon::parse($date)->format('Y-m-d h:i:s');
            $order['end_date'] = $date;
            $order->save();

            $notification = new Notification();
            $notification->order_id = $order->id;
            $notification->save();

            $trans = new Transaction();
            $trans->email = $order->customer_email;
            $trans->amount = $order->invest;
            $trans->type = "Invest";
            $trans->txnid = $order->order_number;
            $trans->user_id = $order->user_id;
            $trans->save();

            $notf = new UserNotification();
            $notf->user_id = Auth::user()->id;
            $notf->order_id = $order->id;
            $notf->type = "Invest";
            $notf->save();

            $gs =  Generalsetting::findOrFail(1);

            if($gs->is_smtp == 1)
            {
            $data = [
                'to' => $order->customer_email,
                'type' => "Invest",
                'cname' => $order->customer_name,
                'oamount' => $order->order_number,
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);            
            }
            else
            {
               $to = $order->customer_email;
               $subject = " You have invested successfully.";
               $msg = "Hello ".$order->customer_name."!\nYou have invested successfully.\nThank you.";
               $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
               mail($to,$subject,$msg,$headers);            
            }

            if($gs->is_affilate == 1)
            {
                $user = User::find($order->user_id);
                if ($user->referral_id != 0) 
                {
                    $val = $order->invest / 100;
                    $sub = $val * $gs->affilate_charge;
                    $sub = round($sub,2);
                    $ref = User::find($user->referral_id);
                    if(isset($ref))
                    {
                        $ref->income += $sub;
                        $ref->update();

                        $trans = new Transaction();
                        $trans->email = $ref->email;
                        $trans->amount = $sub;
                        $trans->type = "Referral Bonus";
                        $trans->txnid = $order->order_number;
                        $trans->user_id = $ref->id;
                        $trans->save();
                    }
                }
            }      
                
            return redirect($success_url);
            
        }
        return redirect($cancel_url);
    }
}
