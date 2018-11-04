<?php

namespace App\Http\Controllers\Report\Ledger;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RegisterReportController extends Controller
{
    public function cashRegisterIndex(Request $request)
    {
        $registerData = DB::Select('select accNo, accName, (startDr - startCr) year_open, cr00, dr00, (startDr+dr00) -  (startCr + cr00) balance
                                    from accounts
                                    where accNo  between "1011100" and "1019999" and isGroup = FALSE
                                    and compCode = ?',[$request->user()->compCode]);

        $registerSum = DB::table('accounts')
            ->selectRaw('sum(startDr - startCr) as year_open, sum(cr00) as cr_00, sum(dr00) as dr_00, sum((startDr+dr00) -  (startCr + cr00)) as balance')
            ->where('compCode',$request->user()->compCode)
            ->whereBetween('accNo',['1011100','1019999'])
            ->where('isGroup',FALSE)
            ->first();

        return view('report.ledger.cashRegisterReport')->with('registerData',$registerData)->with('registerSum',$registerSum);

    }

    public function bankRegisterIndex(Request $request)
    {
        $registerData = DB::Select('select accNo, accName, (startDr - startCr) year_open, cr00, dr00, (startDr+dr00) -  (startCr + cr00) balance
                                    from accounts
                                    where accNo  between "1021100" and "1029999" and isGroup = FALSE
                                    and compCode = ?',[$request->user()->compCode]);

        $registerSum = DB::table('accounts')
            ->selectRaw('sum(startDr - startCr) as year_open, sum(cr00) as cr_00, sum(dr00) as dr_00, sum((startDr+dr00) -  (startCr + cr00)) as balance')
            ->where('compCode',$request->user()->compCode)
            ->whereBetween('accNo',['1021100','1029999'])
            ->where('isGroup',FALSE)
            ->first();

        return view('report.ledger.bankRegisterReport')->with('registerData',$registerData)->with('registerSum',$registerSum);
    }


}
