@extends('layouts.modulos.default')

@section('title', 'Aproveitamento de Disciplinas')

@section('content')
    <section class="py-2">
        @include('Geral::pessoas.includes.dadospessoais')
    </section>

    <section class="py-2">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
                        <select id="crs_id" name="crs_id" class="form-control">
                            @if($matriculas->count())
                                <option>Selecione o curso</option>
                                @foreach($matriculas as $matricula)
                                    <option value="{{$matricula->crs_id}}" data-trm-id={{$matricula->trm_id}} data-mat-id={{$matricula->mat_id}}>{{$matricula->crs_nome}}</option>
                                @endforeach
                            @else
                                <option value="">Nenhuma matrícula disponível</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ofd_per_id" class="form-label">Período Letivo</label>
                        <select name="ofd_per_id" id="ofd_per_id" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="" class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" id="btnLocalizar"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- Modal Alterar Situacao Matricula  -->
            <div class="modal fade" id="matricula-modal"></div>

        </div>
    </section>

    <div class="py-2" id="tabela-ofertas"></div>
@stop

@section('scripts')
    <script>
        window.PageData = {
            alu_id: {{ $aluno->alu_id }},
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/aproveitamentoestudos/show.js')
@endsection