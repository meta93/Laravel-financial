<?php

namespace App\Http\Controllers\Report\Statements;

use App\Models\AccountModel;
use App\Models\FiscalPeriodModel;
use App\Models\StmtDataModel;
use App\Models\StmtListModel;
use App\Models\TempGLModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Models\UserPrivilegesModel;
use Elibyy\TCPDF\Facades\TCPDF;

class FinancialStatementController extends Controller
{
    public function addStatementIndex()
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','S01')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('report.statement.addStatementIndex');
    }

    public function getStatementList(Request $request)
    {

        $stmts = StmtListModel::where('compCode',Auth::user()->compCode)->get();

        return Datatables::of($stmts)
            ->addColumn('action', function ($stmts) {

                return '<button id="editstatement" data-toggle="modal" data-target="#editstatementModal'.$stmts->id.'"  type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="fn.statement.delete/' . $stmts->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <div class="modal fade" id="editstatementModal'.$stmts->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Statement</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="fn.statement.update/'.$stmts->id.'" method="POST" >
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$stmts->id.'">
                                        <div class="form-group">
                                            <label for="fileDesc" class="col-md-4 control-label">File Name</label>
                                            <div class="col-md-6">
                                                <input id="fileDesc" type="text" class="form-control" name="fileDesc" value="'.$stmts->fileDesc.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="importFile" class="col-md-4 control-label">From File</label>
                                            <div class="col-md-6">
                                                <input id="importFile" type="text" class="form-control" name="importFile" value="'.$stmts->importFile.'" >
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="importLine" class="col-md-4 control-label">From Line</label>
                                            <div class="col-md-6">
                                                <input id="importLine" type="text" class="form-control" name="importLine" value="'.$stmts->importLine.'" >
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="intoLine" class="col-md-4 control-label">Into Line</label>
                                            <div class="col-md-6">
                                                <input id="intoLine" type="text" class="form-control" name="intoLine" value="'.$stmts->intoLine.'" >
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

    public function saveStatementList(Request $request)
    {

        $request->request->add(['compCode'=>$request->user()->compCode, 'user_id'=>$request->user()->id]);

        $data = array_filter($request->all(), 'strlen');

        DB::beginTransaction();

        try{

            StmtListModel::create($data);

        }catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();
        }

        DB::commit();


        $request->session()->flash('alert-success','New Statement Added');
        return redirect()->action('Report\Statements\FinancialStatementController@addStatementIndex');

    }

    public function updateStatementList(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            StmtListModel::where('id',$id)->update(['fileDesc'=>$request->input('fileDesc'),
                    'importFile'=>$request->input('importFile'),
                    'importLine' => $request->input('importLine'),
                    'intoLine' => $request->input('intoLine')]);

        }catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();
        }

        DB::commit();


        $request->session()->flash('alert-success','Statement Data Updated Successfully');
        return redirect()->action('Report\Statements\FinancialStatementController@addStatementIndex');
    }

    public function deleteStatementList($id)
    {
        $data = $id;
    }

    public function createStatementData(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','S02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }


        $fileList = StmtListModel::where('compCode', $request->user()->compCode)
            ->select(DB::raw('CONCAT(fileDesc, ", ", fileNo) AS Statement'), 'fileNo')
            ->orderBy('fileNo', 'asc')
            ->pluck('Statement', 'fileNo');

        $fileNo = null;

        if(!empty($request->input('fileNo')))
        {
            $fileNo = $request->input('fileNo');
            Session::put('STATEMENT_FILE_NO',Input::get('fileNo'));
        }


        $maxLineNo = DB::table('stmt_data')->where('compCode',$request->user()->compCode)
            ->where('fileNo',$request->input('fileNo'))
            ->max('lineNo');

        if(is_null($maxLineNo))
        {
            $maxLineNo = 0;
        }

        $maxLineNo += 5;



        return view('report.statement.createStatementData')->with('fileList',$fileList)
            ->with('fileNo',$fileNo)->with('maxLineNo',$maxLineNo);
    }

    public function getStatementData(Request $request)
    {

        $fileNo = Session::get('STATEMENT_FILE_NO');

        $stmtdata = StmtDataModel::where('compCode',Auth::user()->compCode)
            ->where('fileNo',$fileNo)->orderBy('lineNo')->get();

        return Datatables::of($stmtdata)
            ->addColumn('action', function ($stmtdata) {

                return '<button id="editproject" data-toggle="modal" data-target="#editprojectModal'.$stmtdata->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="user.data.delete/' . $stmtdata->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>

                    <!-- editprojectModal -->
                    
                    <div class="modal fade" id="editprojectModal'.$stmtdata->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Statement Data</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="statement.data.update/'.$stmtdata->id.'" method="POST" >
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$stmtdata->id.'">
                                        <div class="form-group">
                                            <label for="lineNo" class="col-md-4 control-label">Line No</label>
                                            <div class="col-md-6">
                                                <input id="lineNo" type="text" class="form-control" name="lineNo" value="'.$stmtdata->lineNo.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="texts" class="col-md-4 control-label">Text</label>
                                            <div class="col-md-6">
                                                <input id="texts" type="text" class="form-control" name="texts" value="'.$stmtdata->texts.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="ac11" class="col-md-4 control-label">ac11</label>
                                            <div class="col-md-6">
                                                <input id="ac11" type="text" class="form-control" name="ac11" value="'.$stmtdata->ac11.'" autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="ac12" class="col-md-4 control-label">ac12</label>
                                            <div class="col-md-6">
                                                <input id="ac12" type="text" class="form-control" name="ac12" value="'.$stmtdata->ac12.'" autofocus>
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

    public function saveStatementData(Request $request)
    {


        $figurePosition = 0;

        if (((Str::length($request->input('subTotal'))) + (Str::length($request->input('pFormula')))) > 0)
        {
            $figurePosition = 60;
        }

        DB::beginTransaction();

        try{

            $request->request->add([
                'compCode'=>$request->user()->compCode,
                'user_id'=>$request->user()->id,
                'figrPosition'=> $figurePosition]);

            StmtDataModel::create($request->all());

        }catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();
        }

        DB::commit();

        $request->session()->flash('alert-success','New Line Added');

//        return view('report.statement.createStatementData')->with('fileList',$fileList)
//            ->with('fileNo',$fileNo)->with('maxLineNo',$maxLineNo);

        return redirect()->action('Report\Statements\FinancialStatementController@createStatementData',['fileNo' => Session::get('STATEMENT_FILE_NO')]);

    }

    public function updateStatementData(Request $request,$id)
    {
        DB::beginTransaction();

        try{

            StmtDataModel::where('id',$id)->update(['ac11'=> $request['ac11'],
                'texts'=>$request->input('texts'),
                'ac12' => $request->input('ac12'),
                'lineNo' => $request->input('lineNo')]);

        }catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            $error = $e->getMessage();
            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();

        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back()->withInput();
        }

        DB::commit();


        $request->session()->flash('alert-success','Statement Data Updated Successfully');
        return redirect()->action('Report\Statements\FinancialStatementController@createStatementData');
    }

    public function processStatementIndex()
    {
        return view('report.statement.processStatementIndex');
    }

    public function processStatementData(Request $request)
    {
        $data = AccountModel::where('compCode',Auth::user()->compCode)->get();

        DB::beginTransaction();

        try {

            StmtDataModel::where('compCode', Auth::user()->compCode)
                ->update(['fp00' => 0, 'fp01' => 0,
                    'fp02' => 0, 'fp03' => 0,
                    'fp04' => 0, 'fp05' => 0,
                    'fp06' => 0, 'fp07' => 0,
                    'fp08' => 0, 'fp09' => 0,
                    'fp10' => 0, 'fp11' => 0,
                    'fp12' => 0]);

            foreach ($data as $row) {
                if (get_stmt_acc_line_id($row->accNo) > 0) {

                    StmtDataModel::find(get_stmt_acc_line_id($row->accNo))
                        ->update([
                            'fp00' => DB::raw("fp00 + '" . $row->startDr . "' - '" . $row->startCr . "'"),
                            'fp01' => DB::raw("fp01 + '" . $row->dr01 . "' - '" . $row->cr01 . "'"),
                            'fp02' => DB::raw("fp02 + '" . $row->dr02 . "' - '" . $row->cr02 . "'"),
                            'fp03' => DB::raw("fp03 + '" . $row->dr03 . "' - '" . $row->cr03 . "'"),
                            'fp04' => DB::raw("fp04 + '" . $row->dr04 . "' - '" . $row->cr04 . "'"),
                            'fp05' => DB::raw("fp05 + '" . $row->dr05 . "' - '" . $row->cr05 . "'"),
                            'fp06' => DB::raw("fp06 + '" . $row->dr06 . "' - '" . $row->cr06 . "'"),
                            'fp07' => DB::raw("fp07 + '" . $row->dr07 . "' - '" . $row->cr07 . "'"),
                            'fp08' => DB::raw("fp08 + '" . $row->dr08 . "' - '" . $row->cr08 . "'"),
                            'fp09' => DB::raw("fp09 + '" . $row->dr09 . "' - '" . $row->cr09 . "'"),
                            'fp10' => DB::raw("fp10 + '" . $row->dr10 . "' - '" . $row->cr10 . "'"),
                            'fp11' => DB::raw("fp11 + '" . $row->dr11 . "' - '" . $row->cr11 . "'"),
                            'fp12' => DB::raw("fp12 + '" . $row->dr12 . "' - '" . $row->cr12 . "'")
                        ]);
                }
            }


        }catch(\Exception $e)
        {
            DB::rollback();

            $error = $e->getMessage();

            $request->session()->flash('alert-danger',$error);
            return Redirect::back();
        }

        DB::commit();

        $request->session()->flash('alert-success','Data Processed for Financial Statements Successfully');
        return redirect()->back();
    }

    public function printStatementIndex(Request $request)
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','S03')->first();


            //dd($userPr);
        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        $fileList = StmtListModel::where('compCode', $request->user()->compCode)
            ->select(DB::raw('CONCAT(fileDesc, ", ", fileNo) AS Statement'), 'fileNo')
            ->orderBy('fileNo', 'asc')
            ->pluck('Statement', 'fileNo');


        $trans = null;
        $data = $request->month;


//        $dRows = TempGLModel::all();


        $deletedRows = TempGLModel::where('user_id',$request->user()->id)->delete();



        if(!empty($data))
        {
//            SUM DATA TILL REQUESTED MONTH

            switch($data)
            {
                case 1:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01")]);

//dd('here');
                    break;

                case 2:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02")]);

                    break;

                case 3:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03")]);

                    break;

                case 4:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04")]);

                    break;

                case 5:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05")]);

                    break;

                case 6:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06")]);

                    break;

                case 7:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07")]);

                    break;

                case 8:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07 + fp08")]);

                    break;

                case 9:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07 + fp08 + fp09")]);

                    break;

                case 10:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07 + fp08 + fp09 + fp10")]);

                    break;

                case 11:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07 + fp08 + fp09 + fp10 + fp11")]);

                    break;

                case 12:

                    StmtDataModel::where('compCode', $request->user()->compCode)
                        ->where('fileNo',$request->input('fileNo'))
                        ->where(DB::Raw('length(ac11)'),'>',5)
                        ->update(['rangeVal3' => DB::raw("fp00 + fp01 + fp02 + fp03 + fp04 + fp05 + fp06 + fp07 + fp08 + fp09 + fp10 + fp11 + fp12")]);

                    break;



            }

//
//            Getting Each Subtotal

            $subTotal = StmtDataModel::Select('subTotal', DB::raw("SUM(rangeVal3) as amount"))
                ->where('subTotal','!=',"")
                ->where('compCode', $request->user()->compCode)
                ->where('fileNo',$request->input('fileNo'))
                ->groupBy("subTotal")
                ->get();

            foreach ($subTotal as $row)
            {
                StmtDataModel::where('compCode', $request->user()->compCode)
                    ->where('fileNo',$request->input('fileNo'))
                    ->where('pFormula',$row->subTotal)
                    ->update(['rangeVal3' => $row->amount]);
            }

//            $Formula = DB::select('select pFormula
//                  from stmt_data
//                  where compCode = ?
//                  and LENGTH(pFormula)> 1
//                  and fileNo = ?',[session::get('company_code'),Input::get('fileNo')]);

            $formulaData = StmtDataModel::where('compCode', $request->user()->compCode)
                ->where('fileNo',$request->input('fileNo'))
                ->where(DB::Raw('LENGTH(pFormula)'),'>',1)->get();


            $Amt =0;

            for($i=0; $i<count($formulaData); $i++)
            {
                $Str = str_replace("+","", $formulaData[$i]->pFormula);

                if(Str::length($Str) > 1)
                {
                    $Amt= 0;
                    for($j=0; $j<Str::length($Str); $j++)
                    {
                        $lineAmt = DB::select('select pformula, rangeVal3 from stmt_data
                                    where pformula = ? and fileNo = ? and compCode = ?',
                                    [substr($Str,$j,1),$request->input('fileNo'),$request->user()->compCode]);

                        $Amt += $lineAmt[0]->rangeVal3;
                    }
                }

                StmtDataModel::where('pFormula',$formulaData[$i]->pFormula)
                    ->where('compCode', $request->user()->compCode)
                    ->where('fileNo',$request->input('fileNo'))
                    ->update(['rangeVal3'=> $Amt]);
            }


//            die();

//            $singleFormulaVal = DB::select('select fileNo, subTotal, sum(rangeVal3) value
//                  from stmt_data
//                  where (length(ac11) = 8 OR (fixedValue = true))
//                  and compCode = ?
//                  and fileNo = ?
//                  group by fileNo, subTotal',[session::get('company_code'),Input::get('fileNo')]);


//            $lineNo = DB::select('select fileNo, importFile,importLine, intoLine, importValue from stmt_lists where compCode = ? and fileNo = ?',[session::get('company_code'),Input::get('fileNo')]);


        }



        if(!empty($request->input('fileNo')))
        {

            StmtDataModel::where('fileNo',$request->input('fileNo'))
                ->where('compCode',$request->user()->compCode)
                ->update(['prntVal' => DB::Raw('rangeVal3')]);

//            DB::Update('update ')

            $data = StmtDataModel::where('fileNo',$request->input('fileNo'))
                    ->where('compCode',$request->user()->compCode)->orderBy('lineNo','ASC')->get();


//dd('here');

//            GET AMOUNT FROM OTHET FILE

            $intoLine = StmtListModel::where('compCode', $request->user()->compCode)
                ->where('importFile',$request->input('fileNo'))
                ->first();
//
            if(!empty($intoLine))
            {
                $importamt = DB::Table('stmt_data')->where('compCode', $request->user()->compCode)
                    ->where('fileNo',$request['fileNo'])->where('lineNo',$intoLine->importLine)
                    ->first();
//
//                dd($importamt);

                DB::table('stmt_data')->where('lineNo',$intoLine->intoLine)
                    ->where('compCode', $request->user()->compCode)
                    ->where('fileNo',$intoLine->fileNo)
                    ->update(['rangeVal3'=>$importamt->prntVal]);


                DB::table('stmt_lists')->where('compCode', $request->user()->compCode)
                    ->where('importFile',$request->input('fileNo'))
                    ->update(['importValue'=>$importamt->prntVal]);
            }



        }


//        $monthNum = $request->month;
//        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));


//        $ason =

        $ason = FiscalPeriodModel::query()->where('monthSl',$request['month'])->where('status',true)->first();
        $reportst = StmtListModel::query()->where('fileNo',$request['fileNo'])->first();

//        dd($ason);

        switch ($request['submittype'])
        {
            case 'preview':
                return view('report.statement.printStatement')->with('fileList',$fileList)->with('data',$data);
                break;

            case 'print':

//                dd($trans);
                $view = \View::make('report.statement.pdffinancialstatement',compact('fileList','data','ason','reportst'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);



                $pdf::AddPage();
                $pdf::SetMargins(10,5,10);

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('statement.pdf');

                break;
        }


        return view('report.statement.printStatement')->with('fileList',$fileList)->with('data',$data);
    }
}
