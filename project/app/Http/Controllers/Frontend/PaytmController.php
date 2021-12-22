<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PaytmController extends Controller
{
    public function store(Request $request){

        if($request->currency_code != "INR")
        {
            return redirect()->back()->with('unsuccess','Please Select INR Currency For Paytm.');
        }

        $settings = Generalsetting::findOrFail(1);
        $order = new Order();
        $item_name = $settings->title." Order";
        $item_number = Str::random(4).time();
        $item_amount = $request->invest;


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
        $order['payment_status'] = "pending";
        $order['currency_sign'] = $request->currency_sign;
        $order['subtitle'] = $request->subtitle;
        $order['title'] = $request->title;
        $order['details'] = $request->details;

        $date = Carbon::now();
        $date = $date->addDays($request->days);
        $date = Carbon::parse($date)->format('Y-m-d h:i:s');

        $order['end_date'] = $date;
        $order->save();

        Session::put('order_number',$item_number);

        $data_for_request = $this->handlePaytmRequest( $item_number, $item_amount );
	    $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
	    $paramList = $data_for_request['paramList'];
	    $checkSum = $data_for_request['checkSum'];
	    return view( 'front.paytm-merchant-form', compact( 'paytm_txn_url', 'paramList', 'checkSum' ) );
    }

    public function handlePaytmRequest( $transaction_id, $amount) {
        $gs = Generalsetting::first();


    
            // Load all functions of encdec_paytm.php and config-paytm.php
            $this->getAllEncdecFunc();
            // $this->getConfigPaytmSettings();
            $checkSum = "";
            $paramList = array();
            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = $gs->paytm_merchant;
            $paramList["ORDER_ID"] = $transaction_id;
            $paramList["CUST_ID"] = $transaction_id;
            $paramList["INDUSTRY_TYPE_ID"] = $gs->paytm_industry;
            $paramList["CHANNEL_ID"] = 'WEB';
            $paramList["TXN_AMOUNT"] = $amount;
            $paramList["WEBSITE"] = $gs->paytm_website;
            $paramList["CALLBACK_URL"] = route('paytm.notify');
            $paytm_merchant_key = $gs->paytm_secret;
            //Here checksum string will return by getChecksumFromArray() function.
            $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );
            return array(
                'checkSum' => $checkSum,
                'paramList' => $paramList
            );
        }

    	function getAllEncdecFunc() {
            function encrypt_e($input, $ky) {
                $key   = html_entity_decode($ky);
                $iv = "@@@@&&&&####$$$$";
                $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
                return $data;
        }

        function decrypt_e($crypt, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }

        function pkcs5_pad_e($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }

        function pkcs5_unpad_e($text) {
            $pad = ord($text(strlen($text) - 1));
            if ($pad > strlen($text))
                return false;
            return substr($text, 0, -1 * $pad);
        }

        function generateSalt_e($length) {
            $random = "";
            srand((double) microtime() * 1000000);
            $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
            $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
            $data .= "0FGH45OP89";
            for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
            }
            return $random;
        }

        function checkString_e($value) {
            if ($value == 'null')
                $value = '';
            return $value;
        }

        function getChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getChecksumFromString($str, $key) {
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function verifychecksum_e($arrayList, $key, $checksumvalue) {
            $arrayList = removeCheckSumParam($arrayList);
            ksort($arrayList);
            $str = getArray2StrForVerify($arrayList);
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function verifychecksum_eFromStr($str, $key, $checksumvalue) {
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function getArray2Str($arrayList) {
            $findme   = 'REFUND';
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pos = strpos($value, $findme);
                $pospipe = strpos($value, $findmepipe);
                if ($pos !== false || $pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function getArray2StrForVerify($arrayList) {
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function redirect2PG($paramList, $key) {
            $hashString = getchecksumFromArray($paramList, $key);
            $checksum = encrypt_e($hashString, $key);
        }

        function removeCheckSumParam($arrayList) {
            if (isset($arrayList["CHECKSUMHASH"])) {
                unset($arrayList["CHECKSUMHASH"]);
            }
            return $arrayList;
        }

        function getTxnStatus($requestParamList) {
            return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
        }

        function getTxnStatusNew($requestParamList) {
            return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
        }

        function initiateTxnRefund($requestParamList) {
            $CHECKSUM = getRefundChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
            $requestParamList["CHECKSUM"] = $CHECKSUM;
            return callAPI(PAYTM_REFUND_URL, $requestParamList);
        }

        function callAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

        function callNewAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

        function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getRefundArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getRefundArray2Str($arrayList) {
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pospipe = strpos($value, $findmepipe);
                if ($pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

            function callRefundAPI($refundApiURL, $requestParamList) {
                $jsonResponse = "";
                $responseParamList = array();
                $JsonData =json_encode($requestParamList);
                $postData = 'JsonData='.urlencode($JsonData);
                $ch = curl_init($apiURL);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_URL, $refundApiURL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $jsonResponse = curl_exec($ch);
                $responseParamList = json_decode($jsonResponse,true);
                return $responseParamList;
            }
        }
        /**
         * Config Paytm Settings from config_paytm.php file of paytm kit
         */
        function getConfigPaytmSettings() {

            $gs = Generalsetting::first();
        
            if ($gs->paytm_mode == 'sandbox') {
            define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
            } elseif ($gs->paytm_mode == 'live') {
            define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
            }
    
            define('PAYTM_MERCHANT_KEY', $gs->paytm_secret); //Change this constant's value with Merchant key downloaded from portal
            define('PAYTM_MERCHANT_MID', $gs->paytm_merchant); //Change this constant's value with MID (Merchant ID) received from Paytm
            define('PAYTM_MERCHANT_WEBSITE', $gs->paytm_website); //Change this constant's value with Website name received from Paytm
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
            if (PAYTM_ENVIRONMENT == 'PROD') {
                $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
                $PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
            }
            define('PAYTM_REFUND_URL', '');
            define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
        }

        public function paytmCallback( Request $request ) {
            
            $order_number = Session::get('order_number');

            $transaction_id = $request['ORDERID'];
            if ( 'TXN_SUCCESS' === $request['STATUS'] ) {
                $transaction_id = $request['TXNID'];
                $order = Order::where('order_number',$order_number)->where('payment_status','pending')->first();
               
                $data['txnid'] = $transaction_id;
                $data['payment_status'] = "completed";
                $order->update($data);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();
    
                $trans = new Transaction;
                $trans->email = $order->customer_email;
                $trans->amount = $order->invest;
                $trans->type = "Invest";
                $trans->txnid = $order->order_number;
                $trans->user_id = $order->user_id;
                $trans->save();
    
    
                $notf = new UserNotification;
                $notf->user_id = $order->user_id;
                $notf->order_id = $order->id;
                $notf->type = "Invest";
                $notf->save();
    
    
                $gs =  Generalsetting::findOrFail(1);
    
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

                Session::forget('order_number');

                return view('front.success');
    
            } else if( 'TXN_FAILURE' === $request['STATUS'] ){
    
                return redirect()->back()->with('unsuccess', 'Payment Cancelled.');
            }
        }

}
