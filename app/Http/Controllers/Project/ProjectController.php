<?php

namespace App\Http\Controllers\Project;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon;
use App\Models\UserPrivilegesModel;

class ProjectController extends Controller
{
    public function index()
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','B02')->first();

        if($userPr->view == true)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('project.projectIndex')->with('userPr',$userPr);
    }

    public function getProjectData()
    {
        $projects = ProjectModel::query();

        return Datatables::of($projects)
            ->addColumn('status', function ($projects) {
                if($projects->status == 1)
                    return '<input type="checkbox" name="status" value="'.$projects->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$projects->compCode.'" disabled="disabled">';
            })
            ->addColumn('action', function ($projects) {
                if($projects->status == 1)
                    $chk = 'checked="checked"';
                else
                    $chk= "";

                return '<button id="editproject" data-toggle="modal" data-target="#editprojectModal'.$projects->id.'"  type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="project.data.delete/' . $projects->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <script>
                        $(function() {
                            $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
                            $("#datepicker1").datepicker({ dateFormat: "dd/mm/yy" }).val()
                        });
                    </script>
                    
                    <div class="modal fade" id="editprojectModal'.$projects->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Project Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="project.data.update/'.$projects->id.'" method="POST" >
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$projects->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Name</label>
                                            <div class="col-md-6">
                                                <input id="projName" type="text" class="form-control" name="projName" value="'.$projects->projName.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Description</label>
                                            <div class="col-md-6">
                                                <textarea class="field" name="projDesc" id = "projDesc" cols="30" rows="5">'.$projects->projDesc.'</textarea>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label for="startDate" class="col-md-4 control-label">Start Date</label>
                                            <div class="col-md-6">
                                                <input id="datepicker" class="control-text" data-mg-required="" name="startDate" type="text" value="'.$projects->startDate.'"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="endDate" class="col-md-4 control-label">End Date</label>
                                            <div class="col-md-6">
                                                <input id="datepicker1" class="control-text" data-mg-required="" name="endDate" type="text" value="'.$projects->endDate.'"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status" class="col-md-4 control-label">Active ?</label>
                                            <div class="col-md-6">
                                                <input name="status" type="checkbox" id="'.$projects->compCode.'" '.$chk.'>
                                            </div>
                                        </div>
                        
                                        <button type="submit" class="btn btn-primary" id="update-data">Save Changes</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ';
            })
            ->make(true);
    }

    public function addNewProject(Request $request)
    {

        $comp_code= Auth::user()->compCode;
        $proj_code = ProjectModel::where('compCode',$comp_code)->max('projCode');



//        dd($request->startDate);

        if(empty($proj_code))
        {
            $proj_code = '100001';
        }else{
            $proj_code += 1;
        }


        DB::beginTransaction();

        try{


            $userCreated = \Auth::user()->name;
            $request->request->add(['projCode' => $proj_code,'compCode'=>$comp_code, 'userCreated' =>$userCreated]);

            ProjectModel::create([
                'compCode' => $comp_code,
                'projCode' => $request->projCode,
                'projName' => $request->input('projName'),
                'projDesc' => $request->input('projDesc'),
                'startDate' => Carbon\Carbon::createFromFormat('d/m/Y', $request->input('startDate')),
                'endDate' => Carbon\Carbon::createFromFormat('d/m/Y', $request->input('endDate')),
                'status' => true,
                'projBudget' => $request->input('projBudget'),
                'userCreated' => $userCreated
            ]);

            $request->flash();

            $request->session()->flash('alert-success', $request->projName.' Added');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->projName.' Not Added');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Project\ProjectController@index');
    }

    public function editProjectData(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            $newStatus = $request->status;

            if(!empty($newStatus))
            {
                ProjectModel::where('id',$id)->update(['status'=>true,'projName'=>$request->input('projName'),'projDesc'=>$request->input('projDesc')]);
            }
            else
            {
                ProjectModel::where('id',$id)->update(['status'=>false,'projName'=>$request->input('projName'),'projDesc'=>$request->input('projDesc')]);
            }

            $request->session()->flash('alert-success', $request->projName.' Updated');


        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->projName.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Project\ProjectController@index');
    }

    public function deleteProjectData(Request $request, $id)
    {
        ProjectModel::find($id)->delete();
        $request->session()->flash('alert-success', $request->projName.' Successfully Deleted');
        return;
    }

}
