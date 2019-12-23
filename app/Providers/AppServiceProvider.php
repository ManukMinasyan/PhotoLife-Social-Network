<?php

namespace App\Providers;

use App\Models\Member;
use App\Observers\MemberObserver;
use Illuminate\Support\ServiceProvider;

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
        Member::observe(MemberObserver::class);
    }
}
