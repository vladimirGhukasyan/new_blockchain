<?php

namespace App\Observers;

use App\Block;

class BlockObserver
{
    public function created(Block $block)
    {
        event(new \App\Events\NewBlockEvent($block));

    }
}
