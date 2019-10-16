<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'size', 'time', 'hash','inputs','outputs','tx_index'
    ];
}
