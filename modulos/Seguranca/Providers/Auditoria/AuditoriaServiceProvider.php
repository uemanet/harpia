<?php

namespace Modulos\Seguranca\Providers\Auditoria;

use Illuminate\Support\ServiceProvider;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Models\Centro;
use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Departamento;
use Modulos\Academico\Models\Disciplina;
use Modulos\Academico\Models\Grupo;
use Modulos\Academico\Models\HistoricoMatricula;
use Modulos\Academico\Models\LancamentoTcc;
use Modulos\Academico\Models\Livro;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Academico\Models\Modalidade;
use Modulos\Academico\Models\ModuloDisciplina;
use Modulos\Academico\Models\ModuloMatriz;
use Modulos\Academico\Models\NivelCurso;
use Modulos\Academico\Models\OfertaCurso;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Models\PeriodoLetivo;
use Modulos\Academico\Models\Polo;
use Modulos\Academico\Models\Professor;
use Modulos\Academico\Models\Registro;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\Tutor;
use Modulos\Academico\Models\TutorGrupo;
use Modulos\Academico\Models\Vinculo;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Models\Configuracao;
use Modulos\Geral\Models\Documento;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\TipoAnexo;
use Modulos\Geral\Models\TipoDocumento;
use Modulos\Geral\Models\Titulacao;
use Modulos\Geral\Models\TitulacaoInformacao;
use Modulos\Integracao\Models\AmbienteServico;
use Modulos\Integracao\Models\AmbienteTurma;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Integracao\Models\MapeamentoNota;
use Modulos\Integracao\Models\Servico;
use Modulos\Integracao\Models\Sincronizacao;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Usuario;

class AuditoriaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Models do Módulo Acadêmico
        Aluno::observe(AuditoriaObserver::class);
        Centro::observe(AuditoriaObserver::class);
        ConfiguracaoCurso::observe(AuditoriaObserver::class);
        Curso::observe(AuditoriaObserver::class);
        Departamento::observe(AuditoriaObserver::class);
        Disciplina::observe(AuditoriaObserver::class);
        Grupo::observe(AuditoriaObserver::class);
        HistoricoMatricula::observe(AuditoriaObserver::class);
        LancamentoTcc::observe(AuditoriaObserver::class);
        Livro::observe(AuditoriaObserver::class);
        Matricula::observe(AuditoriaObserver::class);
        MatriculaOfertaDisciplina::observe(AuditoriaObserver::class);
        MatrizCurricular::observe(AuditoriaObserver::class);
        Modalidade::observe(AuditoriaObserver::class);
        ModuloDisciplina::observe(AuditoriaObserver::class);
        ModuloMatriz::observe(AuditoriaObserver::class);
        NivelCurso::observe(AuditoriaObserver::class);
        OfertaCurso::observe(AuditoriaObserver::class);
        OfertaDisciplina::observe(AuditoriaObserver::class);
        PeriodoLetivo::observe(AuditoriaObserver::class);
        Polo::observe(AuditoriaObserver::class);
        Professor::observe(AuditoriaObserver::class);
        Registro::observe(AuditoriaObserver::class);
        Turma::observe(AuditoriaObserver::class);
        Tutor::observe(AuditoriaObserver::class);
        TutorGrupo::observe(AuditoriaObserver::class);
        Vinculo::observe(AuditoriaObserver::class);

        // Models do Módulo Geral
        Anexo::observe(AuditoriaObserver::class);
        Configuracao::observe(AuditoriaObserver::class);
        Documento::observe(AuditoriaObserver::class);
        Pessoa::observe(AuditoriaObserver::class);
        TipoAnexo::observe(AuditoriaObserver::class);
        TipoDocumento::observe(AuditoriaObserver::class);
        Titulacao::observe(AuditoriaObserver::class);
        TitulacaoInformacao::observe(AuditoriaObserver::class);

        // Models do Módulo Integração
        AmbienteServico::observe(AuditoriaObserver::class);
        AmbienteTurma::observe(AuditoriaObserver::class);
        AmbienteVirtual::observe(AuditoriaObserver::class);
        MapeamentoNota::observe(AuditoriaObserver::class);
        Servico::observe(AuditoriaObserver::class);
        Sincronizacao::observe(AuditoriaObserver::class);

        // Models do Módulo Segurança
        Perfil::observe(AuditoriaObserver::class);
        Usuario::observe(AuditoriaObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
