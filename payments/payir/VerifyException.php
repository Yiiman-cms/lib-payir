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

class VerifyException extends BankException
{
    public static $errors = [
        -1  =>  'ارسال api الزامی می باشد.',
        -2  =>  'ارسال transId الزامی می باشد.',
        -3  =>  'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد.',
        -4  =>  'فروشنده غیر فعال می باشد.',
        -5  =>  'تراکنش با خطا مواجه شده است.'
    ];

    public function __construct($errorId)
    {
        $this->errorId = intval($errorId);

        parent::__construct(@self::$errors[$this->errorId], $this->errorId);
    }
}
