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
use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index(Request $request)
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C05')->first();


            //dd($userPr);
        if($userPr->view == 1)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $voucherNo = TransCodeModel::where('compCode',$request->user()->compCode)
            ->where('transCode','JV')->value('lastTransNo');

        $comp_code = $request->user()->compCode;

        TransCodeModel::where('compCode',$comp_code)
            ->where('transCode','JV')->increment('lastTransNo',1);

        $voucherNo += 1;


        $groupList = GenUtil::get_account_group_list('999');

        $projList = ProjectModel::where('compCode',$comp_code)->pluck('projName','projCode');

        $projList = $projList->toArray();

        return view('transaction.journalIndex')
            ->with('voucherNo',$voucherNo)->with('groupList',$groupList)
            ->with('projList',$projList);;
    }

    public function saveJVTrans(Request $request)
    {

        DB::beginTransaction();

        try{

            $data = $request->all();
            $comp_code = $request->user()->compCode;
            $userCreated = $request->user()->name;

            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate'));
            $fpno = GenUtil::get_fp_from_month_sl(Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('m'));
//            $fpno = Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('m');
            $period = Carbon::createFromFormat('d/m/Y', $request->input('transDate'))->format('Y-m');
            $trans_id = Carbon::now()->format('Ymdhmis');

            for($i=0; $i<count($data['id']); $i++)
            {
                if($request->input('drAmt')[$i] > 0)
                {

                    TransactionModel::create([
                        'compCode' => $comp_code,
                        'projCode' => $request->input('projCode'),
                        'jCode' => 'JV',
                        'period' => $period,
                        'fpNo' => $fpno,
                        'transId' => $trans_id,
                        'transGrpId' => $trans_id,
                        'transDate' => $tr_date,
                        'voucherNo' => $request->input('voucherNo'),
                        'accDr' => $request->input('accDr')[$i],
                        'accCr' => null,
                        'drAmt' => $request->input('drAmt')[$i],
                        'crAmt' => 0,
                        'transAmt' => $request->input('drAmt')[$i],
                        'currCode' => get_currency(),
                        'fiscalYear' => get_fiscal_year(),
                        'transDesc1' => $request->input('transDesc1'),
                        'postFlag' => False,
                        'userCreated' => $userCreated
                    ]);

                   AccountModel::where('accNo',$request->input('accDr')[$i])
                        ->where('compCode',$request->user()->compCode)
                        ->increment('dr00',$request->input('drAmt')[$i]);

                    AccountModel::where('accNo',$request->input('accDr')[$i])
                        ->where('compCode',$comp_code)
                        ->increment('currBal', $request->input('drAmt')[$i]);

                    AccountModel::where('ldgrCode',$request->input('grpDebit')[$i])
                        ->where('compCode',$comp_code)->where('isGroup',True)
                        ->increment('dr00', $request->input('drAmt')[$i]);

                    AccountModel::where('ldgrCode',$request->input('grpDebit')[$i])
                        ->where('compCode',$comp_code)->where('isGroup',True)
                        ->increment('currBal', $request->input('drAmt')[$i]);


                }

                if($request->input('crAmt')[$i] > 0)
                {
                    TransactionModel::create([
                        'compCode' => $comp_code,
                        'projCode' => $request->input('projCode'),
                        'jCode' => 'JV',
                        'period' => $period,
                        'fpNo' => $fpno,
                        'transId' => $trans_id,
                        'transGrpId' => $trans_id,
                        'transDate' => $tr_date,
                        'voucherNo' => $request->input('voucherNo'),
                        'accCr' => $request->input('accCr')[$i],
                        'accDr' => null,
                        'drAmt' => 0,
                        'crAmt' => $request->input('crAmt')[$i],
                        'transAmt' => $request->input('crAmt')[$i],
                        'currCode' => config('site.money.default_currency', 'BDT'),
                        'fiscalYear' => config('site.transaction.fiscal_year','2100-2101'),
                        'transDesc1' => $request->input('transDesc1'),
                        'postFlag' => False,
                        'userCreated' => $userCreated
                    ]);

                    AccountModel::where('accNo',$request->input('accCr')[$i])
                        ->where('compCode',$comp_code)
                        ->increment('cr00', $request->input('crAmt')[$i]);

                    AccountModel::where('ldgrCode',$request->input('grpCredit')[$i])
                        ->where('compCode',$comp_code)->where('isGroup',true)
                        ->increment('cr00', $request->input('crAmt')[$i]);


                    AccountModel::where('accNo',$request->input('accCr')[$i])
                        ->where('compCode',$comp_code)
                        ->decrement('currBal', $request->input('crAmt')[$i]);

                    AccountModel::where('ldgrCode',$request->input('grpCredit')[$i])
                        ->where('compCode',$comp_code)->where('isGroup',true)
                        ->decrement('currBal', $request->input('crAmt')[$i]);
                }
            }

            $request->session()->flash('alert-success', $request->input('voucherNo').' Saved for Approval');

        }
    catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$request->input('voucherNo').' Not Saved');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Transaction\JournalController@index');
    }
}
