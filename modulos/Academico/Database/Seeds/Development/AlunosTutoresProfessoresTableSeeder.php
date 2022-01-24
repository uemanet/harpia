<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Models\Professor;
use Modulos\Academico\Models\Tutor;
use Modulos\Geral\Models\TitulacaoInformacao;
use DB;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Usuario;

class AlunosTutoresProfessoresTableSeeder extends Seeder
{
    public function run()
    {
        // cadastra 400 alunos
        for ($i=1;$i<=400;$i++) {
            $aluno = new Aluno();

            $aluno->alu_pes_id = $i;

            $aluno->save();

            $pessoa = DB::table('gra_pessoas')
                ->where('pes_id', '=', $i)
                ->first();

            $user = new Usuario();
            $user->usr_usuario = $pessoa->pes_email;
            $user->usr_senha = bcrypt('123456');
            $user->usr_pes_id = $i;
            $user->usr_ativo = 1;
            $user->save();

            $user = DB::table('seg_usuarios')
                ->where('usr_pes_id', '=', $i)
                ->first();

            $modulo = Modulo::where('mod_slug','alunos')->first();
            $perfil = Perfil::where('prf_mod_id',$modulo->mod_id)->first();
            $perfil->usuarios()->attach($user->usr_id);

        }

        // cadastra 50 professores
        for ($i+=1;$i<=450;$i++) {
            $professor = new Professor();

            $professor->prf_pes_id = $i;

            $professor->save();

            //cadastra 1 titulação para o professor

            $titulacao = new TitulacaoInformacao();

            $titulacao->tin_pes_id = $professor->prf_pes_id;
            $titulacao->tin_tit_id = 2;
            $titulacao->tin_titulo = 'Graduado em Qualquer Curso';
            $titulacao->tin_instituicao = "Universidade Estadual do Maranhão";
            $titulacao->tin_instituicao_sigla = 'UEMA';
            $titulacao->tin_instituicao_sede = 'São Luís';
            $titulacao->tin_anoinicio = 2014;
            $titulacao->tin_anofim = 2016;

            $titulacao->save();
        }

        // cadastra 50 tutores
        for ($i+=1;$i<=500;$i++) {
            $tutor = new Tutor();

            $tutor->tut_pes_id = $i;

            $tutor->save();
        }
    }
}
