<?php

namespace App\Http\Controllers\Frontend;

use App;
use URL;
use Auth;
use Hash;
use Config;
use Session;
use Redirect;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Classes\BlockIO;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Classes\CoinPaymentsAPI;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\PaymentGateway;
use App\Repositories\OrderRepository;
use Illuminate\Support\Str;

class BlockIOController extends Controller
{
    public $orderRepositorty;

    public function __construct(OrderRepository $orderRepositorty)
    {
        $this->orderRepositorty = $orderRepositorty;
    }

    public function blockioInvest()
    {
        return view('frontend.blockio');
    }

    public function blockiocallback(Request $request)
    {


        $fpbt = fopen('blockIO-Response'.time().'.txt', 'w');
        fwrite($fpbt, json_encode($request->all(),true));
        fclose($fpbt);

        $notifyID = $request['notification_id'];
        $amountRec = $request['data']['amount_received'];
        $bitTran = $request['data']['txid'];


            if (Order::where('notify_id',$notifyID)->exists()){
                $order = Order::where('notify_id',$notifyID)->where('payment_status','pending')->first();

                if ($order->coin_amount <= $amountRec) {
                    $data['txnid'] = $bitTran;
                    $data['payment_status'] = "Completed";
                    $order->update($data);

                    $this->orderRepositorty->callAfterOrder($request,$order);
                }
            }

        
    }

    function curlGetCall($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec ($ch);
        curl_close ($ch);

        return $data;
    }

    public function deposit_new(Request $request){
        $blockinfo    = PaymentGateway::whereKeyword('block.io.btc')->first();
        $blocksettings= $blockinfo->convertAutoData();

        if($request->invest > 0){

            $methods = $request->method;

            $version = 2;
            $coin = "BTC";
            $my_api_key = $blocksettings['blockio_api_btc'];
            
            $blockchain    = PaymentGateway::whereKeyword('blockChain')->first();
            $blockchain= $blockchain->convertAutoData();

            $secret = $blockchain['secret_string'];

            if($methods == "BlockIO(LTC)"){
                $blockinfo    = PaymentGateway::whereKeyword('block.io.ltc')->first();
                $blocksettings= $blockinfo->convertAutoData();
                $coin = "Litecoin";
                $my_api_key = $blocksettings['blockio_api_ltc'];

            }elseif ($methods == "BlockIO(DGC)"){
                $coin = "Dogecoin";
                $blockinfo    = PaymentGateway::whereKeyword('block.io.dgc')->first();
                $blocksettings= $blockinfo->convertAutoData();
                $my_api_key = $blocksettings['blockio_api_dgc'];

            }


            
            $block_io = new BlockIO($my_api_key, $secret, $version);
            $biorate = 1;

            $acc = Auth::user()->id;
            $item_number = Str::random(4).time();

            $item_amount = $request->invest;
            $currency_code = $request->currency_code;

            $bcoin = round($item_amount / $biorate, 8);
    
                $ad = $block_io->get_new_address();
    
                if ($ad->status == 'success') {
                    $blockad = $ad->data;
                    $wallet = $blockad->address;
                    $data['btc_wallet'] = $wallet;
                    $data['btc_amo'] = $bcoin;
                    $data->update();
                } else {
                    return back()->with('danger', 'Failed to Process');
                }

            $my_callback_url = url('/').'blockio/notify?transx_id='.$item_number.'&secret='.$secret;

            $root_url = 'https://block.io/api/v2/';

            $call_address = $root_url.'get_new_address/?api_key=' .$my_api_key;
            $addResponse = $this->curlGetCall($call_address);

            $addObject = json_decode($addResponse);

            $address = $addObject->data->address;

            $addionalData = ['item_number'=>$item_number];
            $this->orderRepositorty->order($request,'pending',$addionalData);


            session(['address' => $address,'coin' => $coin,'amount' => $bcoin,'currency_value' => $item_amount,'currency_sign' => $request->currency_sign,'accountnumber' => $acc]);

            return redirect('invest/blockio');

        }
        return redirect()->back()->with('error','Please enter a valid amount.')->withInput();

    }

    public function deposit(Request $request)
    {
        $blockinfo    = PaymentGateway::whereKeyword('block.io.btc')->first();
        $blocksettings= $blockinfo->convertAutoData();

        if($request->invest > 0){

            $methods = $request->method;
            $version = 2;
            $coin = "BTC";
            $my_api_key = $blocksettings['blockio_api_btc'];
        

             if($methods == "BlockIO(LTC)"){
                $blockinfo    = PaymentGateway::whereKeyword('block.io.ltc')->first();
                $blocksettings= $blockinfo->convertAutoData();
                $coin = "Litecoin";
                $my_api_key = $blocksettings['blockio_api_ltc'];

            }elseif ($methods == "BlockIO(DGC)"){
                $coin = "Dogecoin";
                $blockinfo    = PaymentGateway::whereKeyword('block.io.dgc')->first();
                $blocksettings= $blockinfo->convertAutoData();
                $my_api_key = $blocksettings['blockio_api_dgc'];

            }
            
        
            $acc = Auth::user()->id;
            $item_number = Str::random(4).time();;

            $item_amount = $request->invest;
            $currency_code = $request->currency_code;
            
            $blockchain    = PaymentGateway::whereKeyword('blockChain')->first();
            $blockchain= $blockchain->convertAutoData();
            $secret = $blockchain['secret_string'];


             $my_callback_url = route('blockio.notify');


            $block_io = new BlockIO($my_api_key, $secret, $version);

            $biorate = 1;
        
            $coin_amount = round($item_amount / $biorate, 8);

            $root_url = 'https://block.io/api/v2/';
            $addObject = $block_io->get_new_address(array());

            $address = $addObject->data->address;
             
            $notifyObject = $block_io->create_notification(array('type' => 'address', 'address' =>urlencode($address) , 'url' => $my_callback_url));

            $notifyID = $notifyObject->data->notification_id;

            $addionalData = ['item_number'=>$item_number];
            $this->orderRepositorty->order($request,'pending',$addionalData);
            							
            $qrcode_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=bitcoin:".$address."?amount=".$coin_amount."&choe=UTF-8";


            session(['address' => $address,'coin' => $coin,'qrcode_url' => $qrcode_url,'amount' => $coin_amount,'currency_value' => $item_amount,'currency_sign' => $request->currency_sign,'accountnumber' => $acc]);

            return redirect('invest/blockio');


        }
        return redirect()->back()->with('error','Please enter a valid amount.')->withInput();

    }

}
