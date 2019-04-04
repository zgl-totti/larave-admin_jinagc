<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // 添加自定义验证规则,允许字母、数字和 - _
        Validator::extend('allow_letter', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && preg_match('/^[A-Za-z0-9\-\_]+$/', $value);
        });
        Validator::replacer('allow_letter', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', '用户名', ':attribute 仅包含字母、数字.');
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
