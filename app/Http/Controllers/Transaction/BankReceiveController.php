<?php

namespace App\Http\Controllers\Transaction;

use App\Models\AccountModel;
use App\Models\ProjectModel;
use App\Models\TransactionModel;
use App\Models\TransCodeModel;
use App\Util\GenUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
Use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class BankReceiveController extends Controller
{
    public function index(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C04')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $voucherNo = TransCodeModel::where('compCode',$request->user()->compCode)
            ->where('transCode','BP')->value('lastTransNo');

        $comp_code = $request->user()->compCode;

        TransCodeModel::where('compCode',$comp_code)
            ->where('transCode','BP')->increment('lastTransNo',1);

        $voucherNo += 1;

        $bankHead = GenUtil::get_dropdown_account_list_from_group($comp_code,GenUtil::get_bank_ledger_code($comp_code));

        $groupList = GenUtil::get_account_group_list(GenUtil::get_bank_ledger_code($comp_code));

        $projList = ProjectModel::where('compCode',$comp_code)->pluck('projName','projCode');

//
        return view('transaction.bankReceiveIndex')
            ->with('voucherNo',$voucherNo)->with('groupList',$groupList)
            ->with('bankHead',$bankHead)->with('projList',$projList);
    }

    public function getDebitHeadBal(Request $request)
    {
        $input = $request->input('option');
        $bal = AccountModel::where('compCode',$request->user()->compCode)
            ->where('accNo',$input)->value('currBal');

        return Response::json($bal);
    }

    public function getCreditHead(Request $request)
    {
        $input = $request->input('option');
        $accList = GenUtil::get_dropdown_account_list_from_group($request->user()->compCode,$input);

        return Response::json($accList);
    }

    public function saveBRTrans(Request $request)
    {
        DB::beginTransaction();

        try{

            $data = $request->all();
            $comp_code = $request->user()->compCode;
            $userCreated = $request->user()->name;

            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate'));
//            $fpno = Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('m');
            $fpno = GenUtil::get_fp_from_month_sl(Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('m'));
            $period = Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('Y-m');
            $trans_id = Carbon::now()->format('Ymdhmis');

            for($i=0; $i< count($data['id']); $i++)
            {
                TransactionModel::create([
                    'compCode' => $comp_code,
                    'projCode' => $request->input('projCode')[$i],
                    'jCode' => 'BR',
                    'period' => $period,
                    'fpNo' => $fpno,
                    'transId' => $trans_id,
                    'transGrpId' => $trans_id,
                    'transDate' => $tr_date,
                    'voucherNo' => $request->input('voucherNo'),
                    'accCr' => $request->input('accCr')[$i],
                    'accDr' => $request->input('accDr'),
                    'drAmt' => $request->input('transAmt')[$i],
                    'crAmt' => $request->input('transAmt')[$i],
                    'transAmt' => $request->input('transAmt')[$i],
                    'currCode' => get_currency(),
                    'fiscalYear' => get_fiscal_year(),
                    'transDesc1' => $request->input('transDesc1')[$i],
                    'postFlag' => False,
                    'userCreated' => $userCreated
                ]);


                AccountModel::where('accNo',$request->input('accDr'))
                    ->where('compCode',$comp_code)
                    ->increment('dr00', $request->input('transAmt')[$i]);

                AccountModel::where('accNo',$request->input('accDr'))
                    ->where('compCode',$comp_code)
                    ->increment('currBal', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',GenUtil::get_bank_ledger_code($comp_code))
                    ->where('isGroup',true)
                    ->where('compCode',$comp_code)
                    ->increment('currBal', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',GenUtil::get_bank_ledger_code($comp_code))
                    ->where('isGroup',true)
                    ->where('compCode',$comp_code)
                    ->increment('dr00', $request->input('transAmt')[$i]);


                AccountModel::where('accNo',$request->input('accCr')[$i])
                    ->where('compCode',$comp_code)
                    ->increment('cr00', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',$request->input('grpCredit')[$i])
                    ->where('compCode',$comp_code)->where('isGroup',true)
                    ->increment('cr00', $request->input('transAmt')[$i]);


                AccountModel::where('accNo',$request->input('accCr')[$i])
                    ->where('compCode',$comp_code)
                    ->decrement('currBal', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',$request->input('grpCredit')[$i])
                    ->where('compCode',$comp_code)->where('isGroup',true)
                    ->decrement('currBal', $request->input('transAmt')[$i]);



                $request->session()->flash('alert-success', $request->input('voucherNo').' Saved for Approval');
            }


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('voucherNo').' Not Saved');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Transaction\BankReceiveController@index');

    }
}
