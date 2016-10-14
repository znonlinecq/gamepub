<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Validator;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('payment_password', function($attribute, $value, $parameters, $validator) {
          if($value == '123456')
          {
            return true;
          }
          else
          {
            return false;
          }
        });
        
        Validator::extend('captcha', function($attribute, $value, $parameters, $validator) {
        
            $captcha = Session::get('milkcaptcha');
            if($value==$captcha)
            {
                return true;
            }
            else
            {
                return false;
            }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
