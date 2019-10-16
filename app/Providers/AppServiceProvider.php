<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\TransactionObserver;
use App\Transaction;
use App\Block;
use App\Observers\BlockObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe(TransactionObserver::class);
        Block::observe(BlockObserver::class);
    }
}
