<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Validator::extend('unique_email', function ($attribute, $value, $parameters, $validator) {
            $table = $parameters[0];
            $column = $parameters[1];
            $currentValue = $parameters[2] ?? null;
            $count = DB::table($table)->where($column, $value)->where($column, '!=', $currentValue)->count();
            return $count === 0;
        });
        

    }
}
