<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\User;
use Carbon\Carbon;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeController extends Controller
{
    public function store(Request $request){
        
        $settings = Generalsetting::find(1);
        
        $authorizeinfo    = PaymentGateway::whereKeyword('authorize.net')->first();
        $authorizesettings= $authorizeinfo->convertAutoData();

        $item_name = $settings->title." Order";
        $item_number = Str::random(4).time();
        $item_amount = $request->invest;
        
     
        $validator = Validator::make($request->all(),[
            'cardNumber' => 'required',
            'cardCVC' => 'required',
            'month' => 'required',
            'year' => 'required',
        ]);

        if ($validator->passes()) {
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($authorizesettings['login_id']);
            $merchantAuthentication->setTransactionKey($authorizesettings['txn_key']);

            // Set the transaction's refId
            $refId = 'ref' . time();

            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber(str_replace(' ','',$request->cardNumber));
            $year = $request->year;
            $month = $request->month;
            $creditCard->setExpirationDate($year.'-'.$month);
            $creditCard->setCardCode($request->cardCVC);

            // Add the payment data to a paymentType object
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
        
            // Create order information
            $orderr = new AnetAPI\OrderType();
            $orderr->setInvoiceNumber($item_number);
            $orderr->setDescription($item_name);

            // Create a TransactionRequestType object and add the previous objects to it
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction"); 
            $transactionRequestType->setAmount($item_amount);
            $transactionRequestType->setOrder($orderr);
            $transactionRequestType->setPayment($paymentOne);
            // Assemble the complete transaction request
            $requestt = new AnetAPI\CreateTransactionRequest();
            $requestt->setMerchantAuthentication($merchantAuthentication);
            $requestt->setRefId($refId);
            $requestt->setTransactionRequest($transactionRequestType);
        
            // Create the controller and get the response
            $controller = new AnetController\CreateTransactionController($requestt);
            if($authorizesettings['sandbox_check'] == 1){
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            }
            else {
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);                
            }

        
            if ($response != null) {
                if ($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();
                
                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        $order = new Order();

                        $order['pay_amount'] = $request->total;
                        $order['user_id'] = $request->user_id;
                        $order['invest'] = $request->invest;
                        $order['method'] = $request->method;
                        $order['customer_email'] = $request->customer_email;
                        $order['customer_name'] = $request->customer_name;
                        $order['customer_phone'] = $request->customer_phone;
                        $order['order_number'] = $item_number;
                        $order['customer_address'] = $request->customer_address;
                        $order['customer_city'] = $request->customer_city;
                        $order['customer_zip'] = $request->customer_zip;
                        $order['payment_status'] = "completed";
                        $order['currency_sign'] = $request->currency_sign;
                        $order['subtitle'] = $request->subtitle;
                        $order['title'] = $request->title;
                        $order['details'] = $request->details;
                        $order['status'] = "pending";
    
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
                        $notf->user_id = $request->user_id;
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
                        return view('front.success');

                    } else {
                        return back()->with('unsuccess', 'Payment Failed.');
                    }
                    // Or, print errors if the API request wasn't successful
                } else {
                    return back()->with('unsuccess', 'Payment Failed.');
                }      
            } else {
                return back()->with('unsuccess', 'Payment Failed.');
            }

        }
        return back()->with('unsuccess', 'Invalid Payment Details.');
    }
}
