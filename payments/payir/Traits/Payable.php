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

namespace system\lib\payments\payir\Traits;

use system\lib\payments\payir\Models\Transaction;
use system\lib\payments\payir\payir;

trait Payable {

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'payable');
    }

    public function pay($amount, $callbackUrl, $factorNumber = null)
    {
        $payment = new payir();
        $payment->amount($amount);
        $payment->callback($callbackUrl);
        $payment->factorNumber($factorNumber);
        $response = $payment->ready();

        $this->transactions()->create([
            'amount'        =>  $amount,
            'transId'       =>  $response->transId,
            'factorNumber'  =>  $factorNumber
        ]);

        return $payment->start();
    }
}
