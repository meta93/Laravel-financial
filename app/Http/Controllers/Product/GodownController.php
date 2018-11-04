<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Godown;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class GodownController extends Controller
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

        return view('product.godownIndex')->with('userPr',$userPr);
    }


    public function getData()
    {
        $godowns = Godown::query()->where('compCode',Auth::user()->compCode);


        return Datatables::of($godowns)

            ->addColumn('status', function ($godowns) {
                if($godowns->status == 1)
                    return '<input type="checkbox" name="status" value="'.$godowns->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$godowns->compCode.'" disabled="disabled">';
            })

            ->addColumn('action', function ($godowns) {

                if($godowns->status == 1)
                    $chk = 'checked="checked"';
                else
                    $chk= "";

                return '<button id="editgodown" data-toggle="modal" data-target="#editGodownModal'.$godowns->id.'"  type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
                    <button data-remote="godown.delete/' . $godowns->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="fa fa-remove"></i>Del</button>
                    
                    <!-- editGodownModal -->
                    
                    <div class="modal fade" id="editGodownModal'.$godowns->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit GOdown Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="godown.edit/'.$godowns->id.'" method="POST" >
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$godowns->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Student Name</label>
                                            <div class="col-md-9">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$godowns->godownName.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="address" class="col-md-4 control-label">Address</label>
                                            <div class="col-md-6">
                                                <textarea class="field" name="address" id = "address" cols="30" rows="7">'.$godowns->address.'</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status" class="col-md-4 control-label">Active ?</label>
                                            <div class="col-md-6">
                                                <input name="status" type="checkbox" id="'.$godowns->id.'" '.$chk.'>
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

        try {

            Godown::create([
                'compCode' => Auth::user()->compCode,
                'godownName' => Str::upper($request->input('godownName')),
                'address' => $request->input('address'),
                'description' =>$request->input('description'),
                'status' => true
            ]);

            $request->session()->flash('alert-success', $request->input('godownName').' Added');
            $request->flash();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('godownName').' Not Saved');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\GodownController@index');
    }


}
