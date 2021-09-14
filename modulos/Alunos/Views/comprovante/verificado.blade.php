@extends('layouts.site')




@section('content')

    <div class="row" style="margin-top: 100px">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Dados Pessoais</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Nome Completo: </strong> {{$aluno->pessoa->pes_nome}}</p>
                            <p><strong>Email: </strong> {{$aluno->pessoa->pes_email}}</p>
                            <p><strong>Sexo: </strong> {{($aluno->pessoa->pes_sexo == 'M') ? 'Masculino' : 'Feminino' }}</p>
                            <p><strong>Estado Civil: </strong> {{ucfirst($aluno->pessoa->pes_estado_civil)}}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Data de Nascimento: </strong> {{$aluno->pessoa->pes_nascimento}}</p>
                            <p><strong>Nome da Mãe: </strong> {{$aluno->pessoa->pes_mae}}</p>
                            <p><strong>Nome do Pai: </strong> {{$aluno->pessoa->pes_pai}}</p>
                            <p><strong>Naturalidade: </strong> {{$aluno->pessoa->pes_naturalidade}}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Nacionalidade: </strong> {{$aluno->pessoa->pes_nacionalidade}}</p>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">COMPROVANTE DE MATRÍCULA</h3><br>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-8">
                                        <p>Universidade Estadual do Maranhão - <strong>UEMA</strong></p>
                                        <p>Sistema Acadêmico de Educação à Distância - <b>SAED/UEMA</b></p>
                                        <p class="box-title">Curso: <b>{{mb_strtoupper($data->curso->crs_nome, 'UTF-8')}}</b></p>

                                    </div>
                                    <div class="col-md-4">
                                        <p>Polo:
                                            <b>{{mb_strtoupper($data->matricula->polo->pol_nome, 'UTF-8')}}</b></p>
                                        <p>Período: <b> 2021.2</b></p>
                                    </div>


                                    <div class="box-body no-padding">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <th width="25%">Disciplina</th>
                                                <th width="10%">Tipo</th>
                                                <th width="30%">Módulo</th>
                                                <th width="8%">CH</th>
                                                <th width="27%">Situação</th>
                                            </tr>
                                            @foreach($data->disciplinas as $disciplina)
                                                <tr class="padding border-td">
                                                    <td>{{$disciplina->dis_nome}}</td>

                                                    <td>
                                                        @php
                                                            if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                                                                echo "OBR";
                                                            } elseif ($disciplina->mdc_tipo_disciplina == 'eletiva') {
                                                                echo "ELT";
                                                            } elseif ($disciplina->mdc_tipo_disciplina == 'tcc') {
                                                                echo "TCC";
                                                            } elseif ($disciplina->mdc_tipo_disciplina == 'optativa') {
                                                                echo "OPT";
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>{{$disciplina->mdo_nome}}</td>
                                                    <td>{{$disciplina->dis_carga_horaria}} h</td>
                                                    <td>
                                                        @php
                                                            if ($disciplina->mof_tipo_matricula == 'aproveitamento'){
                                                                echo "APROVEITAMENTO";
                                                            } else if ($disciplina->mof_situacao_matricula == 'aprovado_media') {
                                                                echo "APROVADO POR MÉDIA";
                                                            } elseif ($disciplina->mof_situacao_matricula == 'aprovado_final') {
                                                                echo "APROVADO POR FINAL";
                                                            } elseif ($disciplina->mof_situacao_matricula == 'reprovado_media') {
                                                                echo "REPROVADO POR MÉDIA";
                                                            } elseif ($disciplina->mof_situacao_matricula == 'reprovado_final') {
                                                                echo "REPROVADO POR FINAL";
                                                            } elseif ($disciplina->mof_situacao_matricula == 'cursando') {
                                                                echo "CURSANDO";
                                                            } elseif ($disciplina->mof_situacao_matricula == 'cancelado') {
                                                                echo "CANCELADO";
                                                            }
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-2"></div>

    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.extensions.js') }}"></script>

    <script type="text/javascript">
        Inputmask({"mask": "########-####-####-####-############", "removeMaskOnSubmit": false}).mask('#aln_codigo');
    </script>
@stop