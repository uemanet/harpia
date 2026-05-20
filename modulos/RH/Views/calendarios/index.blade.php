@extends('layouts.modulos.default')

@section('title')
    Módulo de RH
@stop

@section('subtitle')
    Módulo de RH
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-12 mb-3">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0">Adicionar Eventos</h3>
                </div>
                <div class="card-body">
                        <form
                            id="formEvent"
                            method="post"
                            onsubmit="return false"
                            data-events-url="{{ route('rh.async.calendarios.index') }}"
                            data-save-url="{{ route('rh.async.calendarios.create') }}"
                            data-edit-url-template="{{ route('rh.async.calendarios.edit', ['id' => '__ID__']) }}"
                            data-delete-url="{{ route('rh.async.calendarios.delete') }}"
                        >
                            <input type="hidden" id="cld_id" name="cld_id" value="">
                            <fieldset>
                                <div class="form-group">
                                    <section>
                                        <label>Tipo</label>
                                        <select name="cld_tipo_evento" id="cld_tipo_evento" class="form-control" required>
                                            <option value="">Selecione</option>
                                            <option value="FN">Feriado Nacional</option>
                                            <option value="FE">Feriado Estadual</option>
                                            <option value="FM">Feriado Municipal</option>
                                            <option value="PF">Ponto Facultativo</option>
                                        </select>
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label>Nome</label>
                                        <input class="form-control" id="cld_nome" name="cld_nome" maxlength="40"
                                               type="text" placeholder="Nome do fériado" required>
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label class="form-label" for="ferData">Data*</label>
                                        <div class="input">
                                            <input type="date" name="cld_data" id="cld_data"
                                                   class="form-control"
                                                   required>
                                        </div>
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label>Observação</label>
                                        <textarea class="form-control" name="cld_observacao" placeholder="" rows="3"
                                                  maxlength="40" id="cld_observacao"></textarea>
                                    </section>
                                </div>
                                <div class="form-group my-2">
                                    <div class="col-md-12" id="footerForm" style="margin-bottom: 10px">
                                        <button class="btn btn-primary" type="button" id="btnNovo">
                                            Novo
                                        </button>
                                        <button class="btn btn-success" type="submit" id="btnSalvar">Salvar
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="box box-primary">
                <div class="card-body no-padding">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /. box -->
        </div>
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            calendarios_create: "{{ route('rh.async.calendarios.create') }}",
            calendarios_index: "{{ route("rh.async.calendarios.index") }}",
            calendarios_delete: "{{ route('rh.async.calendarios.delete') }}"
        };
    </script>

    @vite('modulos/RH/Resources/js/pages/calendarios/index.js')
@stop