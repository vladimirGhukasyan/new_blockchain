<?php

namespace App\Observers;

use App\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        event(new \App\Events\NewTransactionEvent($transaction));

    }
}
