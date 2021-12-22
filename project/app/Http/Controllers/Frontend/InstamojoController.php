<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\GeniusMailer;
use App\Classes\Instamojo;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\Models\Notification;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class InstamojoController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $data = PaymentGateway::whereKeyword('instamojo')->first();
        $gs = Generalsetting::first();
        $total =  $request->invest;
        $paydata = $data->convertAutoData();

        if($request->currency_code != "INR")
        {
            return redirect()->back()->with('unsuccess',__('Please Select INR Currency For This Payment.'));
        }


        $order['item_name'] = $gs->title." Order";
        $order['item_number'] = Str::random(4).time();
        $order['item_amount'] = $total;
        $cancel_url = route('payment.cancle');
        $notify_url = route('instamojo.notify');

        if($paydata['sandbox_check'] == 1){
        $api = new Instamojo($paydata['key'], $paydata['token'], 'https://test.instamojo.com/api/1.1/');
        }
        else {
        $api = new Instamojo($paydata['key'], $paydata['token']);
        }

        try {
            $response = $api->paymentRequestCreate(array(
                "purpose" => $order['item_name'],
                "amount" => $order['item_amount'],
                "send_email" => true,
                "email" => $request->customer_email,
                "redirect_url" => $notify_url
            ));
            $redirect_url = $response['longurl'];
        /** add payment ID to session **/
        Session::put('input_data',$input);
        Session::put('order_data',$order);
        Session::put('order_payment_id', $response['id']);

        return redirect($redirect_url);

        }
        catch (Exception $e) {
            return redirect($cancel_url)->with('unsuccess','Error: ' . $e->getMessage());
        }
    }


    public function notify(Request $request)
    {
        $input = Session::get('input_data');
        $order_data = Session::get('order_data');
        $success_url = route('payment.return');
        $cancel_url = route('payment.cancle');
        $input_data = $request->all();
        $order = new Order();
 
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('order_payment_id');

        if($input_data['payment_status'] == 'Failed'){
            return redirect($cancel_url);
        }

        if ($input_data['payment_request_id'] == $payment_id) {

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
