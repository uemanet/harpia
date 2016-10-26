<?php

namespace Harpia\Validator\CustomValidator;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
            return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }
}
