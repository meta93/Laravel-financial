<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 1/8/17
 * Time: 11:49 AM
 */

namespace App\Util;


use App\Models\AccountModel;
use App\Models\BasicProperty;
use App\Models\CompanyModel;
use App\Models\CountryModel;
use App\Models\FiscalPeriodModel;
use App\Models\TransactionModel;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GenUtil
{
    public static function ddmmyyyy_to_yyyymmdd($date)
    {
        $day = substr($date,0,2);
        $month = substr($date,3,2);
        $year = substr($date,6,4);

        $tr_date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));

        return($tr_date);

    }

    public static function check_privilege($user, $uid, $pid)
    {
        if($pid == 1)
        {
            $uapr = UserPrivilegesModel::where('email',$user)
                ->where('compCode',Auth::user()->compCode)
                ->where('useCaseId',$uid)->where('view',true)
                ->first();
        }

        if($pid == 2)
        {
            $uapr = UserPrivilegesModel::where('email',$user)
                ->where('compCode',Auth::user()->compCode)
                ->where('useCaseId',$uid)->where('add',true)
                ->first();
        }

        if($pid == 3)
        {
            $uapr = UserPrivilegesModel::where('email',$user)
                ->where('compCode',Auth::user()->compCode)
                ->where('useCaseId',$uid)->where('edit',true)
                ->first();;
        }

        if($pid == 4)
        {
            $uapr = UserPrivilegesModel::where('email',$user)
                ->where('compCode',Auth::user()->compCode)
                ->where('useCaseId',$uid)->where('delete',true)
                ->first();
        }

        if(!empty($uapr))
        {
            return True;
        }
        else
        {
            return False;
        }
    }

    static function get_account_balance($acc_no)
    {
        $balance = AccountModel::where('compCode',Auth::user()->compCode)
            ->where('accNo',$acc_no)->value('currBal');

        return $balance;
    }

    static function get_cash_ledger_code($comp_code)
    {
        $cashCode = BasicProperty::where('compCode',$comp_code)->value('cash');
        return $cashCode;
    }

    static function get_bank_ledger_code($comp_code)
    {
        $bankCode = BasicProperty::where('compCode',$comp_code)->value('bank');
        return $bankCode;
    }

    static function get_account_list($comp_code, $ldgr_code)
    {
        $accList= AccountModel::where('isGroup',false)
            ->where('compCode',$comp_code)
            ->whereNotIn('ldgrCode',$ldgr_code)
            ->orderBy('accName','asc')
            ->pluck('accName','accNo');

        return $accList;
    }

    static function get_account_group_list($ldgr_code)
    {
        $groupList= AccountModel::where('isGroup',true)
            ->where('compCode',Auth::user()->compCode)
            ->whereNotIn('ldgrCode',[$ldgr_code])
            ->pluck('accName','ldgrCode');

//        ltrim ($str,'h')

        $defaultSelection = [''=>'Please Select Group'];
        $groupList = $defaultSelection + $groupList->toArray();

        return $groupList;
    }

    static function get_dropdown_account_list_from_group($comp_code, $ldgr_code)
    {
        $accList= AccountModel::where('isGroup',false)
            ->where('compCode',$comp_code)
            ->where('ldgrCode',$ldgr_code)
            ->orderBy('accName')
            ->pluck('accName','accNo');

        $defaultSelection = [''=>'Please Select Account'];
        $accList = $defaultSelection + $accList->toArray();

        return $accList;
    }

    static function hasProjects()
    {
        $projects = BasicProperty::where('compCode',Auth::user()->compCode)->value('project');
        return $projects;
    }

    public static function gl_negative_check($acc_no, $trans_amt)
    {
        $accDetails = DB::table('gl_accounts')
            ->where('comp_code',Session::get('company_code'))
            ->where('accNo',$acc_no)
            ->first();

        $acc_bal = AccountModel::where('comp_code',Auth::user()->compCode)
            ->where('accNo',$acc_no)->value('currBal');

        $acc_bal = 0;

        if(!empty($accDetails))
        {
            $acc_bal = ($accDetails->start_dr + $accDetails->dr_00) - ($accDetails->start_cr + $accDetails->cr_00);
        }

        if(($acc_bal - $trans_amt) < 0)
        {
            return true;
        }
        else
        {
            return False;
        }
    }

    public static function get_currency_list()
    {
        $currency = CountryModel::whereNotIn('currency',['NULL'])
        ->pluck('currency','currencyCodeA');
        return $currency;
    }

    static function get_group_list()
    {
        $groupList= AccountModel::where('isGroup',true)
            ->where('compCode',Auth::user()->compCode)
            ->pluck('accName','ldgrCode');

        $defaultSelection = [''=>'Please Select Group'];
        $groupList = $defaultSelection + $groupList->toArray();

        return $groupList;
    }

    static function get_all_account_list()
    {
        $accountList= AccountModel::where('isGroup',false)
            ->where('compCode',Auth::user()->compCode)
            ->pluck('accName','accNo');

        $defaultSelection = [''=>'Please Select Group'];
        $accountList = $defaultSelection + $accountList->toArray();

        return $accountList;
    }

    static function get_fp_from_month_sl($monthsl)
    {

        $fpno = FiscalPeriodModel::where('monthSl',(int)$monthsl)
            ->where('compCode',Auth::user()->compCode)
            ->value('fpNo');

        return $fpno;
    }

}