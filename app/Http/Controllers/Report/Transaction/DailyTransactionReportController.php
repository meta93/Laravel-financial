<?php


namespace App\Http\Controllers\Report\Transaction;

use App\Models\TransactionModel;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class DailyTransactionReportController extends Controller
{
    public function index(Request $request)
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R01')->first();


          //  $ob = new userPr();
        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

//        dd($request);

        $data = null;

        if(!empty($request->input('transDate')))
        {
            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate'));

            $credit = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accCr','accCr as acc_no', DB::Raw('0.00 as dr_amt'), 'transAmt as cr_amt', 'userCreated')
                ->with('cr_acc')->has('cr_acc');

            $data = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accDr', 'accDr as acc_no', 'transAmt as dr_amt', DB::Raw('0.00 as cr_amt'), 'userCreated')
                ->with('dr_acc')->has('dr_acc')
                ->unionAll($credit)
                ->orderBY('voucherNo')
                ->get();

//            $sumdata = DB::Select('Select jCode, sum()')

            switch ($request['submittype'])
            {
                case 'preview':
                    return view('report.transaction.dailyTransaction')->with('data',$data);
                    break;

                case 'print':
                    $view = \View::make('report.transaction.printdailytransaction',compact('data'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::AddPage('L');

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('transaction.pdf');

                    break;
            }

//            dd(count($data));
        }

        return view('report.transaction.dailyTransaction')->with('data',$data);
    }
}
