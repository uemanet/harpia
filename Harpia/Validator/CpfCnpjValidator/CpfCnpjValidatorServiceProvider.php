<?php

namespace Harpia\Validator\CpfCnpjValidator;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
            return new CpfCnpjValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }
}