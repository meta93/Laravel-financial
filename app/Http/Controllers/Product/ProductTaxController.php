<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Tax;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Form;

class ProductTaxController extends Controller
{
    public function index()
    {

        $userPr = UserPrivilegesModel::query()->where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','P02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

//        $taxes = Tax::query()
//            ->select('id','taxName','applicableOn','rate','calculatingMode','description','status')
//            ->where('compCode',Auth::user()->compCode)->first();

        return view('product.productTaxIndex')->with('userPr',$userPr);
    }


    public function getData()
    {
        $taxes = Tax::query()
            ->select('id','tax_id','taxName','applicableOn','rate','calculatingMode','description','status')
            ->where('compCode',Auth::user()->compCode);


        return Datatables::of($taxes)

            ->addColumn('status', function ($taxes) {
                if($taxes->status == 1)
                    return '<input type="checkbox" name="status" value="'.$taxes->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$taxes->compCode.'" disabled="disabled">';
            })

            ->addColumn('action', function ($taxes) {

                if($taxes->status == 1)
                    $chk = 'checked="checked"';
                else
                    $chk= "";

                return '<button id="editproductsize" data-toggle="modal" data-target="#editModelModal'.$taxes->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="product.tax.delete/' . $taxes->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editModelModal -->
                    
                    <div class="modal fade" id="editModelModal'.$taxes->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">X</button>
                                    <h4 class="modal-title">Edit Models Data</h4>
                                </div>
                                <form class="form-horizontal" role="form" action="product.tax.edit/'.$taxes->id.'" method="POST" >
                                <div class="modal-body">
                                    
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$taxes->id.'"> 

                
                
                                    
                                        <div class="form-group">
                                            <label for="size" class="col-md-3 control-label">Tax Name</label>
                                            <div class="col-md-8">
                                                <input id="taxName" type="text" class="form-control" name="taxName" value="'.$taxes->taxName.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="applicableOn" class="col-md-3 control-label">Applicable On</label>
                                            <div class="col-md-8"> '.

                                            Form::select('applicableOn', array('S' => 'Sales', 'P' => 'Purchase', 'B' => 'Both'), $taxes->applicableOn , array('id' => 'applicableOn', 'class' => 'form-control'))
                                                
                                    . '    </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="rate" class="col-md-3 control-label">Rate (%)</label>
                                            <div class="col-md-8">
                                                <input id="rate" type="text" class="form-control" name="rate" value="'.$taxes->rate.'" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="calculatingMode" class="col-md-3 control-label">Calculating Mode</label>
                                            <div class="col-md-8"> '.

                                            Form::select('calculatingMode', array('P' => 'Purcentage', 'F' => 'Fixed'), $taxes->calculatingMode , array('id' => 'calculatingMode', 'class' => 'form-control'))

                                    . '    </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="description" class="col-md-3 control-label">Description</label>
                                            <div class="col-md-8">
                                                <input id="description" type="text" class="form-control" name="description" value="'.$taxes->description.'">
                                            </div>
                                        </div>
                                                                                
                                        <div class="form-group">
                                            <label for="status" class="col-md-4 control-label">Active ?</label>
                                            <div class="col-md-6">
                                                <input name="status" type="checkbox" id="'.$taxes->id.'" '.$chk.'>
                                            </div>
                                        </div>
                                        
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
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

    public function add(Request $request)
    {


        $maxCode = Tax::where('compCode',$request->user()->compCode)
            ->max('tax_id');

        if(!empty($maxCode))
        {
            $taxId = $maxCode + 1;
        }
        else
        {
            $taxId = '100001';
        }


        DB::beginTransaction();
//
        try {

            Tax::create([
                'compCode' => Auth::user()->compCode,
                'tax_id' => $taxId,
                'taxName' => $request->input('taxName'),
                'applicableOn' => $request->input('applicableOn'),
                'rate' => $request->input('rate'),
                'calculatingMode' => $request->input('calculatingMode'),
                'description' => $request->input('description'),
                'status' =>true
            ]);

            $request->session()->flash('alert-success', $request->input('modelNo').' Added');
            $request->flash();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('modelNo').' Not Saved');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\ProductTaxController@index');
    }

    public function edit(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            if(empty($request->status))
            {
                $request->request->add(['active'=>false]);

            }else{
                $request->request->add(['active'=>true]);
            }

            Tax::where('id',$id)->update(['taxName' => $request->input('taxName'),
                'applicableOn'=>$request->input('applicableOn'),
                'rate'=>$request->input('rate'),
                'calculatingMode'=>$request->input('calculatingMode'),
                'description'=>$request->input('description'),
                'status'=>$request->active]);

            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->taxName.' Not updated');
            return redirect()->back();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $e.' '.$request->taxName.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\ProductTaxController@index');
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Tax::find($id)->delete();
            echo json_encode(array("status" => TRUE));

        }catch (HttpException $e)
        {
            DB::rollBack();
            return $e;
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            return $e;
        }

        DB::commit();

    }
}
