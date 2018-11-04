<?php

namespace App\Http\Controllers\Report\Transaction;

use App\Models\TransactionModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;
use Elibyy\TCPDF\Facades\TCPDF;

class PrintVoucherController extends Controller
{
    public function index(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $data = null;
        $vouchers = null;
        $tr_date = null;

        if(!empty($request->input('transDate')))
        {
            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate'));

            $vouchers = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->distinct()->with('company')->get(['voucherNo','transDate','jCode','userCreated','compCode']);

//            dd($vouchers);

//            $data = TransactionModel::where('compCode',$request->user()->compCode)
//                ->where('transDate',$tr_date->format('Y-m-d'))
//                ->with('cr_acc')->has('cr_acc')->with('dr_acc')->has('dr_acc')
//                ->get();


            $credit = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accCr','accCr as acc_no', 'transAmt', DB::Raw('0.00 as dr_amt'), 'transAmt as cr_amt', 'userCreated')
                ->with('cr_acc')->has('cr_acc');

            $data = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accDr', 'accDr as acc_no','transAmt', 'transAmt as dr_amt', DB::Raw('0.00 as cr_amt'), 'userCreated')
                ->with('dr_acc')->has('dr_acc')
                ->unionAll($credit)
                ->orderBY('voucherNo')
                ->get();

            $tr_date = $request->input('transDate');


            switch ($request['submittype'])
            {
                case 'preview':
                    return view('report.transaction.voucherDetails')->with('data',$data)->with('vouchers',$vouchers)
                        ->with('tr_date',$tr_date);
                    break;

                case 'print':
                    $view = \View::make('report.transaction.printVoucher',compact('data','vouchers'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('voucher.pdf');

                    break;
            }

        }

        return view('report.transaction.voucherDetails')->with('data',$data)->with('vouchers',$vouchers)
            ->with('tr_date',$tr_date);
    }

    public function pdfview(Request $request)
    {

//        dd($request->tr_date);

//        if($request->has('download')){

            $tr_date = Carbon::createFromFormat('d/m/Y', $request->tr_date);

            $vouchers = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->distinct()->with('company')->get(['voucherNo','transDate','jCode','userCreated','compCode']);

            $credit = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accCr','accCr as acc_no', 'transAmt', DB::Raw('0.00 as dr_amt'), 'transAmt as cr_amt', 'userCreated')
                ->with('cr_acc')->has('cr_acc');

            $data = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('transDate',$tr_date->format('Y-m-d'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accDr', 'accDr as acc_no','transAmt', 'transAmt as dr_amt', DB::Raw('0.00 as cr_amt'), 'userCreated')
                ->with('dr_acc')->has('dr_acc')
                ->unionAll($credit)
                ->orderBY('voucherNo')
                ->get();

//            dd($tr_date);

            $view = \View::make('report.transaction.printVoucher',compact('data','vouchers'));
            $html = $view->render();

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A6', true, 'UTF-8', false);

            $pdf::AddPage();

            $pdf::writeHTML($html, true, false, true, false, '');
            $pdf::Output('voucher.pdf');


//            $pdf = PDF::loadView('report.transaction.printVoucher',compact('data','vouchers'))->setPaper('A4', 'portrait');
//            return $pdf->stream();

//        }
    }
}
