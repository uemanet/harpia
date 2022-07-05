<?php

return [
    'prelogin_openroutes' => array(
        'auth.login',
        'index',
        'alunos.comprovante.verifica',
        'auth.forget-password',
        'auth.reset-password',

        'auth.matriculas-alunos.login',
        'auth.matriculas-alunos.logout',
        'matriculas-alunos.index.alunos',
        'matriculas-alunos.seletivo-matricula.confirmar',
        'matriculas-alunos.seletivo-matricula.comprovante',
        'auth.matriculas-alunos.alunos',

    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'alunos.comprovante.verifica',
        'seguranca.profile.profile-picture',
        'seguranca.profile.picture',
        'seguranca.profile.index',
        'seguranca.profile.updatepassword',

        'seguranca.profile.edit',
        'seguranca.profile.updatepassword',
        'auth.matriculas-alunos.login',
        'auth.matriculas-alunos.logout',
        'matriculas-alunos.index.alunos',
        'auth.matriculas-alunos.alunos',

    )
];
