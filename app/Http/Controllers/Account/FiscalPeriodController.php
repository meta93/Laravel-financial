<?php

namespace App\Http\Controllers\Account;

use App\Models\FiscalPeriodModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;

class FiscalPeriodController extends Controller
{
    public function index()
    {
        return view('account.fiscalPeriodIndex');
    }

    public function getFiscalData()
    {
        $fiscal = FiscalPeriodModel::query()->where('status',True)
            ->where('compCode',Auth::user()->compCode);

        return Datatables::of($fiscal)
            ->addColumn('depriciation', function ($fiscal) {

                if($fiscal->depriciation == True)
                    return '<input type="checkbox" name="depriciation" value="'.$fiscal->fpNo.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="depriciation" value="'.$fiscal->fpNo.'" disabled="disabled">';
            })
            ->make(true);
    }
}
