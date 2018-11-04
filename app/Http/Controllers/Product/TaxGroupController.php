<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Tax;
use App\Models\Products\TaxGroup;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Form;

class TaxGroupController extends Controller
{
    public function index()
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C21')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $taxes = Tax::where('compCode',Auth::user()->compCode)->pluck('taxName','id');
        $taxes= $taxes->toArray();

        return view('product.taxGroupIndex')->with('userPr',$userPr)->with('taxes',$taxes);
    }

    public function getData(Request $request)
    {
        $taxGroups = TaxGroup::query()->where('compCode',$request->user()->compCode)->with('taxid')
        ->with('tax1id')->with('tax2id');

        return Datatables::of($taxGroups)

            ->addColumn('status', function ($taxGroups) {
                if($taxGroups->status == 1)
                    return '<input type="checkbox" name="status" value="'.$taxGroups->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$taxGroups->compCode.'" disabled="disabled">';
            })

            ->addColumn('action', function ($taxGroups) {

                $taxes = Tax::where('compCode',Auth::user()->compCode)->pluck('taxName','id');
                $taxes= $taxes->toArray();

                return '<button id="editproductsize" data-toggle="modal" data-target="#editModelModal'.$taxGroups->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="tax.group.delete/' . $taxGroups->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editModelModal -->
                    
                    <div class="modal fade" id="editModelModal'.$taxGroups->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">X</button>
                                    <h4 class="modal-title">Edit Models Data</h4>
                                </div>
                                <form class="form-horizontal" role="form" action="tax.group.edit/'.$taxGroups->id.'" method="POST" >
                                <div class="modal-body">
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$taxGroups->id.'"> 
                                    
                                    <div class="form-group">
                                        <label for="size" class="col-md-3 control-label">Name</label>
                                        <div class="col-md-8">
                                            <input id="taxGroupName" type="text" class="form-control" name="taxGroupName" value="'.$taxGroups->taxGroupName.'" required autofocus>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax_id" class="col-md-3 control-label">1. Tax Name</label>
                                        <div class="col-md-8"> '.

                                        Form::select('tax_id',$taxes , $taxGroups->tax_id , array('id' => 'tax_id', 'class' => 'form-control'))

                                . '    </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax1_id" class="col-md-3 control-label">2. Tax Name</label>
                                        <div class="col-md-8"> '.

                                            Form::select('tax1_id',$taxes , $taxGroups->tax1_id , array('id' => 'tax1_id', 'class' => 'form-control','placeholder'=>'Select Tax'))

                                . '    </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <label for="tax2_id" class="col-md-3 control-label">3. Tax Name</label>
                                        <div class="col-md-8"> '.

                                            Form::select('tax2_id',([0=>'Please Select'] + $taxes ) , $taxGroups->tax2_id , array('id' => 'tax2_id', 'class' => 'form-control','placeholder'=>'Select Tax'))

                                . '    </div>
                                    </div>
                                    
                                        
                                    <div class="form-group">
                                        <label for="status" class="col-md-3 control-label">Active ?</label>
                                        <div class="col-md-6"> '.
                                            Form::checkbox('status',$taxGroups->id,$taxGroups->status, array('id'=>'status'))
                                .'
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


    public function addGroup(Request $request)
    {
        $maxCode = TaxGroup::where('compCode',$request->user()->compCode)
            ->max('taxgrp_code');

        if(!empty($maxCode))
        {
            $grpCode = $maxCode + 1;
        }
        else
        {
            $grpCode = '200001';
        }

        if($request->input('tax2_id') == NULL)
        {
            $tax2_id = null;
        }
        else
        {
            $tax2_id = $request->input('tax2_id');
        }

        if($request->input('tax1_id') == NULL)
        {
            $tax1_id = null;
        }
        else
        {
            $tax1_id = $request->input('tax1_id');
        }



        DB::beginTransaction();
//
        try {

            TaxGroup::create([
                'compCode' => $request->user()->compCode,
                'taxgrp_code' => $grpCode,
                'taxGroupName' => $request->input('taxGroupName'),
                'tax_id' => $request->input('tax_id'),
                'tax1_id' => $tax1_id,
                'tax2_id' => $tax2_id,
                'user_id' => $request->user()->id,
                'status' => true
            ]);

            $request->session()->flash('alert-success', $request->input('taxGroupName').' Added');
            $request->flash();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('taxGroupName').' Not Saved');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\TaxGroupController@index');

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

            TaxGroup::where('id',$id)->update(['taxGroupName' => $request->input('taxGroupName'),
                'tax_id'=>$request->input('tax_id'),
                'tax1_id'=>$request->input('tax1_id'),
                'tax2_id'=>$request->input('tax2_id'),
                'status'=>$request->active]);

            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->taxGroupName.' Not updated');
            return redirect()->back();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $e.' '.$request->taxGroupName.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\TaxGroupController@index');
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            TaxGroup::find($id)->delete();
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
