<?php

namespace App\Http\Controllers\Account;

use App\Models\AccountModel;
use App\Models\DepreciationModel;
use App\Models\FiscalPeriodModel;
use App\Models\TransactionModel;
use App\Models\TransCodeModel;
use App\Util\GenUtil;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;

class DepreciationController extends Controller
{

    public $comp_code;

    /**
     * DepreciationController constructor.
     * @param $comp_code
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->comp_code = Auth::user()->compCode;

            return $next($request);
        });

//        $this->comp_code = Auth::user()->compCode;
    }


    public function index(Request $request)

    {


        $comp_code = $this->comp_code;

        $fpData =DB::table('fiscal_period')
            ->where('depriciation',false)
            ->where('compCode',$comp_code)
            ->orderBy('fpNo', 'asc')->first();

        $glHead = AccountModel::where('isGroup', false)
            ->where('compCode', $comp_code)
            ->where('typeCode',1)
            ->orderBy('accName', 'asc')
            ->pluck('accName', 'accNo');

        $defaultSelection = ['' => 'Please Select'];
        $glHead = $defaultSelection + $glHead->toArray();

        $contraAcc = AccountModel::where('isGroup', false)
            ->where('compCode', $comp_code)
            ->whereIn('accNo',['40912128','40712132'])
            ->orderBy('accName', 'asc')
            ->pluck('accName', 'accNo');

        $contraAcc = $defaultSelection + $contraAcc->toArray();

//        $test = DB::select('select ?, GET_GL_DATE_BAL(11212110, compCode, ?), GET_GL_DATE_BAL(11212110, compCode, ?) - GET_GL_DATE_BAL(11212110, compCode, ?), GET_GL_DATE_BAL(11212110, compCode, ?) from fixed_asset_sch
//            WHERE compCode = ? and accNo=?',[$fpData->startDate, $fpData->startDate, $fpData->endDate, $fpData->startDate, $fpData->endDate, Session::get('company_code'),'11212110']);
//
//
//        dd($test);

        DB::update('UPDATE depreciation
            SET openBall = GET_GL_DATE_BAL(accNo, compCode, ?), Addition= GET_GL_DATE_BAL(accNo, compCode, ?) - GET_GL_DATE_BAL(accNo, compCode, ?), totalVal=GET_GL_DATE_BAL(accNo, compCode, ?)
            WHERE compCode = ? and postingStatus = FALSE',[$fpData->startDate, $fpData->endDate, $fpData->startDate, $fpData->endDate, $comp_code]);
//
//
        DB::UPDATE('update depreciation set deprAmt = ((openBall*depRate)/100 + (Addition*depRate)/200)/12
                        where compCode = ? and postingStatus = FALSE',[$comp_code]);
//
        DB::UPDATE('update depreciation set finalval = (totalVal - deprAmt)
                        where compCode = ? and postingStatus = FALSE',[$comp_code]);

        $data = DepreciationModel::with('acc_no')
            ->select('depreciation.*',DB::Raw('GET_ACC_NAME(contraAcc, compCode) as contraName'))
            ->where('postingStatus',false)
            ->where('compCode',$comp_code)
            ->orderBy('accNo')
            ->get();


//        $data = DB::Select('select accNo, GET_ACC_NAME(accNo, compCode) accName, openBall, Addition, totalVal,depRate,deprAmt,finalval,
//                    GET_ACC_NAME(contraAcc, compCode) contraName, contraAcc
//                    from depreciation
//                    where postingStatus = FALSE and compCode = ?
//                    Order By contraAcc, accNo',[$comp_code]);

        $dataFooter = DB::Select('select sum(openBall) openBall , sum(Addition) Addition, sum(totalVal) totalVal,
                          sum(depRate) depRate, sum(deprAmt) deprAmt, sum(finalval) finalval
                    from depreciation
                    where compCode = ? and postingStatus = FALSE',[$comp_code]);


//        return view('account.depreciationIndex');

        return view('account.depreciationIndex')->with('glHead',$glHead)
            ->with('data',$data)->with('dataFooter',$dataFooter)
            ->with('contraAcc',$contraAcc)->with('fpData',$fpData);

    }

    public function getDepData()
    {

        $data = DepreciationModel::with('acc_no')
            ->select('depreciation.*',DB::Raw('GET_ACC_NAME(contraAcc, compCode) as contraName'))
            ->where('postingStatus',false)
            ->where('compCode',$this->comp_code)
            ->orderBy('accNo')
            ->get();


        return Datatables::of($data)
            ->addColumn('action', function ($data) {

                return '<button id="editproject" data-toggle="modal" data-target="#editModal'.$data->id.'"  type="button" class="btn btn-xs btn-primary btn-edit pull-left"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="dep.data.delete/' . $data->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <div class="modal fade" id="editModal'.$data->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Depreciation Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="dep.data.update/'.$data->id.'" method="POST" >
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$data->id.'">
                                        <div class="form-group">
                                            <label for="accName" class="col-md-4 control-label">Rate</label>
                                            <div class="col-md-6">
                                                <input id="depRate" type="text" class="form-control" name="depRate" value="'.$data->depRate.'" required autofocus>
                                            </div>
                                        </div>
                                        
                        
                                        <button type="submit" class="btn btn-primary" id="update-data">Save Changes</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ';
            })
            ->make(true);
    }

    public function saveDeprRate(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'accNo' => 'required|unique:depreciation|max:255',
            'rate' => 'required',
            'contraAcc' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $fpData =DB::table('fiscal_period')
            ->where('depriciation',false)
            ->where('compCode',$this->comp_code)
            ->orderBy('fpNo', 'asc')->first();

        DB::beginTransaction();

        try {

            DB::table('depreciation')->insert(
                ['compCode' => $this->comp_code,
                    'endDate' => $fpData->endDate,
                    'opnDate' =>$fpData->startDate,
                    'accNo' => $data['accNo'],
                    'depRate' => $data['rate'],
                    'contraAcc'=>$data['contraAcc'],
                    'fpNo'=>$fpData->fpNo,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'userCreated' => $request->user()->name
                ]
            );

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back()
                ->withInput();
        }

        DB::commit();

        $request->session()->flash('alert-success','Asset Added For Depreciation');
        return redirect()->action('Account\DepreciationController@index');

    }

    public function update(Request $request, $id)
    {
        if(GenUtil::check_privilege(\Auth::user()->email,'B07',3) == False)
        {
            session()->flash('alert-danger', 'You do not have permission');
            return redirect()->back();
            die();
        }

        DB::beginTransaction();

        try{

            DepreciationModel::find($id)->update($request->all());
            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', 'Not updated');
            return redirect()->back();
        }

        DB::commit();

        $request->session()->flash('alert-success','Update Depreciation Rate');
        return redirect()->action('Account\DepreciationController@index');
    }

    public function destroy(Request $request, $id)
    {

        if(GenUtil::check_privilege($request->user()->email,'B07',4) == False)
        {
            session()->flash('alert-danger', 'You do not have permission');
//            Response::json(['message' => 'Danger', 'message_class' => 'alert alert-danger fade in']);
            return 'Failed';
        }

        DepreciationModel::find($id)->delete();
        return;
    }


    public function postDepriciation(Request $request)
    {

        $comp_code = $this->comp_code;

        $data = DepreciationModel::with('acc_no')
            ->select('depreciation.*',DB::Raw('GET_ACC_NAME(contraAcc, compCode) as contraName'))
            ->where('postingStatus',false)
            ->where('compCode',$this->comp_code)
            ->orderBy('accNo')
            ->get();

        $fpData =DB::table('fiscal_period')
            ->where('depriciation',false)
            ->where('compCode',$this->comp_code)
            ->orderBy('fpNo', 'asc')->first();


        $tr_date = date('Y-m-d',strtotime($fpData->endDate));
        $period = date('Y-M', strtotime($fpData->endDate));
        $fpno = date('m', strtotime($fpData->endDate));


        $voucherNo = TransCodeModel::where('compCode',$comp_code)
            ->where('transCode','JV')->value('lastTransNo');

        $trans_id = Carbon::now()->format('Ymdhmis');



        TransCodeModel::where('compCode',$comp_code)
            ->where('transCode','JV')->increment('lastTransNo',2);

//        $voucherNo += 1;

        $v1 = $voucherNo;
        $v2 = $voucherNo + 1;



        $fpData =DB::table('fiscal_period')
            ->where('depriciation',false)
            ->where('compCode',$comp_code)
            ->orderBy('fpNo', 'asc')->first();

        if($fpData->fpNo == 12)
        {
            $nextFp = 1;
            $cDate = DB::table('fiscal_period')->where('compCode',$comp_code)
                ->where('fpNo',1)->first();
        }else{
            $nextFp = $fpData->fpNo + 1;
            $cDate = DB::table('fiscal_period')->where('compCode',$comp_code)
                ->where('fpNo',$fpData->fpNo + 1)->first();
        }

        $totalAmt = 0;

        $deprData = DepreciationModel::select('contraAcc',DB::RAW('SUM(deprAmt) as transAmt'))
            ->where('postingStatus','FALSE')
            ->where('compCode',$comp_code)
            ->groupBy('contraAcc')->get();



        DB::beginTransaction();

        try{
            for($i=0; $i< count($data); $i++)
            {
                if($data[$i]['deprAmt'] > 0)
                {
                    if($data[$i]['contraAcc'] =='40912128')
                    {
                        $vNo = $v1;
                    }
                    else{
                        $vNo = $v2;
                    }

                    DB::table('transactions')
                        ->insert(array(
                            'compCode' => $comp_code,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'period' => Str::upper($period),
                            'jCode' => 'JV',
                            'fpNo' => $fpno,
                            'accDr' => null,
                            'accCr' =>$data[$i]['accNo'],
                            'transId' => $trans_id,
                            'transGrpId' => $trans_id,
                            'userCreated' => \Auth::user()->name,
                            'transDate' => $tr_date,
                            'postFlag'=> false,
                            'transAmt' => $data[$i]['deprAmt'],
                            'transDesc1' => 'Depriciation For The Month '.$fpData->monthName,
                            'voucherNo' => $vNo
                        ));

                    AccountModel::where('accNo',$data[$i]['accNo'])
                        ->where('compCode',$comp_code)
                        ->increment('cr00', $data[$i]['deprAmt']);

                    AccountModel::where('ldgrCode',substr($data[$i]['accNo'],0,3))
                        ->where('compCode',$comp_code)->where('isGroup',true)
                        ->increment('cr00', $data[$i]['deprAmt']);


                    AccountModel::where('accNo',$data[$i]['accNo'])
                        ->where('compCode',$comp_code)
                        ->decrement('currBal', $data[$i]['deprAmt']);

                    AccountModel::where('ldgrCode',substr($data[$i]['accNo'],0,3))
                        ->where('compCode',$comp_code)->where('isGroup',true)
                        ->decrement('currBal', $data[$i]['deprAmt']);

//                    AccountModel::where('accNo',$data[$i]['accNo'])
//                        ->where('compCode',$comp_code)
//                        ->increment('cr_00', $data['deprAmt'][$i]);

                    $totalAmt = $totalAmt + $data[$i]['deprAmt'];
                }


                DepreciationModel::insert(
                    ['compCode' => $comp_code,
                        'fpNo' =>$nextFp,
                        'accNo' => $data[$i]['accNo'],
                        'contraAcc' => $data[$i]['contraAcc'],
                        'depRate' => $data[$i]['depRate'],
                        'endDate' => $cDate->endDate,
                        'opnDate' =>$cDate->startDate,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'userCreated' => \Auth::user()->name
                    ]
                );
            }

            if( count($deprData) > 0)
            {
                for($i=0; $i<count($deprData); $i++)
                {

                    if($deprData[$i]->contraAcc =='40912128')
                    {
                        $vNo = $v1;
                    }
                    else{
                        $vNo = $v2;
                    }

                    DB::table('transactions')
                        ->insert(array(
                            'compCode' => $comp_code,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'period' => Str::upper($period),
                            'fpNo' => $fpno,
                            'jCode' => 'JV',
                            'accCr' => null,
                            'accDr' =>$deprData[$i]->contraAcc,
                            'transId' => $trans_id,
                            'transGrpId' => $trans_id,
                            'userCreated' => Auth::user()->name,
                            'transDate' => $tr_date,
                            'postFlag'=> false,
                            'transAmt' => $deprData[$i]->transAmt,
                            'transDesc1' => 'Depriciation For The Month '.$fpData->monthName,
                            'voucherNo' => $vNo
                        ));


                    AccountModel::where('accNo',$deprData[$i]->contraAcc)
                        ->where('compCode',$comp_code)
                        ->increment('dr00',$deprData[$i]->transAmt);

                    AccountModel::where('accNo',$deprData[$i]->contraAcc)
                        ->where('compCode',$comp_code)
                        ->increment('currBal', $deprData[$i]->transAmt);

                    AccountModel::where('ldgrCode',substr($deprData[$i]->contraAcc,0,3))
                        ->where('compCode',$comp_code)->where('isGroup',True)
                        ->increment('dr00', $deprData[$i]->transAmt);

                    AccountModel::where('ldgrCode',substr($deprData[$i]->contraAcc,0,3))
                        ->where('compCode',$comp_code)->where('isGroup',True)
                        ->increment('currBal', $deprData[$i]->transAmt);

                }

            }

            DepreciationModel::where('compCode',$comp_code)
                ->where('fpNo',$fpData->fpNo)
                ->update(['postingStatus'=>TRUE,'postDate'=>$tr_date]);


            FiscalPeriodModel::where('fpNo',$fpData->fpNo)
                ->where('compCode',$comp_code)
                ->update(['depriciation'=>true]);

            $request->session()->flash('alert-success','Depreciation Data Posted Successfully');

        }catch(ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger', $error);
//            Flash::error($error);
            return Redirect::back();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger', $error);
            return Redirect::back();
//            throw $e;
        }

        DB::commit();

        return redirect()->action('HomeController@index');

    }
}
