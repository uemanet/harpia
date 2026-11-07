@section('stylesheets')
    <style>
        .title-card {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop
<!--  Dados Pessoais  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me card -->
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Dados do Colaborador</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <p><strong>Foto facial</strong></p>
                        @php($fotoFacialVersion = optional($colaborador->foto_facial)->anx_localizacao ?? 'avatar')
                        <img
                            src="{{ route('rh.colaboradores.foto', ['id' => $colaborador->col_id, 'v' => $fotoFacialVersion], false) }}"
                            alt="Foto facial do colaborador"
                            style="max-width: 180px; max-height: 180px; width: 100%; object-fit: cover; border: 1px solid #d2d6de; border-radius: 8px; padding: 4px;"
                        >
                    </div>
                    <div class="col-md-4">
{{--                        <p><strong>Setor: </strong> {{$colaborador->setor->set_descricao}}</p>--}}
                        <p><strong>Carga Horária: </strong> {{$colaborador->col_ch_diaria}}</p>
                        <p><strong>Código da catraca: </strong> {{$colaborador->col_codigo_catraca}}</p>
                        <p><strong>Status: </strong> {{$colaborador->col_status}}</p>

                    </div>
                    <div class="col-md-4">
                        <p><strong>Funções: </strong>
                            @foreach($colaborador->funcoes as $funcao)
                                @if($loop->last)
                                    {{$funcao->fun_descricao}}.
                                @else
                                    {{$funcao->fun_descricao}},
                                @endif
                            @endforeach
                        </p>
                        <p><strong>Vínculo com a
                                universidade: </strong> {{($colaborador->col_vinculo_universidade == 1) ? 'Sim' : 'Não' }}
                        </p>
                        <p><strong>Matrícula    na universidade: </strong> {{$colaborador->col_matricula_universidade}}</p>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Observação: </strong> {{$colaborador->col_observacao}}</p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>