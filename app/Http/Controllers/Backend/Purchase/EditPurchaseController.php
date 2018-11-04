<?php

namespace App\Http\Controllers\Backend\Purchase;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Purchase;
use App\Models\Products\Product;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Form;

class EditPurchaseController extends Controller
{
    public $comp_code;
    public $user_id;

    /**
     * EditPurchaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->comp_code = Auth::user()->compCode;
            $this->user_id = Auth::user()->id;

            return $next($request);
        });
    }

    public function index()
    {

//        $data=TransProduct::
//        join('purchases','')
//        ->where('compCode',$this->compCode)->get();
//
//        dd($data);

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','P02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }


        return view('backend.purchase.editpurchaseindex')->with('userPr',$userPr);
    }

    public function getpdata()
    {
        $purchase = Purchase::query()->where('compCode',$this->comp_code)
            ->join('trans_products as p','refno','=','refno')
            ->where('status',1)
            ->with('relationship')->get();


        return Datatables::of($purchase)

            ->addColumn('action', function ($purchase) {

                $product = Product::where('compCode',Auth::user()->compCode)->pluck('name','id');

                return '<button id="editrequisition" data-toggle="modal" data-target="#editReqModal'.$purchase->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="product.brand.delete/' . $purchase->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editCategoryModal -->
                    
                    <div class="modal fade" id="editReqModal'.$purchase->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Requisitions Data</h4>
                                </div>
                            <form class="form-horizontal" role="form" action="requisition.edit/'.$purchase->id.'" method="POST" >
                                <div class="modal-body">
                                   
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$purchase->id.'">
                                        
                                    <div class="form-group">
                                        <label for="tax_id" class="col-md-3 control-label">Req Type</label>
                                        <div class="col-md-8"> '.

                Form::select('reqType',array('0' => 'Please Select', 'P' => 'Purchase', 'C' => 'Consumption') , $purchase->reqType , array('id' => 'reqType', 'class' => 'form-control'))

                . '    </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax_id" class="col-md-3 control-label">Req Type</label>
                                        <div class="col-md-8"> '.

                Form::select('product_id',$product, $purchase->product_id , array('id' => 'product_id', 'class' => 'form-control'))

                . '    </div>
                                    </div>
                                    
                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Quantity</label>
                                        <div class="col-md-8">
                                            <input name="quantity" class="form-control" type="text" id="quantity" value="'.$purchase->quantity.'">
                                            
                                        </div>
                                    </div>
                                        
                                
                                    <div class="form-group">
                                        <div class="col-md-10 col-md-offset-1">
                                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                                        </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>';
            })
            ->make(true);
    }
}
