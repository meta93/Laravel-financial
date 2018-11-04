<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 6/18/17
 * Time: 4:40 PM
 */

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function get_currency()
{
    $currency = \App\Models\BasicProperty::where('compCode',Auth::user()->compCode)
        ->value('currency');

    return $currency;
}

function get_company_name()
{
    $compName = \App\Models\CompanyModel::where('compCode',Auth::user()->compCode)->value('compName');
    return $compName;
}

function get_company_address()
{
    $company = \App\Models\CompanyModel::where('compCode',Auth::user()->compCode)->first();

    $address = $company->street." ".$company->city." ".$company->state." ".$company->country;

    return $address;
}

function cleanData($var)
{

    $var = floatval(preg_replace('/[^\d.]/', '', $var));
    return $var;
}

function get_stmt_acc_line_id($accNo)
{
    $id = \App\Models\StmtDataModel::where('compCode',Auth::user()->compCode)
        ->where('ac11', '<=', $accNo)->where('ac12', '>=', $accNo)->value('id');

    if(!empty($id))
    {

        return $id;
    }else{
        $id = \App\Models\StmtDataModel::where('compCode',Auth::user()->compCode)
            ->where('ac21', '<=', $accNo)->where('ac22', '>=', $accNo)->value('id');

        return $id;
    }

}

function has_inventory()
{
    $result = \App\Models\BasicProperty::where('compCode',Auth::user()->compCode)->value('inventory');
    if($result==true)
    {
        return true;
    }
    else{
        return false;
    }
}

function get_fiscal_year()
{
    $fYaer = \App\Models\FiscalPeriodModel::where('compCode',Auth::user()->compCode)
        ->where('status',true)->where('fpNo',1)->value('FiscalYear');

    return $fYaer;
}

function get_trans_numbers($tr_code)
{
    $tr_number = \App\Models\TransCodeModel::where('compCode',Auth::user()->compCode)
        ->where('transCode',$tr_code)->value('lastTransNo');

    \App\Models\TransCodeModel::where('compCode',Auth::user()->compCode)
        ->where('transCode',$tr_code)
        ->increment('lastTransNo',1);

    return $tr_number;
}

function Projects()
{
    $projects = \App\Models\BasicProperty::where('compCode',Auth::user()->compCode)->value('project');
    return $projects;
}