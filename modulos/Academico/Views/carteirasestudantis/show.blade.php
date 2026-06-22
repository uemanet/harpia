@extends('layouts.modulos.default')

@section('title')
    Gerenciamento de Matriculas
@stop

@section('subtitle')
    {{$lista->lst_nome}} - {{$lista->lst_descricao}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline p-0">
            {{-- Removido a classe obsoleta with-border --}}
            <div class="card-header">
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
                    @if($turmas->count())
                        <input type="hidden" name="lst_id" value="{{ $lista->lst_id }}" id="lst_id" >
                        <div class="col-md-6">
                            <select name="trm_id" id="trm_id" class="form-control">
                                <option value="">Selecione uma Turma</option>
                                @foreach($turmas as $key => $value)
                                    <option value="{{ $key }}" {{ old('trm_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btnBuscar">Buscar</button>
                        </div>
                    @else
                        <div class="col-md-12">
                            <p>Não há matrículas nesta lista</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="row py-2" id="tabela"></div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/carteirasestudantis/show.js')
@stop