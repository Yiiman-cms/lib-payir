<?php
/**
 * Copyright (c) 2018.
 * Author: Tokapps Tm
 * Programmer: gholamreza beheshtian
 * mobile: 09353466620
 * WebSite:http://tokapps.ir
 *
 *
 */

namespace system\lib\payments\payir;

use system\lib\payments\payir\BankException;

class SendException extends BankException
{
    public static $errors = [
        -1  =>  'ارسال api الزامی می‌باشد.',
        -2  =>  'ارسال amount ( مبلغ تراکنش ) الزامی می باشد.',
        -3  =>  'amount ( مبلغ تراکنش )باید به صورت عددی باشد.',
        -4  =>  'amount نباید کمتر از 1000 باشد.',
        -5  =>  'ارسال redirect الزامی می باشد.',
        -6  =>  'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد.',
        -7  =>  'فروشنده غیر فعال می باشد.',
        -8  =>  'آدرس بازگشتی با آدرس درگاه پرداخت ثبت شده همخوانی ندارد.',
        'failed'    =>  'تراکنش با خطا مواجه شد.'
    ];

    public function __construct($errorId)
    {
        $this->errorId = intval($errorId);

        parent::__construct(@self::$errors[$this->errorId], $this->errorId);
    }
}
