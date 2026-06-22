@extends('layouts.modulos.default')

@section('title')
    Horas Trabalhadas
@stop

@section('subtitle')
    Gerenciamento de Horas de Colaboradores
@stop

@section('content')
    <div class="row">
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
                    <form method="GET" class="d-flex row" action="{{{ route('rh.horastrabalhadas.index') }}}">
                        <div class="form-group col-md-2 px-1">
                            <select name="htr_pel_id" id="htr_pel_id" class="form-control">
                                <option value="">Selecione o período laboral</option>
                                @foreach($periodosLaborais as $key => $value)
                                    <option value="{{ $key }}" {{ Request::input('htr_pel_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3 px-1">
                            <select name="cfn_set_id[]" id="cfn_set_id" class="form-control" multiple="true">
                                @foreach($setores as $key => $value)
                                    <option value="{{ $key }}" {{ Request::input('cfn_set_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3 px-1">
                            <select name="col_pes_id[]" id="col_pes_id" class="form-control" multiple="true">
                                @foreach($colaboradores as $key => $value)
                                    <option value="{{ $key }}" {{ Request::input('col_pes_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>

                        <div class="col-md-2 px-1">
                            <button type="button" class="btn btn-success w-100" data-toggle="modal" data-target=".modalImportacaoHoras">
                                <i class="fa fa-upload"></i> Importar Horas
                            </button>
                        </div>

                    </form>

                    <!-- Modal Importação Horas Trabalhadas -->
                    <div class="modal fade modalImportacaoHoras">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                    <h4 class="modal-title">
                                        Importar dados
                                    </h4>
                                </div>
                                <form action="{{ route('rh.horastrabalhadasdiarias.import') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group @if ($errors->has('csv_file')) has-error @endif">
                                            <div class="col-sm-9">
                                                <input type="file" name="csv_file" class="form-control file" >
                                                @if ($errors->has('csv_file')) <p class="help-block">{{ $errors->first('csv_file') }}</p> @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Importar dados</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="row">
        @if(!is_null($tabela))
            <div class="card card-primary card-outline my-2 p-0">
                <div class="card-header">
                    <div class="row" style="align-items: right">
                        <div class="col-md-2" style="float: right;">
                            <form id="exportPdf" target="_blank" method="post" action="{{{ route('rh.horastrabalhadasdiarias.pdf') }}}">
                                {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                            'classButton' => 'btn btn-danger',
                                            'icon' => 'fa fa-file-pdf-o',
                                            'route' => 'rh.horastrabalhadasdiarias.pdf',
                                            'label' => 'Exportar para PDF',
                                            'method' => 'post',
                                            'id' => '',
                                            'attributes' => ['id' => 'formPdf']
                                            ]
                                        ]
                                ]) !!}
                                <input type="hidden" name="pel_id" id="periodoLaboralId" value="{{ Request::input('htr_pel_id')}}">
                                <input type="hidden" name="set_id" id="setorId" value="{{ implode(',', (array) Request::input('cfn_set_id')) }}">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {!! $tabela->render() !!}
                </div>
            </div>

            <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>
        @else
            <div class="card card-primary">
                <div class="card-body">Sem registros para apresentar</div>
            </div>
        @endif
    </div>
@stop