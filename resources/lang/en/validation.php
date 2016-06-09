<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The must be accepted.',
    'active_url'           => 'The is not a valid URL.',
    'after'                => 'The must be a date after :date.',
    'alpha'                => 'The may only contain letters.',
    'alpha_dash'           => 'The may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The may only contain letters and numbers.',
    'array'                => 'The must be an array.',
    'before'               => 'The must be a date before :date.',
    'between'              => [
        'numeric' => 'The must be between :min and :max.',
        'file'    => 'The must be between :min and :max kilobytes.',
        'string'  => 'The must be between :min and :max characters.',
        'array'   => 'The must have between :min and :max items.',
    ],
    'boolean'              => 'The field must be true or false.',
    'confirmed'            => 'The confirmation does not match.',
    'date'                 => 'The is not a valid date.',
    'date_format'          => 'The does not match the format :format.',
    'different'            => 'The and :other must be different.',
    'digits'               => 'The must be :digits digits.',
    'digits_between'       => 'The must be between :min and :max digits.',
    'distinct'             => 'The field has a duplicate value.',
    'email'                => 'The must be a valid email address.',
    'exists'               => 'The selected is invalid.',
    'filled'               => 'The field is required.',
    'image'                => 'The must be an image.',
    'in'                   => 'The selected is invalid.',
    'in_array'             => 'The field does not exist in :other.',
    'integer'              => 'The must be an integer.',
    'ip'                   => 'The must be a valid IP address.',
    'json'                 => 'The must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The may not be greater than :max.',
        'file'    => 'The may not be greater than :max kilobytes.',
        'string'  => 'The may not be greater than :max characters.',
        'array'   => 'The may not have more than :max items.',
    ],
    'mimes'                => 'The must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The must be at least :min.',
        'file'    => 'The must be at least :min kilobytes.',
        'string'  => 'The must be at least :min characters.',
        'array'   => 'The must have at least :min items.',
    ],
    'not_in'               => 'The selected is invalid.',
    'numeric'              => 'The must be a number.',
    'present'              => 'The field must be present.',
    'regex'                => 'The format is invalid.',
    'required'             => 'The field is required.',
    'required_if'          => 'The field is required when :other is :value.',
    'required_unless'      => 'The field is required unless :other is in :values.',
    'required_with'        => 'The field is required when :values is present.',
    'required_with_all'    => 'The field is required when :values is present.',
    'required_without'     => 'The field is required when :values is not present.',
    'required_without_all' => 'The field is required when none of :values are present.',
    'same'                 => 'The and :other must match.',
    'size'                 => [
        'numeric' => 'The must be :size.',
        'file'    => 'The must be :size kilobytes.',
        'string'  => 'The must be :size characters.',
        'array'   => 'The must contain :size items.',
    ],
    'string'               => 'The must be a string.',
    'timezone'             => 'The must be a valid zone.',
    'unique'               => 'The has already been taken.',
    'url'                  => 'The format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
