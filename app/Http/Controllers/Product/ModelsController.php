<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Models;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ModelsController extends Controller
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

        return view('product.productModelsIndex')->with('userPr',$userPr);
    }


    public function getData()
    {
        $models = Models::query()->where('compCode',Auth::user()->compCode);


        return Datatables::of($models)

            ->addColumn('status', function ($models) {
                if($models->status == 1)
                    return '<input type="checkbox" name="status" value="'.$models->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$models->compCode.'" disabled="disabled">';
            })

            ->addColumn('action', function ($models) {

                if($models->status == 1)
                    $chk = 'checked="checked"';
                else
                    $chk= "";

                return '<button id="editproductsize" data-toggle="modal" data-target="#editModelModal'.$models->id.'"  type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
                    <button data-remote="product.size.delete/' . $models->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="fa fa-remove"></i>Del</button>
                    
                    <!-- editModelModal -->
                    
                    <div class="modal fade" id="editModelModal'.$models->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">X</button>
                                    <h4 class="modal-title">Edit Models Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="product.model.edit/'.$models->id.'" method="POST" >
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$models->id.'">
                                        <div class="form-group">
                                            <label for="size" class="col-md-3 control-label">size</label>
                                            <div class="col-md-9">
                                                <input id="size" type="text" class="form-control" size="name" value="'.$models->modelNo.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Formal Name</label>
                                            <div class="col-md-9">
                                                <input id="formalName" type="text" class="form-control" name="formalName" value="'.$models->description.'" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status" class="col-md-4 control-label">Active ?</label>
                                            <div class="col-md-6">
                                                <input name="status" type="checkbox" id="'.$models->id.'" '.$chk.'>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary" id="update-data">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
            })
            ->make(true);
    }

    public function add(Request $request)
    {

        DB::beginTransaction();
//
        try {

            Models::create([
                'compCode' => Auth::user()->compCode,
                'modelNo' => $request->input('modelNo'),
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

        return redirect()->action('Product\ModelsController@index');
    }

    public function edit(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            Size::where('id',$id)->update(['size'=>$request->input('size'),'description'=>$request->description,'status'=>$request->status]);
            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\ModelsController@index');
    }


    public function delete($id)
    {

    }
}
