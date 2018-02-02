<?php

namespace Harpia\Validator\CustomValidator;

use Illuminate\Support\ServiceProvider;

/**
 * Class ValidationServiceProvider
 * @package Harpia\Validator\CustomValidator
 * @codeCoverageIgnore
 */
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
