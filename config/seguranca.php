<?php

return [
    'prelogin_openroutes' => array(
        'auth.login',
        'index',
        'auth.matriculas-alunos.login',
        'auth.matriculas-alunos.logout',
        'matriculas-alunos.index.alunos',
        'matriculas-alunos.seletivo-matricula.confirmar',
        'matriculas-alunos.seletivo-matricula.comprovante',
        'auth.matriculas-alunos.alunos',
        'alunos.comprovante.verifica',
        'auth.forget-password',
        'auth.reset-password'
    ),

    'postlogin_openroutes' => array(
        'auth.logout',
        'index',
        'alunos.comprovante.verifica',
        'seguranca.profile.profile-picture',
        'seguranca.profile.picture',
        'seguranca.profile.index',
        'seguranca.profile.edit',
        'seguranca.profile.updatepassword',
        'auth.matriculas-alunos.login',
        'auth.matriculas-alunos.logout',
        'matriculas-alunos.index.alunos',
        'auth.matriculas-alunos.alunos',
        'seguranca.profile.updatepassword'
    )
];
