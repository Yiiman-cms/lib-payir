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

class BankException extends \Exception {
    protected $code = -100;
    protected $message = 'خطای بانک';
}
