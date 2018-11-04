<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 9/28/17
 * Time: 11:22 AM
 */
use Money\Money;
use Money\Currency;

if (!function_exists('format_money')) {

    /**
     * Formats a money object to price + value. eg Money A becomes KSH 10000
     *
     * @param $money
     *
     * @param bool $returnMoneyObject
     *
     * @return mixed
     */
    function format_money($money, $returnMoneyObject = false)
    {
        if (!$money instanceof Money) {

            $money = new Money(is_int($money) ? $money : (int)$money, new Currency(config('site.currencies.default', 'KES')));
        }

        return $returnMoneyObject ? $money : (new MoneyFormatter())->format($money);

    }
}