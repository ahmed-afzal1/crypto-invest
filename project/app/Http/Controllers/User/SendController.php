<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Str;

class SendController extends Controller
{
    public function index(){
        return view('user.transfer.index');
    }

    public function store(Request $request){
        $rules = [
            'email'=> 'required',
            'amount'=> 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
        }
        
        $user = auth()->user();
        if($request->email == $user->email){
            return response()->json(array('errors' => [ 0 => 'You can not send money yourself!!' ])); 
        }

        if($request->amount>0 && $user->income>0){
            if($user->income > $request->amount){

                if($receiver = User::where('email',$request->email)->first()){
                    $transaction = new Transaction();
                    $transaction->user_id = auth()->user()->id;
                    $transaction->receiver_id = $user->id;
                    $transaction->email = auth()->user()->email;
                    $transaction->amount = $request->amount;
                    $transaction->type = 'Send Money';
                    $transaction->txnid	 = Str::random(4).time();
                    $transaction->save();
    
                    $receiver->increment('income',$request->amount);
                    $user->decrement('income',$request->amount);
    
                    return response()->json('Money Send Successfully.');
                }else{
                    return response()->json(array('errors' => [ 0 => 'Send Account not found!!' ])); 
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Insufficient Balance.' ]));
            }
        }else{
            return response()->json(array('errors' => [ 0 => 'Amount should be greater than 0!!' ])); 
        }
    }
}
