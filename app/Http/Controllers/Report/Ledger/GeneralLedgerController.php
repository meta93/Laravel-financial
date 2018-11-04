<?php

namespace App\Http\Controllers\Report\Ledger;

use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Util\GenUtil;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $comp_code = $request->user()->compCode;

        $transData = null;
        $openingData = null;
        $fromDate = Carbon::now();
        $toDate = Carbon::now();

        $groupList= AccountModel::where('isGroup',true)
            ->where('compCode',$comp_code)
            ->pluck('accName','ldgrCode');

        $defaultSelection = [''=>'Please Select Group'];
        $groupList = $defaultSelection + $groupList->toArray();

        $accdata = AccountModel::where('isGroup',false)
            ->where('compCode',$comp_code)->where('accNo',$request['accNo'])
            ->first();


        if(!empty($request->input('accNo')))
        {
//            $opnBal = AccountModel::Select('DB::Raw()')

            $fromDate = Carbon::createFromFormat('d/m/Y', $request->input('fromDate'));
            $toDate = Carbon::createFromFormat('d/m/Y', $request->input('toDate'));

            $openingData = AccountModel::selectRaw('accNo, accName, GET_GL_DATE_BAL(accNo, compCode, ? ) as opnBal',[$fromDate->format('Y-m-d')])
                ->where('compCode',$comp_code)
                ->where('accNo',$request->input('accNo'))
                ->get();

            $credit = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('accCr',$request->input('accNo'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accCr','accCr as acc_no', DB::Raw('0.00 as dr_amt'), 'transAmt as cr_amt', 'userCreated');

            $transData = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('accDr',$request->input('accNo'))
                ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accDr', 'accDr as acc_no', 'transAmt as dr_amt', DB::Raw('0.00 as cr_amt'), 'userCreated')
                ->unionAll($credit)
                ->orderBY('voucherNo')
                ->get();

        }


        switch ($request['submittype'])
        {
            case 'preview':
                return view('report.ledger.generalLedgerIndex')->with('groupList',$groupList)
                    ->with('transData',$transData)->with('openingData',$openingData)
                    ->with('fromDate',$fromDate->format('d-m-Y'))
                    ->with('toDate',$toDate->format('d-m-Y'));

                break;

            case 'print':

//                dd($trans);
                $view = \View::make('report.ledger.printgeneralledger',compact('groupList','transData','openingData','fromDate','toDate','accdata'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);



                $pdf::AddPage();
//                $pdf::SetMargins(2,5,2);

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('voucher.pdf');

                break;
        }



        return view('report.ledger.generalLedgerIndex')->with('groupList',$groupList)
            ->with('transData',$transData)->with('openingData',$openingData)
            ->with('fromDate',$fromDate->format('d-m-Y'))
            ->with('toDate',$toDate->format('d-m-Y'));
    }

    /**

     * Get Ajax Request and restun Data

     *

     * @return \Illuminate\Http\Response

     */

    public function getAccList(Request $request, $id)

    {

        $accNos = AccountModel::where('compCode',$request->user()->compCode)
            ->where('isGroup',false)->where('ldgrCode',$id)
            ->pluck('accName','accNo');

//        dd($accNos);

        return json_encode($accNos);

    }



}
