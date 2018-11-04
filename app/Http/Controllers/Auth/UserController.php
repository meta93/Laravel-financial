<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\UseCasesModel;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Util\GenUtil;

class UserController extends Controller
{
    public function index()
    {

        $userPr = UserPrivilegesModel::query()->where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','A01')->first();

           // dd($userPr);
        if($userPr->view == null)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('auth.userIndex')->with('userPr',$userPr);
    }

    public function getUserData()
    {
        $users = User::query()->where('compCode',Auth::user()->compCode);

        return Datatables::of($users)
            ->addColumn('status', function ($users) {
                if($users->status == 1)
                    return '<input type="checkbox" name="status" value="'.$users->compCode.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$users->compCode.'" disabled="disabled">';
            })
            ->addColumn('action', function ($users) {
                if($users->status == 1)
                    $chk = 'checked="checked"';
                else
                    $chk= "";

                return '<button id="editproject" data-toggle="modal" data-target="#editprojectModal'.$users->id.'"  type="button" class="btn btn-edit btn-xs btn-primary "><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="user.data.delete/' . $users->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <div class="modal fade" id="editprojectModal'.$users->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit User Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="user.data.update/'.$users->id.'" method="POST" >
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$users->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Name</label>
                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$users->name.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Email</label>
                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control" name="email" value="'.$users->email.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Role</label>
                                            <div class="col-md-6">
                                                <select id="role" class="form-control" name="role"><option value="" selected="selected">'.$users->role.'</option><option value="Admin">Admin</option><option value="User">User</option> </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status" class="col-md-4 control-label">Active ?</label>
                                            <div class="col-md-6">
                                                <input name="status" type="checkbox" id="'.$users->compCode.'" '.$chk.'>
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

    public function editUserData(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            $newRole = $request->role;
            if(!empty($newRole))
            {
                User::find($id)->update($request->all());
            }
            else
            {
                User::find($id)->update($request->except('role'));
            }

            $newStatus = $request->status;

            if(!empty($newStatus))
            {
                User::where('id',$id)->update(['status'=>true]);
            }
            else
            {
                User::where('id',$id)->update(['status'=>false]);
            }

            $request->session()->flash('alert-success', $request->name.' Updated');


        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Auth\UserController@index');
    }

    public function deleteUserData(Request $request, $id)
    {
        User::find($id)->delete();
        $request->session()->flash('alert-success', $request->name.' Deleted');
        return;
    }

    public function userPrivilegeIndex(Request $request)
    {

        if (!Auth::check()) {
//            return view('auth.login');
            return redirect()->guest(route('login'));
        }

//        $userLevel = User::where('email',\Auth::user()->email)->first();

        if(\Auth::user()->role != 'Admin')
        {
            $request->session()->flash('alert-success', \Auth::user()->name.' , You Are Not Authorized To Perform This Task');
            return redirect()->back();
            die();
        }

        $emailList= User::OrderBy('name','ASC')->where('compCode',Auth::user()->compCode)
            ->pluck('email','email');

        $defaultSelection = [''=>'Please Select'];
        $emailList = $defaultSelection + $emailList->toArray();


        $userPrivilege = null;
        $userEmail = $request->input('email');

        if(!empty($userEmail))
        {

            $useCases = UseCasesModel::all();

            for($i=0; $i<count($useCases); $i++)
            {
                if(!UserPrivilegesModel::where('email',$userEmail)->where('compCode',config('site.company.comp_code','999999'))
                        ->where('useCaseId',$useCases[$i]->useCaseId)->exists())
                {
                    DB::table('user_privileges')
                        ->insert(array(
                            'compCode' => Auth::user()->compCode,
                            'email' => $userEmail,
                            'menuId' => $useCases[$i]->menuId,
                            'useCaseId' => $useCases[$i]->useCaseId,
                            'view' => False,
                            'add' => False,
                            'edit' => False,
                            'delete' => False
                        ));
                }
            }


            $userPrivilege = UserPrivilegesModel
                ::join('use_cases', 'use_cases.useCaseId', '=', 'user_privileges.useCaseId')
                ->where('user_privileges.email',$userEmail)
                ->select('user_privileges.email','user_privileges.id', 'user_privileges.useCaseId', 'use_cases.useCaseName', 'user_privileges.view',
                        'user_privileges.add','user_privileges.edit','user_privileges.delete')
                ->get();
        }

        return view('auth.userPrivilege')->with('emailList',$emailList)
                ->with('userPrivilege',$userPrivilege)
            ->with('userEmail',$userEmail);

    }

    public function approvePrivilege()
    {
        $dataView = Input::get('view');
        $dataAdd = Input::get('add');
        $dataEdit = Input::get('edit');
        $dataDelete = Input::get('delete');

        DB::beginTransaction();

        try{


            DB::table('user_privileges')->where('email', Input::get('email'))
                ->update(['view' => false,'add' => false, 'edit' => false, 'delete' =>false]);

            for($i = 0; $i < count($dataView); $i++)
            {
                DB::table('user_privileges')->where('email', Input::get('email'))
                    ->where('useCaseId',$dataView[$i])
                    ->update(['view' => true]);
            }

            for($i = 0; $i < count($dataAdd); $i++)
            {
                DB::table('user_privileges')->where('email', Input::get('email'))
                    ->where('useCaseId',$dataAdd[$i])
                    ->update(['add' => true]);
            }
            for($i = 0; $i < count($dataEdit); $i++)
            {
                DB::table('user_privileges')->where('email', Input::get('email'))
                    ->where('useCaseId',$dataEdit[$i])
                    ->update(['edit' => true]);
            }
            for($i = 0; $i < count($dataDelete); $i++)
            {
                DB::table('user_privileges')->where('email', Input::get('email'))
                    ->where('useCaseId',$dataDelete[$i])
                    ->update(['delete' => true]);
            }
        }catch(ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            Flash::error($error);
            return Redirect::back();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            Flash::error($error);
            return Redirect::back();
//            throw $e;
        }

        DB::commit();

        session()->flash('alert-success', Input::get('email').' , Profile has been updated');
        return redirect()->action('Auth\UserController@userPrivilegeIndex');

    }

    public function changePasswordIndex()
    {
        return view('auth.passwords.reset');
    }
}
