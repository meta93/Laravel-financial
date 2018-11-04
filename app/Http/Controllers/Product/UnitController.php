<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Unit;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class UnitController extends Controller
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

        return view('product.unitIndex')->with('userPr',$userPr);
    }


    public function getData()
    {
        $units = Unit::query();


        return Datatables::of($units)

            ->addColumn('action', function ($units) {

                return '<button id="editgodown" data-toggle="modal" data-target="#editUnitModal'.$units->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="unit.delete/' . $units->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editGodownModal -->
                    
                    <div class="modal fade" id="editUnitModal'.$units->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Unit Data</h4>
                                </div>
                                <form class="form-horizontal" role="form" action="unit.edit/'.$units->id.'" method="POST" >
                                <div class="modal-body">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$units->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Unit Name</label>
                                            <div class="col-md-8">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$units->name.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Formal Name</label>
                                            <div class="col-md-8">
                                                <input id="formalName" type="text" class="form-control" name="formalName" value="'.$units->formalName.'" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">No of Decimal Places</label>
                                            <div class="col-md-8">
                                                <input id="noOfDecimalplaces" type="text" class="form-control" name="noOfDecimalplaces" value="'.$units->noOfDecimalplaces.'" required>
                                            </div>
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

    public function add(Request $request)
    {

        DB::beginTransaction();
//
        try {

        Unit::create([
            'name' => Str::upper($request->input('name')),
            'formalName' => $request->input('formalName'),
            'noOfDecimalplaces' =>$request->input('noOfDecimalplaces')
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

        return redirect()->action('Product\UnitController@index');
    }

    public function edit(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            Unit::where('id',$id)->update(['name'=>Str::upper($request->input('name')),'formalName'=>$request->formalName,'noOfDecimalplaces'=>$request->noOfDecimalplaces]);
            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\UnitController@index');
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Unit::find($id)->delete();
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

        return;

    }
}
