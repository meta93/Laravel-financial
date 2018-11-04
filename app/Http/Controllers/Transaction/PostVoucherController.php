<?php

namespace App\Http\Controllers\Transaction;

use App\Models\AccountModel;
use App\Models\StmtDataModel;
use App\Models\TransactionModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use App\Models\UserPrivilegesModel;
use Illuminate\Support\Facades\Auth;

class PostVoucherController extends Controller
{
    public function index(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','C21')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }


        return view('transaction.postVoucherIndex');
    }

    public function getData(Request $request)
    {
        $trans = TransactionModel::where('compCode',$request->user()->compCode)
                ->where('postFlag',false)
            ->select('voucherNo','transDate','postFlag','userCreated', DB::raw('count(*) as rownumb'))
            ->groupBy('voucherNo','transDate','postFlag','userCreated')->get();

        return Datatables::of($trans)
            ->addColumn('status', function ($trans) {
                if($trans->postFlag == true)
                    return '<input type="checkbox" name="status" value="'.$trans->voucherNo.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="status" value="'.$trans->voucherNo.'" disabled="disabled">';
            })

            ->addColumn('action', function ($trans) {

                return '<button id="postvoucher" data-toggle="modal" data-target="postvoucher'.$trans->voucherNo.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>Check</button>
                    <button data-remote="voucher.data.delete/' . $trans->voucherNo . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>
                    ';
            })
            ->addColumn('details_url', function($trans) {
                return url('voucher/details-data/' . $trans->voucherNo);
            })
            ->make(true);
    }

    public function details($voucherNo, Request $request)
    {
        $credit = TransactionModel::where('voucherNo',$voucherNo)
            ->where('compCode',$request->user()->compCode)
            ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accCr','accCr as acc_no', DB::Raw('0.00 as dr_amt'), 'transAmt as cr_amt', 'userCreated')
            ->with('cr_acc')->has('cr_acc');

        $transdetails = TransactionModel::where('voucherNo',$voucherNo)
            ->where('compCode',$request->user()->compCode)
            ->select('transDate', 'jCode', 'voucherNo', 'transDesc1', 'accDr', 'accDr as acc_no', 'transAmt as dr_amt', DB::Raw('0.00 as cr_amt'), 'userCreated')
            ->with('dr_acc')->has('dr_acc')
            ->unionAll($credit)
            ->orderBY('voucherNo')
            ->get();

        return Datatables::of($transdetails)->make(true);

    }

    public function postvoucher($voucherno, Request $request)
    {
        $data = TransactionModel::where('voucherNo',$voucherno)
            ->where('compCode',$request->user()->compCode)
            ->get();



        DB::beginTransaction();

        try{

            foreach($data as $row)
            {

                switch($row->fpNo)
                {

                    case 1:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr01', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr01', $row->transAmt);


                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr01', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr01', $row->transAmt);

                        break;

                    case 2:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr02' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr02' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr02', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr02', $row->transAmt);

                        break;

                    case 3:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr03' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr03' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr03', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr03', $row->transAmt);

                        break;

                    case 4:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr04' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr04' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr04', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr04', $row->transAmt);

                        break;

                    case 5:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr05' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr05' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr05', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr05', $row->transAmt);

                        break;

                    case 6:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr06' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr06' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr06', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr06', $row->transAmt);

                        break;

                    case 7:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr07' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr07' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr07', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr07', $row->transAmt);

                        break;

                    case 8:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr08' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr08' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr08', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr08', $row->transAmt);

                        break;

                    case 9:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr09' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr09' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr09', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr09', $row->transAmt);

                        break;

                    case 10:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr10' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr10' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr10', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr10', $row->transAmt);

                        break;

                    case 11:
                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr11' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr11' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr11', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr11', $row->transAmt);

                        break;

                    case 12:

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accDr)
                            ->increment('dr12' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('accNo',$row->accCr)
                            ->increment('cr12' , $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accDr,0,3))
                            ->where('isGroup',true)
                            ->increment('dr12', $row->transAmt);

                        AccountModel::where('compCode',$request->user()->compCode)
                            ->where('ldgrCode',substr($row->accCr,0,3))
                            ->where('isGroup',true)
                            ->increment('cr12', $row->transAmt);

                        break;

                    default:

                        $request->session()->flash('alert-info', ' No Data Found');

                }

                TransactionModel::where('voucherNo',$voucherno)
                    ->where('compCode',$request->user()->compCode)
                    ->update(['postFlag'=>true, 'postDate'=>Carbon::now(), 'postedBy'=>$request->user()->id]);

                $request->session()->flash('alert-success', $voucherno.' Posted Successfully');

            }
        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error.' '.$voucherno.' Not Posted');
            return redirect()->back();
        }

        DB::commit();


        return redirect()->action('Transaction\PostVoucherController@index');
    }

}
