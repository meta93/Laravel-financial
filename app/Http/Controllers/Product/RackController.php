<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Godown;
use App\Models\Products\Rack;
use App\Models\UserPrivilegesModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Form;
class RackController extends Controller
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

        $godowns = Godown::where('compCode',Auth::user()->compCode)
            ->pluck('godownName','id');

        return view('product.rackIndex')->with('userPr',$userPr)->with('godowns',$godowns);
    }

    public function getData()
    {
        $racks = Rack::query()->where('compCode',Auth::user()->compCode)->with('godown')->where('compCode',Auth::user()->compCode);


        return Datatables::of($racks)

            ->addColumn('status', function ($racks) {

                return Form::checkbox('status',$racks->id,$racks->status, array('id'=>'status','disabled'));

            })

            ->addColumn('action', function ($racks) {

                $godowns = Godown::where('compCode',Auth::user()->compCode)->pluck('godownName','id');

                return '<button id="editgodown" data-toggle="modal" data-target="#editGodownModal'.$racks->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="rack.delete/' . $racks->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class= "glyphicon glyphicon-remove"></i>Del</button>
                    
                    <!-- editGodownModal -->
                    
                    <div class="modal fade" id="editGodownModal'.$racks->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit GOdown Data</h4>
                                </div>
                                <form class="form-horizontal" role="form" action="rack.edit/'.$racks->id.'" method="POST" >
                                <div class="modal-body">
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$racks->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Rack Name</label>
                                            <div class="col-md-9">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$racks->name.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="godown_id" class="col-md-3 control-label">Godown</label>
                                        <div class="col-md-8"> '.

                                            Form::select('godown_id', $godowns , $racks->godown_id , array('id' => 'godown_id', 'class' => 'form-control','placeholder'=>'Select Tax'))

                                    . '    </div>
                                        </div>
                                    
                                        
                                        <div class="form-group">
                                            <label for="status" class="col-md-3 control-label">Active ?</label>
                                            <div class="col-md-6"> '.
                                                Form::checkbox('status',$racks->id,$racks->status, array('id'=>'status'))
                                    .'
                                            </div>
                                        </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="submit" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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

        try {

            Rack::create([
                'compCode' => Auth::user()->compCode,
                'name' => Str::upper($request->input('name')),
                'godown_id' => $request->input('godown_id'),
                'status' => true
            ]);

            $request->session()->flash('alert-success', $request->input('name').' Added');
            $request->flash();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('name').' Not Saved');
            return redirect()->back();
        }catch (QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $e.' '.$request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\RackController@index');
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

            Rack::where('id',$id)->update(['name'=>$request->input('name'),'godown_id' => $request->input('godown_id'),
                'status' => $request->active]);
            $request->session()->flash('alert-success', 'Successfully Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', $request->name.' Not updated');
            return redirect()->back();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e.' '.$request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\RackController@index');
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Rack::find($id)->delete();
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
