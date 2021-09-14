<?php

return [
    'prelogin_openroutes' => array(
        'auth.login',
        'index',
        'alunos.comprovante.verifica'
    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'alunos.comprovante.verifica',
        'seguranca.profile.index',
        'seguranca.profile.edit',
        'seguranca.profile.updatepassword'
    )
];
