<?php

return [
    'security_enabled' => env('IS_SECURITY_ENABLED', env('IS_SECURITY_ENNABLED', true)),

    'prelogin_openroutes' => array(
        'auth.login',
        'index',
        'alunos.comprovante.verifica',
        'auth.forget-password',
        'auth.reset-password',
        'portal.foto.create',
        'portal.foto.authenticate',
        'portal.foto.edit',
        'portal.foto.store',
        'portal.foto.atual',
    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'alunos.comprovante.verifica',
        'seguranca.profile.profile-picture',
        'seguranca.profile.picture',
        'seguranca.profile.index',
        'seguranca.profile.updatepassword',
        'portal.foto.create',
        'portal.foto.authenticate',
        'portal.foto.edit',
        'portal.foto.store',
        'portal.foto.atual',
    )
];
