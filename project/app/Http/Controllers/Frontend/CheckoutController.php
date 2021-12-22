<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Currency;
use App\Models\Product;
use Session;
use Auth;


class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function checkout($id)
    {
        if (!Session::has('product_id')) {
            return view('errors.404');
        }
        else {
            if($id != Session::get('product_id')){
            return view('errors.404');                
            }
        }
    	$data['product'] = Product::findOrFail($id);
        $data['gateways'] = PaymentGateway::where('status',1)->get();
        
        return view('frontend.checkout',$data);
    }
}
