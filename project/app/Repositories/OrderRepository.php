<?php
namespace App\Repositories;

use App\Classes\GeniusMailer;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderRepository
{
    public $gs;

    public function __construct()
    {
        $this->gs = Generalsetting::findOrFail(1);
    }

    public function order($request,$status,$addionalData){
        $order = new Order();

        if($request->currency_id){
            $currencyValue = Currency::where('id',$request->currency_id)->first();
        }

        if($request->currency_id){
            $order['pay_amount'] = $request->total/$currencyValue->value;
        }else{
            $order['pay_amount'] = $request->total;
        }

        $order['user_id'] = $request->user_id;

        if($request->currency_id){
            $order['invest'] = $request->invest/$currencyValue->value;
        }else{
            $order['invest'] = $request->invest;
        }
        $order['method'] = $request->method;
        $order['customer_email'] = $request->customer_email;
        $order['customer_name'] = $request->customer_name;
        $order['customer_phone'] = $request->customer_phone;

        if(isset($addionalData['item_number'])){
            $order['order_number'] = $addionalData['item_number'];
        }
        $order['customer_address'] = $request->customer_address;
        $order['customer_city'] = $request->customer_city;
        $order['customer_zip'] = $request->customer_zip;
        $order['payment_status'] = "completed";
        $order['currency_sign'] = $request->currency_sign;
        $order['subtitle'] = $request->subtitle;
        $order['title'] = $request->title;
        $order['details'] = $request->details;

        if($status == 'complete'){
            $order['payment_status'] = "completed";
        }else{
            $order['payment_status'] = "pending";
        }

        if($status == 'manual'){
            $order['payment_status'] = "pending";
        }

        if(isset($addionalData['txnid'])){
            $order['txnid'] = $addionalData['txnid'];
        }

        if(isset($addionalData['charge_id'])){
            $order['charge_id'] = $addionalData['charge_id'];
        }

        $date = Carbon::now();
        $date = $date->addDays($request->days);
        $date = Carbon::parse($date)->format('Y-m-d h:i:s');
        $order['end_date'] = $date;
        $order->save();

        if($status == 'complete' || $status == 'manual'){
            $this->callAfterOrder($request,$order);
        }
    }

    public function OrderFromSession($request,$status,$addionalData){
        $input = Session::get('input_data');

        $order = new Order();

        if($input['currency_id']){
            $currencyValue = Currency::where('id',$input['currency_id'])->first();
        }

        if($input['currency_id']){
            $order['pay_amount'] = $input['total']/$currencyValue->value;
        }else{
            $order['pay_amount'] = $input['total'];
        }

        if($input['currency_id']){
            $order['invest'] = $input['invest']/$currencyValue->value;
        }else{
            $order['invest'] = $input['invest'];
        }

        $order['user_id'] = auth()->user()->id;
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
        $order['txnid'] = $addionalData['txnid'];

        $date = Carbon::now();
        $date = $date->addDays($input['days']);
        $date = Carbon::parse($date)->format('Y-m-d h:i:s');
        $order['end_date'] = $date;
        $order->save();

        if($status == 'complete'){
            $this->callAfterOrder($request,$order);
        }
    }

    public function callAfterOrder($request,$order){

        $this->createNotification($order);
        $this->createTransaction($order);
        $this->createUserNotification($request,$order);
        $this->sendMail($order);
        $this->refferalBonus($order);
    }

    public function createNotification($order){
        $notification = new Notification();
        $notification->order_id = $order->id;
        $notification->save();
    }

    public function createTransaction($order){
        $trans = new Transaction();
        $trans->email = $order->customer_email;
        $trans->amount = $order->invest;
        $trans->type = "Invest";
        $trans->txnid = $order->order_number;
        $trans->user_id = $order->user_id;
        $trans->save();
    }

    public function createUserNotification($request,$order){
        $notf = new UserNotification();
        $notf->user_id = $order->user_id;
        $notf->order_id = $order->id;
        $notf->type = "Invest";
        $notf->save();
    }

    public function sendMail($order){
        if($this->gs->is_smtp == 1)
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
           $headers = "From: ".$this->gs->from_name."<".$this->gs->from_email.">";
           mail($to,$subject,$msg,$headers);            
        }
    }

    public function refferalBonus($order){
        if($this->gs->is_affilate == 1)
        {
            $user = User::find($order->user_id);
            if ($user->referral_id != 0) 
            {
                $val = $order->invest / 100;
                $sub = $val * $this->gs->affilate_charge;
                $sub = round($sub,2);
                $ref = User::find($user->referral_id);
                if(isset($ref))
                {
                    $ref->income += $sub;
                    $ref->update();

                    $trans = new Transaction;
                    $trans->email = $ref->email;
                    $trans->amount = $sub;
                    $trans->type = "Referral Bonus";
                    $trans->txnid = $order->order_number;
                    $trans->user_id = $ref->id;
                    $trans->save();
                }
            }
        }
    }

}