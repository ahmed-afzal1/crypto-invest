<?php

namespace App\Http\Controllers\User;

use App\Classes\GoogleAuthenticator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\User;
use App\Traits\Payout;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();  
        $nowDate = Carbon::now();
        
        $orders = Order::where('status','running')->where('user_id',auth()->user()->id)->where('income_add_status',0)->orderBy('id','desc')->get();

        foreach($orders as $key=>$val){
            $result = $nowDate->gt($val->end_date);
            if($result){
                $user = User::findOrFail($val->user_id);
                $user->increment('income',$val->pay_amount);
                $val->update(['income_add_status'=>1,'status'=>'completed']);
            }
        }
        $investAmount = Order::where('user_id',auth()->user()->id)->where('payment_status','completed')->sum('invest');
        return view('user.dashboard',compact('user','investAmount'));
    }

    public function trans()
    {
        $user = Auth::user();  
        return view('user.transactions',compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();  
        return view('user.profile',compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:users,email,'.Auth::user()->id
        ]);

        $input = $request->all();  
        $data = Auth::user();        
        if ($file = $request->file('photo')) 
        {              
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/users/',$name);
            @unlink('assets/images/users/'.$data->photo);
        
            $input['photo'] = $name;

            $input['is_provider'] = 0;
        }
         
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return redirect()->back()->with('success',$msg);
    }

    public function resetform()
    {
        return view('user.reset');
    }

    public function reset(Request $request)
    {
        $user = Auth::user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $user->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));     
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));   
            }
        }
        $user->update($input);
        $msg = 'Successfully change your password';
        return response()->json($msg);
    }

    public function showTwoFactorForm()
    {
        $gnl = Generalsetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->name . '@' . $gnl->title, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->name . '@' . $gnl->title, $prevcode);

        return view('user.twofactor.index', compact('secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function createTwoFactor(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode == $request->code) {
            $user->go = $request->key;
            $user->twofa = 1;
            $user->save();
            
            return redirect()->back()->with('success','Two factor authentication activated');
        } else {
            return redirect()->back()->with('error','Something went wrong!');
        }
    }


    public function disableTwoFactor(Request $request)
    {

        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->go;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {

            $user->go = null;
            $user->twofa = 0;

            $user->save();

            return redirect()->back()->with('success','Two factor authentication disabled');
        } else {
            return redirect()->back()->with('error','Something went wrong!');
        }
    }

    public function affilate_code()
    {
        $user = Auth::guard('web')->user();
        return view('user.affilate_code',compact('user'));
    }


}
