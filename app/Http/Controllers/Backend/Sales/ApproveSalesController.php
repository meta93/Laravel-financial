<?php

namespace App\Http\Controllers\Backend\Sales;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Sales;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Response;

class ApproveSalesController extends Controller
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

        return view('backend.sales.approvesalesinvoice')->with('userPr',$userPr);
    }

    public function getinvoicedata()
    {
        $invoice = Sales::query()->where('compCode',Auth::user()->compCode)->where('status',1)->with('relationship');



        return Datatables::of($invoice)

            ->editColumn('type',function ($invoice) { return $invoice->type == 'L' ? 'Local Sales' : 'Export';})

            ->addColumn('action', function ($invoice) {

                return '<button id="approveinvoice" type="button" class="btn btn-xs btn-detail btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Details</button>
                    
                    ';
            })
            ->make(true);
    }

    public function invoicedetails($invoiceno)
    {
        $invoicedata = TransProduct::query()->where('compCode',Auth::user()->compCode)
            ->where('refno',$invoiceno)
            ->with('item')->get();

        return Response::json($invoicedata);
    }

    public function approve($invoiceno)
    {
        $response = Sales::where('invoiceno',$invoiceno)
            ->where('compCode',Auth::user()->compCode)->update(['status' => 2,'approver'=>Auth::user()->id]);

        return Response::json($response);
    }

    public function reject($invoiceno)
    {
        $response = Sales::where('invoiceno',$invoiceno)
            ->where('compCode',Auth::user()->compCode)->update(['status' => 3,'approver'=>Auth::user()->id]);

        return Response::json($response);
    }
}
