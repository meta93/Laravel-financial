<?php

namespace App\Http\Controllers\Company;

use App\Models\AccountModel;
use App\Models\BasicProperty;
use App\Models\FiscalPeriodModel;
use App\Models\Products\Brand;
use App\Util\GenUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyConfigController extends Controller
{
    public function index()
    {

        if(GenUtil::check_privilege(\Auth::user()->email,'B01',1) == False)
        {
            session()->flash('alert-success', 'You do not have permission');
            return redirect()->back();
            die();
        }

        $comp = CompanyModel::where('compCode',Auth::user()->compCode)->first();
        $basic = BasicProperty::where('compCode',Auth::user()->compCode)->first();

        return view('company.companyConfig')->with('comp',$comp)->with('basic',$basic);
    }
    public function saveConfig(Request $request)
    {
        if (!Auth::check()) {
            return view('auth.login');
        }

        if(GenUtil::check_privilege(\Auth::user()->email,'B01',2) == False)
        {
            session()->flash('alert-success', 'You do not have permission');
            return redirect()->back();
            die();
        }

        $comp_code = Auth::user()->compCode;

        switch ($request['action'])
        {

            case 'NEW': //action for html here

                DB::beginTransaction();

                try{
                    if(!empty($request->project))
                    {
                        BasicProperty::where('compCode',Auth::user()->compCode)
                            ->update(['project' => true]);
                    }
                    else
                    {
                        BasicProperty::where('id',$request->id)
                            ->update(['project' => false]);
                    }

                    if(!empty($request->inventory))
                    {
                        BasicProperty::where('compCode',Auth::user()->compCode)
                            ->update(['inventory' => true]);

                    }
                    else
                    {
                        BasicProperty::where('id',$request->id)
                            ->update(['inventory' => false]);

                    }




                    $start_date = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'));
                    $end_dt = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->endOfMonth();

                    $f_y1 = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->format('Y');
                    $f_y2 = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->addMonth(7)->format('Y');
                    $f_y = $f_y1.'-'.$f_y2;

                    BasicProperty::where('id',$request->id)
                        ->update(['currency' => $request->input('currency'),'posted'=>true, 'fpStart'=>$start_date]);

                    for ($m=1; $m <=12; $m++) {

                        $year = $start_date->format('Y');
                        $fpNo= $m;

                        $month_sl = $start_date->format('m');
                        $month = date('F', mktime(0,0,0,$month_sl, 1, date('Y')));
                        $status = true;


                        FiscalPeriodModel::create([
                            'compCode' => $comp_code,
                            'FiscalYear' => $f_y,
                            'year' =>$year,
                            'fpNo' => $fpNo,
                            'monthSl' => $month_sl,
                            'monthName' => $month,
                            'startDate' => $start_date,
                            'endDate' => $end_dt,
                            'status' => $status,
                            'depriciation' => false
                        ]);

                        $start_date = $start_date->addMonth(1);

                    }

                    FiscalPeriodModel::where('compCode',$comp_code)->where('status',1)
                        ->update(['endDate'=>DB::Raw('LAST_DAY(startDate)')]);


                    $account = new AccountModel;

                    $account->compCode = Auth::user()->compCode;
                    $account->ldgrCode = $request->input('cashAcc');
                    $account->typeCode = '12';
                    $account->accNo = $request->input('cashAcc').'12100';
                    $account->accrNo = $request->input('cashAcc').'12999';
                    $account->accName = '*'.Str::upper('CASH IN HAND');
                    $account->accType = 'A';
                    $account->isGroup = TRUE;
                    $account->userCreated = Auth::user()->name;

                    $account->save();

                    $account = new AccountModel;

                    $account->compCode = Auth::user()->compCode;
                    $account->ldgrCode = $request->input('bankAcc');
                    $account->typeCode = '12';
                    $account->accNo = $request->input('bankAcc').'12100';
                    $account->accrNo = $request->input('bankAcc').'12999';
                    $account->accName = '*'.Str::upper('CASH AT BANK');
                    $account->accType = 'A';
                    $account->isGroup = TRUE;
                    $account->userCreated = Auth::user()->name;

                    $account->save();

                    $account = new AccountModel;

                    $account->compCode = Auth::user()->compCode;
                    $account->ldgrCode = $request->input('salesAcc');
                    $account->typeCode = '31';
                    $account->accNo = $request->input('salesAcc').'12100';
                    $account->accrNo = $request->input('salesAcc').'12999';
                    $account->accName = '*'.Str::upper('SALES ACCOUNT');
                    $account->accType = 'I';
                    $account->isGroup = TRUE;
                    $account->userCreated = Auth::user()->name;

                    $account->save();


                    $account = new AccountModel();

                    $account->compCode = Auth::user()->compCode;
                    $account->ldgrCode = $request->input('purchaseAcc');
                    $account->typeCode = '41';
                    $account->accNo = $request->input('purchaseAcc').'12100';
                    $account->accrNo = $request->input('purchaseAcc').'12999';
                    $account->accName = '*'.Str::upper('PURCHASE ACCOUNT');
                    $account->accType = 'E';
                    $account->isGroup = TRUE;
                    $account->userCreated = Auth::user()->name;

                    $account->save();



                    $request->session()->flash('alert-success', 'Company Configuration Updated');

                }catch (HttpException $e)
                {
                    DB::rollBack();
                    $request->session()->flash('alert-success', 'Not updated');
                    return redirect()->back();
                }

                DB::commit();

                break;

            case 'UPDATE': //action for css here

                DB::beginTransaction();

                BasicProperty::where('id',$request->id)
                    ->update(['currency' => $request['currency']]);

                FiscalPeriodModel::where('status',true)->delete();

                $start_date = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'));
                $end_dt = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->endOfMonth();

                $f_y1 = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->format('Y');
                $f_y2 = Carbon::createFromFormat('d/m/Y', $request->input('fpStart'))->addMonth(7)->format('Y');
                $f_y = $f_y1.'-'.$f_y2;

                BasicProperty::where('id',$request->id)
                    ->update(['currency' => $request->input('currency'),'posted'=>true, 'fpStart'=>$start_date]);

                for ($m=1; $m <=12; $m++) {

                    $year = $start_date->format('Y');
                    $fpNo= $m;

                    $month_sl = $start_date->format('m');
                    $month = date('F', mktime(0,0,0,$month_sl, 1, date('Y')));
                    $status = true;


                    FiscalPeriodModel::create([
                        'compCode' => $comp_code,
                        'FiscalYear' => $f_y,
                        'year' =>$year,
                        'fpNo' => $fpNo,
                        'monthSl' => $month_sl,
                        'monthName' => $month,
                        'startDate' => $start_date,
                        'endDate' => $end_dt,
                        'status' => $status,
                        'depriciation' => false
                    ]);

                    $start_date = $start_date->addMonth(1);

                }

                FiscalPeriodModel::where('compCode',$comp_code)->where('status',1)
                    ->update(['endDate'=>DB::Raw('LAST_DAY(startDate)')]);


//                CompanyModel::where('id',1)->update(['compName' => 'Jamia Ghaisul Huda']);

//                CompanyModel::where('id',1)->update(['compName' => 'Jamia Ghaisul Huda',
//                'city'=>'Arabic Residential College, shikaripalaya','state'=>'Bangalore','postCode'=>'560105','country'=>'India','phoneNo'=>'+91 984 501 6443']);

                DB::commit();

        }



        return redirect()->action('HomeController@index');

    }
}
