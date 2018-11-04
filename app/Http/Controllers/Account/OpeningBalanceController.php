<?php
namespace App\Http\Controllers\Account;
use App\Models\AccountModel;
use App\Models\StmtDataModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OpeningBalanceController extends Controller
{

    public function index(Request $request)
    {

        $glGroup = AccountModel::where('isGroup', false)
            ->where('compCode', Auth::user()->compCode)
            ->where('startDr', 0)->where('startCr', 0)
            ->where('opnPost',false)
            ->orderBy('accNo', 'asc')->paginate(100);

        $checkSum = DB::table('accounts')
            ->select(DB::Raw('sum(opnDr) as opnDr, sum(opnCr) as opnCr'))
            ->where('compCode',Auth::user()->compCode)
            ->get();


        $searchItemlist = $request->input('accHead');

        if(!empty($searchItemlist))
        {

            $glGroup = AccountModel::where('isGroup', false)
                ->where('compCode', Auth::user()->compCode)
                ->where('startDr', 0)->where('startCr', 0)
                ->where('accName','LIKE','%'.$searchItemlist.'%')
                ->orderBy('accNo', 'asc')->paginate(100);

        }

        return view('account.openingBalance')->with('glGroup',$glGroup)->with('checkSum',$checkSum);
    }

    public function saveOpenbalance(Request $request)
    {

        $data = $request->all();

        DB::beginTransaction();

        try{

//            AccountModel::whereIn('ldgrCode',['101','102','501'])->update(['opnPost'=>false]);

                dd($data); die();

    for($i = 0; $i < count($data['id']); $i++)
            {
        AccountModel::where('id',$request->$id[$i])
        ->update(['opnDr'=>$request->opnDr[$i], 'opnCr'=>$request->opnCr[$i]]);

//                AccountModel::update(['opnDr'=>$request->opnDr[$i], 'opnCr'=>$request->opnCr[$i],
//                    'currBal' =>0, 'startCr'=> 0]);

//                AccountModel::where('accNo','40212102')->delete();

                $request->session()->flash('alert-success', 'Opening Balance Saved');
            }

            $request->session()->flash('alert-success', 'Opening Balance Saved');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', ' Failed To Save');
            return redirect()->back();
        }

        DB::commit();


        return redirect()->action('Account\OpeningBalanceController@index');

    }

    public function postOpenbalance(Request $request)
    {


        DB::beginTransaction();

        try{



//            AccountModel::where('isGroup',false)
//                ->where('opnPost',false)
//                ->where('compCode',$request->user()->compCode)
//                ->update(['currBal'=>DB::Raw('((opnDr + currBal) - opnCr)')]);

//                ->update(['startDr'=>DB::Raw('opnDr'), 'startCr'=>DB::Raw('opnCr'), 'currBal'=>DB::Raw('((opnDr + currBal) - opnCr)')]);

            DB::update('update accounts set currBal = 0 where opnPost = 0');
            DB::update('update accounts set startDr = 0, startCr = 0 where opnPost = 0');
            DB::update('update accounts set startDr = (case when opnDr - opnCr >=0 then (opnDr - opnCr) else 0 end) where opnPost = false');
            DB::update('update accounts set startCr = (case when opnDr - opnCr < 0 then abs( opnDr - opnCr) else 0 end) where opnPost = false');
            DB::update('update accounts set currBal = opnDr + currBal - opnCr where opnPost = 0');

            $grpsum = DB::table("accounts")
                ->select('ldgrCode',DB::raw("SUM(currBal) as currBal"),
                                    DB::raw("SUM(startDr) as startDr"),
                                    DB::raw("SUM(startCr) as startCr"))
                ->where('isGroup',false)->where('opnPost',false)
                ->groupBy('ldgrCode')
                ->get();

            foreach ($grpsum as $rowdata)
            {
                AccountModel::where('isGroup',true)
                    ->where('ldgrCode',$rowdata->ldgrCode)
                    ->where('compCode',$request->user()->compCode)
                    ->increment('currBal',$rowdata->currBal);

                AccountModel::where('isGroup',true)
                    ->where('ldgrCode',$rowdata->ldgrCode)
                    ->where('compCode',$request->user()->compCode)
                    ->increment('startDr',$rowdata->startDr);


                AccountModel::where('isGroup',true)
                    ->where('ldgrCode',$rowdata->ldgrCode)
                    ->where('compCode',$request->user()->compCode)
                    ->increment('startCr',$rowdata->startCr);

            }

            AccountModel::where(DB::Raw('opnDr + opnCr'),'>',0)
                ->where('opnPost',false)
                ->where('compCode',$request->user()->compCode)
                ->update(['opnPost'=>true]);

            $data = AccountModel::where('compCode',$request->user()->compCode)
                ->where('opnPost',true)
                ->get();

            StmtDataModel::where('compCode',$request->user()->compCode)
                ->update(['fp00'=>0]);

            foreach ($data as $row)
            {
                if($row->startDr > 0)
                {
                    $stmtIdDr = get_stmt_acc_line_id($row->accNo);

                    if(!empty($stmtIdDr))
                    {
                        StmtDataModel::where('id',$stmtIdDr->id)
                            ->increment('fp00', $row->startDr);
                    }
                }

                if($row->startCr > 0)
                {
                    $stmtIdCr = get_stmt_acc_line_id($row->accNo);

                    if(!empty($stmtIdCr))
                    {
                        StmtDataModel::where('id',$stmtIdCr->id)
                            ->decrement('fp00', $row->startCr);
                    }
                }
            }

            $request->session()->flash('alert-success', 'Opening Balance Posted');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', ' Failed To Save');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Account\OpeningBalanceController@index');
    }
}
