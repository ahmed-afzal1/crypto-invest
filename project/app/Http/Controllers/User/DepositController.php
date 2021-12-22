<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(){
        $data['deposits'] = Deposit::orderby('id','desc')->where('user_id',auth()->user()->id)->get();
        return view('user.deposit.index',$data);
    }

    public function create(){
        $data['gateways'] = PaymentGateway::OrderBy('id','desc')->get();
        return view('user.deposit.create',$data);
    }
}
