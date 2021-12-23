<?php

namespace App\Http\Controllers\Frontend;
use App\Classes\GeniusMailer;
use App\Classes\GetUserAgents;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use PayPal\{
    Api\Item,
    Api\Payer,
    Api\Amount,
    Api\Payment,
    Api\ItemList,
    Rest\ApiContext,
    Api\Transaction,
    Api\RedirectUrls,
    Api\PaymentExecution,
    Auth\OAuthTokenCredential
};
use App\Models\Transaction as Tran;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $_api_context;
    public $orderRepositorty;

    public function __construct(OrderRepository $orderRepositorty)
    {
        $data = Paymentgateway::whereKeyword('paypal')->first();
        $paydata = $data->convertAutoData();
        
        $paypal_conf = \Config::get('paypal');
        $paypal_conf['client_id'] = $paydata['client_id'];
        $paypal_conf['secret'] = $paydata['client_secret'];
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        $this->orderRepositorty = $orderRepositorty;
    }

    public function store(Request $request){

        $settings = Generalsetting::findOrFail(1);
        $order = new Order;
        $paypal_email = $settings->paypal_business;
        $return_url = route('front.payreturn');
        $cancel_url = route('paypal.cancle');
        $notify_url = route('paypal.notify');

        $item_name = $settings->title." Order";
        $item_number = Str::random(4).time();
        $item_amount = $request->invest;
       
        // $order['pay_amount'] = $request->total;
        // $order['user_id'] = $request->user_id;
        // $order['invest'] = $request->invest;
        // $order['method'] = $request->method;
        // $order['customer_email'] = $request->customer_email;
        // $order['customer_name'] = $request->customer_name;
        // $order['customer_phone'] = $request->customer_phone;
        // $order['order_number'] = $item_number;
        // $order['customer_address'] = $request->customer_address;
        // $order['customer_city'] = $request->customer_city;
        // $order['customer_zip'] = $request->customer_zip;
        // $order['payment_status'] = "pending";
        // $order['currency_sign'] = $request->currency_sign;
        // $order['subtitle'] = $request->subtitle;
        // $order['title'] = $request->title;
        // $order['details'] = $request->details;

        // $date = Carbon::now();
        // $date = $date->addDays($request->days);
        // $date = Carbon::parse($date)->format('Y-m-d h:i:s');

        // $order['end_date'] = $date;
        // $order->save();

        $addionalData = ['item_number'=>$item_number];
        $this->orderRepositorty->order($request,'pending',$addionalData);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($item_name)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($item_amount);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($item_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($item_name.' Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($notify_url)
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
            
            
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                return redirect()->back()->with('unsuccess',$ex->getMessage());
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
           
            Session::put('paypal_data',$request->all());
            Session::put('paypal_payment_id', $payment->getId());
            Session::put('order_number',$item_number);

            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }


            return redirect()->back()->with('unsuccess','Unknown error occurred');

            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
            return redirect()->back()->with('unsuccess','Unknown error occurred');

    }

    public function notify(Request $request)
    {

        $payment_id = Session::get('paypal_payment_id');
        if (empty( $request['PayerID']) || empty( $request['token'])) {
            return redirect()->back()->with('error', 'Payment Failed'); 
        } 

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);


        $order_number = Session::get('order_number');


        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $resp = json_decode($payment, true);

                $order = Order::where('order_number',$order_number)->where('payment_status','pending')->first();
                $data['txnid'] = $resp['transactions'][0]['related_resources'][0]['sale']['id'];
                $data['payment_status'] = "completed";
                $order->update($data);

                $this->orderRepositorty->callAfterOrder($request,$order);

                // $notification = new Notification;
                // $notification->order_id = $order->id;
                // $notification->save();

                // $trans = new Tran();
                // $trans->email = $order->customer_email;
                // $trans->amount = $order->invest;
                // $trans->type = "Invest";
                // $trans->txnid = $order->order_number;
                // $trans->user_id = $order->user_id;
                // $trans->save();


                // $notf = new UserNotification;
                // $notf->user_id = $order->user_id;
                // $notf->order_id = $order->id;
                // $notf->type = "Invest";
                // $notf->save();


                // $gs =  Generalsetting::findOrFail(1);

                // if($gs->is_affilate == 1)
                // {
                //     $user = User::find($order->user_id);
                //     if ($user->referral_id != 0) 
                //     {
                //         $val = $order->invest / 100;
                //         $sub = $val * $gs->affilate_charge;
                //         $sub = round($sub,2);
                //         $ref = User::find($user->referral_id);
                //         if(isset($ref))
                //         {
                //             $ref->income += $sub;
                //             $ref->update();

                //             $trans = new Tran;
                //             $trans->email = $ref->email;
                //             $trans->amount = $sub;
                //             $trans->type = "Referral Bonus";
                //             $trans->txnid = $order->order_number;
                //             $trans->user_id = $ref->id;
                //             $trans->save();
                //         }
                //     }
                // }


                // if($gs->is_smtp == 1)
                // {
                // $data = [
                //     'to' => $order->customer_email,
                //     'type' => "Invest",
                //     'cname' => $order->customer_name,
                //     'oamount' => $order->order_number,
                //     'aname' => "",
                //     'aemail' => "",
                //     'wtitle' => "",
                // ];
        
                // $mailer = new GeniusMailer();
                // $mailer->sendAutoMail($data);            
                // }
                // else
                // {
                //    $to = $order->customer_email;
                //    $subject = " You have invested successfully.";
                //    $msg = "Hello ".$order->customer_name."!\nYou have invested successfully.\nThank you.";
                //    $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                //    mail($to,$subject,$msg,$headers);            
                // }



                Session::forget('paypal_data');
                Session::forget('paypal_payment_id');
                Session::forget('order_number');

                return redirect()->route('front.payreturn');
        }

        }



    function goRandomString($length = 10) {
        $characters = 'abcdefghijklmnpqrstuvwxyz123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }


}
