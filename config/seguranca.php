<?php

return [
    'prelogin_openroutes' => array(
        'auth.login',
        'index',
        'alunos.comprovante.verifica',
        'auth.forget-password',
        'auth.reset-password',
    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'alunos.comprovante.verifica',
        'seguranca.profile.profile-picture',
        'seguranca.profile.picture',
        'seguranca.profile.index',
        'seguranca.profile.updatepassword',

    )
];
