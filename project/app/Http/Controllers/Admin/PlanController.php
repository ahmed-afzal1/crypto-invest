<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use Datatables;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = Product::orderBy('id','desc')->get();
         
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)

                            ->editColumn('price', function(Product $data) {
                                $sign = Currency::where('is_default','=',1)->first();
                                $price = $sign->sign.$data->price;
                                return  $price;
                            }) 
                            ->addColumn('action', function(Product $data) {

                                return '<div class="btn-group mb-1">
                                  <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.'Actions' .'
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="' . route('admin.plan.edit',$data->id) . '"  class="dropdown-item">'.__("Edit").'</a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.plan.delete',$data->id).'">'.__("Delete").'</a>
                                  </div>
                                </div>';
  
                              })
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    //*** GET Request
    public function index()
    {
        return view('admin.plan.index');
    }

    public function info()
    {
        return view('admin.product.info');
    }

    //*** GET Request
    public function create()
    {
        $data['currency'] = Currency::where('is_default','=',1)->first();
        return view('admin.plan.create',$data);
    }

    //*** GET Request
    public function status($id1,$id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** POST Request
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|unique:products',
            'min_price'=> 'required',
            'min_price'=> 'required',
            'days'=> 'required',
            'percentage'=> 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Logic Section 
        $percentage = $request->percentage;  
        $data = new Product;
        $input = $request->all();

        if($percentage>=100)
        {
            $input['percentage'] =  $percentage;
            $data->fill($input)->save();
            //logic Section Ends

            //--- Redirect Section        
            $msg = 'New Plan Added Successfully.<a href="'.route('admin.plan.index').'">View Plan Lists.</a>';
            return response()->json($msg);      
            //--- Redirect Section Ends  
        }
        else{
            $msg = 'Payout Rate Must be Larger than 100%';
            return response()->json(array('errors' => [$msg]));
        }
              
    }
 
    //*** GET Request
    public function edit($id)
    {
        $data = Product::findOrFail($id);
        $currency = Currency::where('is_default','=',1)->first();
        return view('admin.plan.edit',compact('data','currency'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|unique:products,title,'.$id,
            'min_price'=> 'required',
            'min_price'=> 'required',
            'days'=> 'required',
            'percentage'=> 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        //-- Logic Section
        $data = Product::findOrFail($id);
        $input = $request->all();
        $percentage = $request->percentage; 
        if($percentage>=100)
            {
                $input['percentage'] =  $percentage;
                $data->update($input);
                //logic Section Ends
                
                //--- Redirect Section        
                $msg = 'Plan Updated Successfully.<a href="'.route('admin.plan.index').'">View Plan Lists.</a>';
                return response()->json($msg);      
                //--- Redirect Section Ends    
            }
            else{
                $msg = 'Payout Rate Must be Larger than 100%';
                return response()->json(array('error' => $msg));
            }
        
    }

    //*** GET Request
    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Plan Deleted Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends    

        // PRODUCT DELETE ENDS  
    }
}
