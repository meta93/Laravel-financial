<?php

namespace App\Http\Controllers\Backend\Requisition;

use App\Models\Products\Product;
use App\Models\Requisition\Requisition;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Form,Response;

class ApproveRequisition extends Controller
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

        return view('backend.requisition.approverequisition')->with('userPr',$userPr);
    }

    public function getreqdata()
    {
//        $requisition = Requisition::query()->where('compCode',Auth::user()->compCode)
//            ->where('status',1)
//            ->with('item')->get();

        $requisition = DB::table('requisitions as a')
            ->select("a.refNo","a.reqType", "a.reqDate", DB::raw("group_concat(p.name order by p.name separator ',') as items"))
            ->join("products as p",function($join){
                $join->on("p.id","=","a.product_id")
                    ->on("p.compCode","=","a.compCode");
            })
            ->where('a.status',1)
            ->groupBy("a.refNo","a.reqType", "a.reqDate")->get();


        return Datatables::of($requisition)

            ->editColumn('reqType',function ($requisition) { return $requisition->reqType == 'P' ? 'Purchase' : 'Consumption';})

            ->addColumn('action', function ($requisition) {



                return '<button id="editrequisition" type="button" class="btn btn-xs btn-detail btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Details</button>
                    
                    ';
            })
            ->make(true);
    }

    public function getdetailsreqdata($refNo)
    {
        $requisition = Requisition::query()->where('compCode',Auth::user()->compCode)
            ->where('status',1)->where('refNo',$refNo)
            ->with('item')->get();

        return Response::json($requisition);
    }


    public function approvereq($refNo)
    {
        $response = Requisition::where('refNo',$refNo)->update(['status' => 2,'approver'=>Auth::user()->id]);

        return Response::json($response);
    }

    public function rejectreq($refNo)
    {
        $response = Requisition::where('refNo',$refNo)->update(['status' => 3,'approver'=>Auth::user()->id]);

        return Response::json($response);
    }
}
