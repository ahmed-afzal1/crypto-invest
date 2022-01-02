<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(){
        $data['deposits'] = Deposit::orderby('id','desc')->where('user_id',auth()->user()->id)->paginate(10);
        return view('user.deposit.index',$data);
    }

    public function create(){
        $data['availableGatways'] = ['flutterwave','authorize.net','razorpay','mollie','paytm','paystack','instamojo','stripe','paypal'];
        $data['gateways'] = PaymentGateway::OrderBy('id','desc')->get();
        return view('user.deposit.create',$data);
    }
}
