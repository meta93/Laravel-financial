<?php

namespace App\Http\Controllers\Report\Ledger;

use App\Models\AccountModel;
use App\Models\FiscalPeriodModel;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R03')->first();

        if($userPr->view ==('0'))
        {
            session()->flash('alert-danger ','You do not have permission.Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $data = AccountModel::where('compCode',$request->user()->compCode)
            ->orderBy('ldgrCode')->get();

        $footer = AccountModel::where('compCode',$request->user()->compCode)
            ->where('isGroup',true)
            ->select(DB::raw('sum(startDr) as opnDr,sum(startCr) as opnCr, 
                sum(dr00) as dr00, sum(cr00) as cr00, sum(currBal) as currBal')
            )->orderBy('ldgrCode')->first();


        return view('report.ledger.trialBalanceAccount')->with('data',$data)->with('footer',$footer);
    }

    public function groupTB(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R04')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $data = AccountModel::where('compCode',$request->user()->compCode)
            ->where('isGroup',true)
            ->orderBy('ldgrCode')->get();

        $footer = AccountModel::where('compCode',$request->user()->compCode)
            ->where('isGroup',true)
            ->select(DB::raw('sum(startDr) as opnDr,sum(startCr) as opnCr, 
                sum(dr00) as dr00, sum(cr00) as cr00, sum(currBal) as currBal')
            )->orderBy('ldgrCode')->first();

        return view('report.ledger.trialBalanceGroup')->with('data',$data)->with('footer',$footer);
    }

    public function postedTB(Request $request)
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R03')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $trans = null;

        $data = $request->month;

        $ason = FiscalPeriodModel::query()->where('monthSl',$data)->where('status',true)->first();

        if(!empty($data))
        {
            switch($data)
            {
                case 1: //JANUARY

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 as opncr'),'dr07 as prdr', 'cr07 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 2: //FEBRUARY
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 as opncr'),'dr08 as prdr', 'cr08 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 3: //MARCH

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 as opncr'),'dr09 as prdr', 'cr09 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 4: //APRIL

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 as opncr'),'dr10 as prdr', 'cr10 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 5: //MAY

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 + dr10 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 + cr10 as opncr'),'dr11 as prdr', 'cr11 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 6: //JUNE

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 + dr10 + dr11 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 + cr10 + cr11 as opncr'),'dr12 as prdr', 'cr12 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 7: // JULY
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr as opndr'),
                            DB::Raw('startCr as opncr'),'dr01 as prdr', 'cr01 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 8: //AUGUST
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 as opndr'),
                            DB::Raw('startCr + cr01 as opncr'),'dr02 as prdr', 'cr02 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;


                case 9: //SEPTEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 as opncr'),'dr03 as prdr', 'cr03 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 10: //OCTOBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 as opncr'),'dr04 as prdr', 'cr04 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 11: //NOVEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 +cr04 as opncr'),'dr05 as prdr', 'cr05 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 12: //DECEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',false)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 as opncr'),'dr06 as prdr', 'cr06 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;
            }

        }


        switch ($request['submittype'])
        {
            case 'preview':
                return view('report.ledger.trialBalancePosted')->with('trans',$trans)->with('ason',$ason);
                break;

            case 'print':

//                dd($trans);
                $view = \View::make('report.ledger.printtrialbalanceposted',compact('trans','ason'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);



                $pdf::AddPage();
                $pdf::SetMargins(2,5,2);

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('voucher.pdf');

                break;
        }


        return view('report.ledger.trialBalancePosted')->with('trans',$trans);
    }

    public function postedGrpTB(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','R04')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $trans = null;

        $data = $request->month;

        $ason = FiscalPeriodModel::query()->where('monthSl',$data)->where('status',true)->first();


        if(!empty($data))
        {
            switch($data)
            {
                case 1: //JANUARY

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 as opncr'),'dr07 as prdr', 'cr07 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 2: //FEBRUARY
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 as opncr'),'dr08 as prdr', 'cr08 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 3: //MARCH

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 as opncr'),'dr09 as prdr', 'cr09 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 4: //APRIL

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 as opncr'),'dr10 as prdr', 'cr10 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 5: //MAY

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 + dr10 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 + cr10 as opncr'),'dr11 as prdr', 'cr11 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 6: //JUNE

                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 + dr10 + dr11 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 +cr07 + cr08 + cr09 + cr10 + cr11 as opncr'),'dr12 as prdr', 'cr12 as prcr')
                        ->orderBy('accNo')
                        ->get();

                    break;

                case 7: // JULY
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr as opndr'),
                            DB::Raw('startCr as opncr'),'dr01 as prdr', 'cr01 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 8: //AUGUST
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 as opndr'),
                            DB::Raw('startCr + cr01 as opncr'),'dr02 as prdr', 'cr02 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;


                case 9: //SEPTEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 as opncr'),'dr03 as prdr', 'cr03 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 10: //OCTOBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 as opncr'),'dr04 as prdr', 'cr04 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 11: //NOVEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 +cr04 as opncr'),'dr05 as prdr', 'cr05 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;

                case 12: //DECEMBER
                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
                        ->where('isGroup',true)
                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 as opndr'),
                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 as opncr'),'dr06 as prdr', 'cr06 as prcr')
                        ->orderBy('accNo')
                        ->get();
                    break;
            }

        }



//        if(!empty($data))
//        {
//            switch($data)
//            {
//                case 1:
//                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
//                        ->where('isGroup',true)
//                        ->select('accNo','accName','accType','startDr as opndr', 'startCr as opncr','dr01 as prdr', 'cr01 as prcr')
//                        ->get();
//                    break;
//                case 2:
//                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
//                        ->where('isGroup',true)
//                        ->select('accNo','accName','accType','startDr as opndr', 'startCr as opncr',DB::raw('dr01 + dr02 as prdr'), 'cr01 as prcr')
//                        ->get();
//                    break;
//
//                case 5:
//                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
//                        ->where('isGroup',true)
//                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 + dr02 + dr03 + dr04 + dr05 + dr06 + dr07 + dr08 + dr09 + dr10 as opndr'),
//                            DB::Raw('startCr + cr01 + cr02 + cr03 + cr04 + cr05 + cr06 + cr07 + cr08 + cr09 + cr10 as opncr'),'dr11 as prdr', 'cr11 as prcr')
//                        ->get();
//                    break;
//
//                case 7:
//                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
//                        ->where('isGroup',true)
//                        ->select('accNo','accName','accType',DB::Raw('startDr as opndr'),
//                            DB::Raw('startCr as opncr'),'dr01 as prdr', 'cr01 as prcr')
//                        ->orderBy('accNo')
//                        ->get();
//                    break;
//
//                case 8:
//                    $trans =  AccountModel::where('compCode',$request->user()->compCode)
//                        ->where('isGroup',true)
//                        ->select('accNo','accName','accType',DB::Raw('startDr + dr01 as opndr'),
//                            DB::Raw('startCr + cr01 as opncr'),'dr02 as prdr', 'cr02 as prcr')
//                        ->orderBy('accNo')
//                        ->get();
//                    break;
//
//
//            }
//
//        }


        switch ($request['submittype'])
        {
            case 'preview':
                return view('report.ledger.trialBalancePostedGroup')->with('trans',$trans)->with('ason',$ason);
                break;

            case 'print':

//                dd($trans);
                $view = \View::make('report.ledger.printtrialbalanceposted',compact('trans','ason'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);



                $pdf::AddPage();
                $pdf::SetMargins(2,5,2);

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('voucher.pdf');

                break;
        }


        return view('report.ledger.trialBalancePostedGroup')->with('trans',$trans);
    }
}
