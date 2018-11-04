<?php

namespace App\Http\Controllers\Backend\Requisition;

use App\Models\Products\Product;
use App\Models\Requisition\Requisition;
use App\Models\UserPrivilegesModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Form;

class EditRequisition extends Controller
{
    public function index()
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','P02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('backend.requisition.editrequisition')->with('userPr',$userPr);
    }

    public function getreqdata()
    {
        $requisition = Requisition::query()->where('compCode',Auth::user()->compCode)
            ->where('status',1)
            ->with('item')->get();


        return Datatables::of($requisition)
            ->editColumn('reqType',function ($requisition) { return $requisition->reqType == 'P' ? 'Purchase' : 'Consumption';})

            ->addColumn('action', function ($requisition) {

                $product = Product::where('compCode',Auth::user()->compCode)->pluck('name','id');

                return '<button id="editrequisition" data-toggle="modal" data-target="#editReqModal'.$requisition->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="product.brand.delete/' . $requisition->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editCategoryModal -->
                    
                    <div class="modal fade" id="editReqModal'.$requisition->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Requisitions Data</h4>
                                </div>
                            <form class="form-horizontal" role="form" action="requisition.edit/'.$requisition->id.'" method="POST" >
                                <div class="modal-body">
                                   
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$requisition->id.'">
                                        
                                    <div class="form-group">
                                        <label for="tax_id" class="col-md-3 control-label">Req Type</label>
                                        <div class="col-md-8"> '.

                                            Form::select('reqType',array('0' => 'Please Select', 'P' => 'Purchase', 'C' => 'Consumption') , $requisition->reqType , array('id' => 'reqType', 'class' => 'form-control'))

                                . '    </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax_id" class="col-md-3 control-label">Req Type</label>
                                        <div class="col-md-8"> '.

                                            Form::select('product_id',$product, $requisition->product_id , array('id' => 'product_id', 'class' => 'form-control'))

                                . '    </div>
                                    </div>
                                    
                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Quantity</label>
                                        <div class="col-md-8">
                                            <input name="quantity" class="form-control" type="text" id="quantity" value="'.$requisition->quantity.'">
                                            
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

    public function update(Request $request,$id)
    {
        DB::beginTransaction();

        try {

            Requisition::where('id',$id)->update(['reqType'=>$request->input('reqType'),'product_id' => $request->input('product_id'),'quantity' => $request->input('quantity')]);
            $request->session()->flash('alert-success', 'Successfully Updated');

        } catch (\HttpException $e) {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Backend\Requisition\EditRequisition@index');
    }
}
