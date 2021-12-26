<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use App\Classes\GeniusMailer;
use App\Models\Order;
use App\Models\User;
use App\Models\Generalsetting;

class InvestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables($status)
    {
        if($status == 'pending'){
            $datas = Order::where('status','=','pending')->where('payment_status','pending')->orderBy('id','desc');
        }
        if($status == 'running'){
            $datas = Order::where('status','=','pending')->where('payment_status','completed')->orderBy('id','desc');
        }
        elseif($status == 'processing') {
            $datas = Order::where('status','=','processing')->where('payment_status','completed')->orderBy('id','desc');
        }
        elseif($status == 'completed') {
            $datas = Order::where('status','=','completed')->where('payment_status','completed')->orderBy('id','desc');
        }
        elseif($status == 'declined') {
            $datas = Order::where('status','=','declined')->where('payment_status','completed')->orderBy('id','desc');
        }
        else{
          $datas = Order::orderBy('id','desc');  
        }

         
        return Datatables::of($datas)
                            ->editColumn('customer_email', function(Order $data) {
                                return '<a href="">'.$data->customer_email.'</a>';
                            })
                            ->editColumn('pay_amount', function(Order $data) {
                                $gs = Generalsetting::find(1);
                                return $gs->currency_sign.$data->pay_amount;
                            })
                            ->editColumn('invest', function(Order $data) {
                                $gs = Generalsetting::find(1);
                                return $gs->currency_sign.$data->invest;
                            })
                            ->editColumn('status', function(Order $data) {

                                if($data->status == 'pending'){
                                    $status = "Pending";
                                    $status_sign = $data->status == 'pending' ? 'warning' : '';
                                }elseif($data->status == 'processing'){
                                    $status = "Processing";
                                    $status_sign = $data->status == 'processing' ? 'info' : '';
                                }elseif($data->status == 'declined'){
                                    $status = "Declined";
                                    $status_sign = $data->status == 'declined' ? 'danger' : '';
                                }else{
                                    $status = "Completed";
                                    $status_sign = $data->status == 'declined' ? 'success' : '';
                                }

                                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  '.$status .'
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.pendinginvest.status',['id1' => $data->id, 'status' => 'pending']).'">'.__("Pending").'</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.pendinginvest.status',['id1' => $data->id, 'status' => 'completed']).'">'.__("Completed").'</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.pendinginvest.status',['id1' => $data->id, 'status' => 'declined']).'">'.__("Declined").'</a>
                                </div>
                              </div>';
                            })
                            ->addColumn('action', function(Order $data) {
                                return '<div class="actions-btn"><a href="'.route('admin.invests.show',$data->id).'" class="btn btn-primary btn-sm btn-rounded"><i class="fas fa-eye"></i> '.__("View Details").'
                              </a></div>';
                            }) 
                            ->rawColumns(['id','customer_email','status','action'])
                            ->toJson();
    }

    public function index(){
        return view('admin.invest.index');
    }

    public function pending(){
        return view('admin.invest.pending');
    }

    public function running(){
        return view('admin.invest.running');
    }

    public function completed(){
        return view('admin.invest.completed');
    }

    public function declined(){
        return view('admin.invest.declined');
    }

    public function show($id){
        $data['order'] = Order::findOrFail($id);
        return view('admin.invest.details',$data);
    }
}
