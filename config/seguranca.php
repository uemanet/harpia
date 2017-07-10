<?php

return [
    'prelogin_openroutes' => array(
        'auth.login',
        'index'
    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'seguranca.profile.index',
        'seguranca.profile.edit',
        'seguranca.profile.updatepassword'
    )
];
