<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Requests\EditVoucherRequest;
use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Util\GenUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Validator;
use App\Models\UserPrivilegesModel;

class EditVoucherController extends Controller
{


    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C20')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $trans = null;
        $input = $request->voucherNo;

        $accountList= DB::table('accounts')->where('isGroup',false)
            ->where('compCode',Auth::user()->compCode)
            ->pluck('accName','accNo');

        if(!empty($input))
        {
            $trans = $request->data; //getting data through middleware PostVoucherCheck()

        }

        return view('transaction.editVoucher')->with('trans',$trans)->with('accountList',$accountList);
    }

    public function update(Request $request)
    {

        $rules = [
            'transAmt.*'     => 'required|numeric'
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {

            throw new ValidationException(
                $validate,
                redirect()->back()->withErrors($validate)
            );
        }

        $data = $request->all();


        DB::beginTransaction();

        try{


            $tr_date = Carbon::createFromFormat('d/m/Y', $request->input('transDate')[0]);

            $fpno = GenUtil::get_fp_from_month_sl(Carbon::createFromFormat('d/m/Y', $request->input('transDate')[0])->format('m'));
//            $fpno = Carbon::createFromFormat('d/m/Y', $request->input('transDate')[0])->format('m');
            $period = Carbon::createFromFormat('d/m/Y', $request->input('transDate')[0])->format('Y-m');

            for($i = 0; $i < count($data['id']); $i++)
            {

                AccountModel::where('accNo',$data['oldCrAcc'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->decrement('cr00',$data['oldAmt'][$i]);


                AccountModel::where('accNo',$data['oldCrAcc'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->increment('currBal', $data['oldAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['oldCrAcc'][$i],0,3))
                    ->where('compCode',$request->user()->compCode)->where('isGroup',true)
                    ->decrement('cr00',$data['oldAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['oldCrAcc'][$i],0,3))
                    ->where('compCode',$request->user()->compCode)->where('isGroup',true)
                    ->increment('currBal', $data['oldAmt'][$i]);


                AccountModel::where('accNo',$data['oldDrAcc'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->decrement('dr00',$data['oldAmt'][$i]);


                AccountModel::where('accNo',$data['oldDrAcc'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->decrement('currBal', $data['oldAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['oldDrAcc'][$i],0,3))
                    ->where('isGroup',true)->where('compCode',$request->user()->compCode)
                    ->decrement('dr00',$data['oldAmt'][$i]);


                AccountModel::where('accNo',substr($data['oldDrAcc'][$i],0,3))
                    ->where('isGroup',true)->where('compCode',$request->user()->compCode)
                    ->decrement('currBal', $data['oldAmt'][$i]);


                TransactionModel::find($data['id'][$i])
                    ->update([
                            'transDate'=>$tr_date,
                            'accCr' =>$data['accCr'][$i],
                            'accDr'=> $data['accDr'][$i],
                            'transAmt'=>$data['transAmt'][$i],
                            'fpNo'=>$fpno,
                            'period' =>$period
                    ]);


                AccountModel::where('accNo',$data['accCr'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->increment('cr00',$data['transAmt'][$i]);


                AccountModel::where('accNo',$data['accCr'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->decrement('currBal', $data['transAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['accCr'][$i],0,3))
                    ->where('compCode',$request->user()->compCode)->where('isGroup',true)
                    ->increment('cr00',$data['transAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['accCr'][$i],0,3))
                    ->where('compCode',$request->user()->compCode)->where('isGroup',true)
                    ->decrement('currBal', $data['transAmt'][$i]);


                AccountModel::where('accNo',$data['accDr'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->increment('dr00',$data['transAmt'][$i]);


                AccountModel::where('accNo',$data['accDr'][$i])
                    ->where('compCode',$request->user()->compCode)
                    ->increment('currBal', $data['transAmt'][$i]);


                AccountModel::where('ldgrCode',substr($data['accDr'][$i],0,3))
                    ->where('isGroup',true)->where('compCode',$request->user()->compCode)
                    ->increment('dr00',$data['transAmt'][$i]);


                AccountModel::where('accNo',substr($data['accDr'][$i],0,3))
                    ->where('isGroup',true)->where('compCode',$request->user()->compCode)
                    ->increment('currBal', $data['transAmt'][$i]);

            }

            $request->session()->flash('alert-success', 'Transaction Successfully Updated');
        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', 'Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Transaction\EditVoucherController@index');

    }
}
