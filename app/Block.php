<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'size', 'totalBTCSent', 'estimatedBTCSent', 'blockIndex', 'hash', 'mrklRoot', 'time','prevBlockIndex'

    ];
}
