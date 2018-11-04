<?php

namespace App\Http\Controllers\Budget;

use App\Models\AccountModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class AnualBudgetController extends Controller
{
    public function index()
    {
        return view('budget.anualBudgetIndex');
    }

    public function getHeadData()
    {
        $accounts = AccountModel
            ::join('acc_types', 'acc_types.typeCode', '=', 'accounts.typeCode')
            ->where('compCode',Auth::user()->compCode)
            ->where('accounts.accType','E')
            ->select('accounts.id','accounts.ldgrCode','accounts.accNo','accounts.accName','accounts.accType','acc_types.description','accounts.cyrBbgtr','accounts.isGroup','accounts.currBal',
                DB::Raw('CASE accounts.accType WHEN "A" THEN "ASSET" 
                                               WHEN "L" THEN "LIABILITY"
                                               WHEN "I" THEN "INCOME"
                                               WHEN "E" THEN "EXPENSE"
                                               ELSE "CAPITAL" END as TypeDesc'))->get();


        return Datatables::of($accounts)
            ->addColumn('action', function ($accounts) {

                return '<button id="editproject" data-toggle="modal" data-target="#editprojectModal'.$accounts->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="user.data.delete/' . $accounts->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <div class="modal fade" id="editprojectModal'.$accounts->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Current Year Budget</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="budget.head.update/'.$accounts->id.'" method="POST" >
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$accounts->id.'">
                                    <input type="hidden" name="isGroup" value="'.$accounts->isGroup.'">
                                        <div class="form-group">
                                            <label for="cyrBbgtr" class="col-md-4 control-label">Enter Budget</label>
                                            <div class="col-md-6">
                                                <input id="cyrBbgtr" type="text" class="form-control" name="cyrBbgtr" value="'.$accounts->cyrBbgtr.'" required autofocus>
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

    public function editBudgetData(Request $request, $id)
    {

        if($request->isGroup == true)
        {
            $request->session()->flash('alert-danger', 'Group Head Selected. Please Select Account Head');
            return redirect()->action('Budget\AnualBudgetController@index');
        }

        DB::beginTransaction();

        try{

            AccountModel::where('id',$id)->update(['cyrBbgtr'=>$request->cyrBbgtr]);
            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Budget\AnualBudgetController@index');
    }

}
