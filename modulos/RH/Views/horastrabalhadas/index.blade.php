@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Horas Trabalhadas
@stop

@section('subtitle')
    Gerenciamento de Horas de Colaboradores
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="card-tools float-end">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{{ route('rh.horastrabalhadas.index') }}}">
                <div class="row">
                    <div class="col-md-2 px-1">
                        <select name="htr_pel_id" id="htr_pel_id" class="form-control">
    <option value="">Selecione o período laboral</option>
    @foreach($periodosLaborais as $key => $value)
        <option value="{{ $key }}" {{ Request::input('htr_pel_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                    </div>

                    <div class="col-md-3 px-1">
                        <select name="cfn_set_id[]" id="cfn_set_id" class="form-control" multiple="true">
    @foreach($setores as $key => $value)
        <option value="{{ $key }}" {{ Request::input('cfn_set_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                    </div>

                    <div class="col-md-3 px-1">
                        <select name="col_pes_id[]" id="col_pes_id" class="form-control" multiple="true">
    @foreach($colaboradores as $key => $value)
        <option value="{{ $key }}" {{ Request::input('col_pes_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                    </div>

                    <div class="col-md-2 px-1">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>

                    <div class="col-md-2 px-1">
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target=".modalImportacaoHoras">
                            <i class="fa fa-upload"></i> Importar Horas
                        </button>
                    </div>
                </div>
            </form>

                <!-- Modal Importação Horas Trabalhadas -->
                <div class="modal fade modalImportacaoHoras">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                                <h4 class="modal-title">
                                    Importar dados
                                </h4>
                            </div>
                            <div class="modal-body">

                                <form action="{{ route('rh.horastrabalhadasdiarias.import') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    {{-- Form model: [] - inputs devem usar old('campo', []->campo) --}}

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
                                </form>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if(!is_null($tabela))
        <div class="card card-primary card-outline">
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
        <div class="card card-primary card-outline">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif

    <style>
        .select2-container .select2-selection--single {
            height: 32px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc;
        }

    </style>
@stop



@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#htr_pel_id").select2({
            });

            $('#cfn_set_id').select2({
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Selecione os Setores',
            }).on('select2:select', function () {
                $('.select2-search__field').val('');
            });


            $('#col_pes_id').select2({
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Selecione os colaboradores',
            }).on('select2:select', function () {
                $('.select2-search__field').val('');
            });
        });
    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection
