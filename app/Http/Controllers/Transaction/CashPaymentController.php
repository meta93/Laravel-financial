<?php

namespace App\Http\Controllers\Transaction;

use App\Models\AccountModel;
use App\Models\ProjectModel;
use App\Models\TransactionModel;
use App\Models\TransCodeModel;
use App\Util\GenUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
Use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class CashPaymentController extends Controller
{
    public function index(Request $request)
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C01')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $voucherNo = TransCodeModel::where('compCode',$request->user()->compCode)
            ->where('transCode','CP')->value('lastTransNo');

        $comp_code = $request->user()->compCode;

        TransCodeModel::where('compCode',$comp_code)
            ->where('transCode','CP')->increment('lastTransNo',1);

        $voucherNo += 1;

        $cashHead = GenUtil::get_dropdown_account_list_from_group($comp_code,GenUtil::get_cash_ledger_code($comp_code));

        $groupList = GenUtil::get_account_group_list('101');

        $projList = ProjectModel::where('compCode',$comp_code)->pluck('projName','projCode');

//
        return view('transaction.cashPaymentIndex')
            ->with('voucherNo',$voucherNo)->with('groupList',$groupList)
            ->with('cashHead',$cashHead)->with('projList',$projList);
    }

    public function getDebitHead(Request $request)
    {

        $input = Input::get('option');

        $accList = GenUtil::get_dropdown_account_list_from_group($request->user()->compCode,$input);

        return Response::json($accList);
    }

    public function saveCPTrans(Request $request)
    {

        DB::beginTransaction();

        try{

            $data = $request->all();
            $comp_code = $request->user()->compCode;
            $userCreated = $request->user()->name;

            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate'));
            $fpno = GenUtil::get_fp_from_month_sl(Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('m'));
            $period = Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('Y-M');
            $trans_id = Carbon::now()->format('Ymdhmis');

            for($i=0; $i< count($data['id']); $i++)
            {
                TransactionModel::create([
                    'compCode' => $comp_code,
                    'projCode' => $request->input('projCode')[$i],
                    'jCode' => 'CP',
                    'period' => $period,
                    'fpNo' => $fpno,
                    'transId' => $trans_id,
                    'transGrpId' => $trans_id,
                    'transDate' => $tr_date,
                    'voucherNo' => $request->input('voucherNo'),
                    'accDr' => $request->input('accDr')[$i],
                    'accCr' => $request->input('accCr'),
                    'drAmt' => $request->input('transAmt')[$i],
                    'crAmt' => $request->input('transAmt')[$i],
                    'transAmt' => $request->input('transAmt')[$i],
                    'currCode' => get_currency(),
                    'fiscalYear' => get_fiscal_year(),
                    'transDesc1' => $request->input('transDesc1')[$i],
                    'postFlag' => False,
                    'userCreated' => $userCreated
                ]);


                AccountModel::where('accNo',$request->input('accDr')[$i])
                    ->where('compCode',$comp_code)
                    ->increment('dr00', $request->input('transAmt')[$i]);

                AccountModel::where('accNo',$request->input('accDr')[$i])
                    ->where('compCode',$comp_code)
                    ->increment('currBal', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',$request->input('grpDebit')[$i])
                    ->where('compCode',$comp_code)->where('isGroup',True)
                    ->increment('dr00', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',$request->input('grpDebit')[$i])
                    ->where('compCode',$comp_code)->where('isGroup',True)
                    ->increment('currBal', $request->input('transAmt')[$i]);


                AccountModel::where('accNo',$request->input('accCr'))
                    ->where('compCode',$comp_code)
                    ->increment('cr00', $request->input('transAmt')[$i]);

                AccountModel::where('accNo',$request->input('accCr'))
                    ->where('compCode',$comp_code)
                    ->decrement('currBal', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',GenUtil::get_cash_ledger_code($comp_code))
                    ->where('compCode',$comp_code)->where('isGroup',True)
                    ->increment('cr00', $request->input('transAmt')[$i]);

                AccountModel::where('ldgrCode',GenUtil::get_cash_ledger_code($comp_code))
                    ->where('compCode',$comp_code)->where('isGroup',True)
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




        return redirect()->action('Transaction\CashPaymentController@index');

    }


}
