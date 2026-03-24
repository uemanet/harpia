@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Relatório de Alunos por Curso
@endsection

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
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
                <form id="form" method="GET" action="">
                    <div class="row">
                        <div class="col-md-4 @if ($errors->has('crs_id')) has-error @endif">
                            <label for="crs_id">Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="crs_id" id="crs_id" class="form-control">
                                    <option value="">Escolha o Curso</option>
                                    @foreach($cursos as $key => $value)
                                        <option value="{{ $key }}" {{ Request::input('crs_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
                            <label for="ofc_id">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="ofc_id" id="ofc_id" class="form-control">
                                    @foreach($ofertasCurso as $key => $value)
                                        <option value="{{ $key }}" {{ Request::input('ofc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('trm_id')) has-error @endif">
                            <label for="trm_id">Turma <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="trm_id" id="trm_id" class="form-control">
                                    @foreach($turmas as $key => $value)
                                        <option value="{{ $key }}" {{ Request::input('trm_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 @if ($errors->has('pol_id')) has-error @endif">
                            <label for="pol_id">Polo</label>
                            <div class="form-group">
                                <select name="pol_id" id="pol_id" class="form-control">
                                    <option value="">Selecione o polo</option>
                                    @foreach($polos as $key => $value)
                                        <option value="{{ $key }}" {{ Request::input('pol_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pol_id')) <p class="help-block">{{ $errors->first('pol_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('mat_situacao')) has-error @endif">
                            <label for="mat_situacao">Situação</label>
                            <div class="form-group">
                                <select name="mat_situacao" id="mat_situacao" class="form-control">
                                    @foreach($situacao as $key => $value)
                                        <option value="{{ $key }}" {{ Request::input('mat_situacao') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('mat_situacao')) <p class="help-block">{{ $errors->first('mat_situacao') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label>
                            <div class="form-group">
                                <input type="submit" id="btnBuscar" class="btn btn-primary w-100" value="Buscar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row">
        @if(!is_null($tabela))
            <div class="card card-primary card-outline my-2">
                <div class="card-header">
                    <div class="row" style="align-items: right">
                        <div class="col-md-2" style="float: right; margin-right: 1%;">
                            <form id="exportXLS" target="_blank" method="post" action="{{{ route('academico.relatoriosmatriculascurso.xls') }}}">
                                {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                            'classButton' => 'btn btn-success',
                                            'icon' => 'fa fa-file-excel-o',
                                            'route' => 'academico.relatoriosmatriculascurso.pdf',
                                            'label' => 'Exportar para XLS',
                                            'method' => 'post',
                                            'id' => '',
                                            'attributes' => ['id' => 'formPdf','target' => '_blank']
                                            ]
                                        ]
                                ]) !!}
                                <input type="hidden" name="trm_id" id="turmaId" value="">
                                <input type="hidden" name="crs_id" id="cursoId" value="">
                                <input type="hidden" name="ofc_id" id="ofertaCursoId" value="">
                                <input type="hidden" name="pol_id" id="poloId" value="">
                                <input type="hidden" name="mat_situacao" id="situacao" value="">
                            </form>
                        </div>
                        <div class="col-md-2" style="float: right;">
                            <form id="exportPdf" target="_blank" method="post" action="{{{ route('academico.relatoriosmatriculascurso.pdf') }}}">
                                {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                            'classButton' => 'btn btn-danger',
                                            'icon' => 'fa fa-file-pdf-o',
                                            'route' => 'academico.relatoriosmatriculascurso.pdf',
                                            'label' => 'Exportar para PDF',
                                            'method' => 'post',
                                            'id' => '',
                                            'attributes' => ['id' => 'formPdf','target' => '_blank']
                                            ]
                                        ]
                                ]) !!}
                                <input type="hidden" name="trm_id" id="pturmaId" value="">
                                <input type="hidden" name="crs_id" id="pcursoId" value="">
                                <input type="hidden" name="ofc_id" id="pofertaCursoId" value="">
                                <input type="hidden" name="pol_id" id="ppoloId" value="">
                                <input type="hidden" name="mat_situacao" id="psituacao" value="">
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
            <div id="cardInfo" class="card card-primary card-outline my-2">
                <div class="card-body">Sem registros para apresentar</div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var polosSelect = $('#pol_id');
            var routePdf = "{{{ route('academico.relatoriosmatriculascurso.pdf') }}}";
            var routeIndex = "{{{ route('academico.relatoriosmatriculascurso.index') }}}";
            var cursoSelect = $('#crs_id');
            var situacaoSelect = $('#mat_situacao');

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                polosSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if (!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="' + obj.ofc_id + '">' + obj.ofc_ano + ' (' + obj.mdl_nome + ')</option>');
                        });
                    } else {
                        ofertasCursoSelect.append("<option>Sem ofertas disponiveis</option>");
                    }
                });
            });

            // evento change select de ofertas de curso
            ofertasCursoSelect.change(function () {

                //limpando selects
                turmaSelect.empty();
                polosSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if (!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });

                // buscar polos
                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        polosSelect.append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            polosSelect.append("<option value='"+obj.pol_id+"'>"+obj.pol_nome+"</option>");
                        });
                    } else {
                        polosSelect.append("<option value=''>Sem polos cadastrados</option>");
                    }
                });
            });



            $(document).on('click', '#btnBuscar', function () {
                $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
            });

            $('#turmaId').attr('value', turmaSelect.val());
            $('#ofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#poloId').attr('value', polosSelect.val());
            $('#cursoId').attr('value', cursoSelect.val());
            $('#situacao').attr('value', situacaoSelect.val());

            $('#pturmaId').attr('value', turmaSelect.val());
            $('#pofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#ppoloId').attr('value', polosSelect.val());
            $('#pcursoId').attr('value', cursoSelect.val());
            $('#psituacao').attr('value', situacaoSelect.val());

            $(document).on('click', '#formPdf', function (event) {
                $('#exportPdf').submit();
            });
        });
    </script>
@endsection
