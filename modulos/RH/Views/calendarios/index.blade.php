@extends('layouts.modulos.rh')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/fullcalendar.min.css')}}">
@endsection


@section('title')
    Módulo de RH
@stop

@section('subtitle')
    Módulo de RH
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-3">
            <div class=" box box-primary jarviswidget jarviswidget-color-blueDark">
                <header>
                    <h2 style="padding-left: 10px">Adicionar Eventos</h2>
                </header>
                <div>
                    <div class="widget-body" style="padding: 10px">
                        <form id="formEvent" action="javascript:func()" method="post">
                            <input type="hidden" id="cld_id" name="cld_id" value="">
                            <fieldset>
                                <div class="form-group">
                                    <section>
                                        <label>Tipo</label>
                                        <select name="cld_tipo_evento" id="cld_tipo_evento" class="form-control">
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
                                               type="text" placeholder="Nome do fériado">
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label class="control-label" for="ferData">Data*</label>
                                        <div class="input">
                                            <input type="date" name="cld_data" id="cld_data"
                                                   class="form-control required"
                                                   value="">
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
                                <div class="form-actions">
                                    <div class="col-md-12" id="footerForm" style="margin-bottom: 10px">
                                        <button class="btn btn-default btn-sm" type="button" id="btnNovo">
                                            Novo
                                        </button>
                                        <button class="btn btn-success btn-sm" type="submit" id="btnSalvar">Salvar
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
                <div class="box-body no-padding">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        @stop

        @section('scripts')
            <script src="{{asset('/js/moment.js')}}" type="text/javascript"></script>
            <script src="{{asset('/js/fullcalendar.js')}}" type="text/javascript"></script>
            <script src="{{asset('/js/jquery.validate.min.js')}}" type="text/javascript"></script>

            <script>

                $(document).ready(function () {

                    $("#formEvent").validate({
                        // Rules for form validation
                        rules: {
                            cld_nome: {
                                required: true,
                                maxlength: 100
                            },
                            cld_tipo_evento: {
                                required: true
                            },
                            cld_data: {
                                required: true
                            },
                            cld_observacao: {
                                required: false
                            }
                        },
                        // Messages for form validation
                        messages: {
                            cld_nome: {
                                required: 'Campo obrigatorio'
                            },
                            cld_tipo_evento: {
                                required: 'Campo obrigatorio'
                            },
                            cld_data: {
                                required: 'Campo obrigatorio'
                            },
                            cld_observacao: {
                                required: 'Campo obrigatorio'
                            }
                        },
                        submitHandler :function(e) {
                            data = {
                                cld_id: $('#cld_id').val(),
                                cld_data: $('#cld_data').val(),
                                cld_nome: $('#cld_nome').val(),
                                cld_tipo_evento: $('#cld_tipo_evento').val(),
                                cld_observacao: $('#cld_observacao').val(),
                                _token: "{{ csrf_token() }}",
                            };

                            // Ajax request
                            url = "{{ route('rh.async.calendarios.create') }}";

                            $.harpia.showloading();
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (response) {
                                    $.harpia.hideloading();
                                    getEventsData()

                                },
                                error: function (err) {
                                    $.harpia.hideloading();
                                    toastr.error(err.responseJSON.message, null, {progressBar: true});

                                    // Reabilita o botao
                                    btn.disabled = false;
                                }
                            });
                        }

                    });

                });
                

                getEventsData()

                function getEventsData() {

                    // Dados do calendário
                    $.ajax({
                        url: "{{ route("rh.async.calendarios.index") }}",
                        type: "GET",
                        success: function (data) {

                            var eventos = new Array();

                            $.each(data, function (chave, objeto) {

                                evento = new Object();
                                evento.title = objeto.cld_nome;
                                evento.start = objeto.cld_data;
                                evento.id = objeto.cld_id;

                                eventos[chave] = evento;
                            });

                            renderCalendar(eventos)
                        },
                        error: function (error) {

                        }
                    });


                }

                function renderCalendar(data) {

                    $('#calendar').fullCalendar('destroy');

                    /* initialize the calendar
                     -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)
                    var date = new Date()
                    var d = date.getDate(),
                        m = date.getMonth(),
                        y = date.getFullYear()

                    $('#calendar').fullCalendar({

                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        buttonText: {
                            today: 'today',
                            month: 'month',
                            week: 'week',
                            day: 'day'
                        },
                        //Random default events
                        events: data,
                        eventRender: function (event, element) {
                            element.append("<span class='closeon'><i class='air air-top-right fa fa-edit' style='padding:2px;cursor:pointer '></i></span>");
                            element.find(".closeon").click(function () {
                                editEvent(event.id);
                            });
                        },
                        // editable: true,
                        // droppable: true, // this allows things to be dropped onto the calendar !!!

                    })


                }

                function editEvent(id) {

                    // Dados do calendário
                    $.ajax({
                        url: "async/calendarios/edit/" + id,
                        type: "GET",
                        success: function (data) {


                            $('#btnExcluir').remove();

                            $('#footerForm').append('<button class="btn btn-danger" type="button" id="btnExcluir" data-id="' + data.cld_id + '" >Excluir</button>');

                            $('#btnExcluir').click(function (e) {
                                var itemSelecionado = $(e.currentTarget).data('id');

                                removeEvent(itemSelecionado);

                            });

                            $('#cld_id').val(data.cld_id);
                            $('#cld_nome').val(data.cld_nome);
                            $('#cld_tipo_evento').val(data.cld_tipo_evento);
                            $('#cld_observacao').val(data.cld_observacao);
                            $('#cld_data').val(data.cld_data);

                            $('#btnSalvar').html('Alterar');

                        },
                        error: function (error) {

                        }
                    });

                }

                function removeEvent(id) {

                    data = {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    };

                    // Ajax request
                    url = "{{ route('rh.async.calendarios.delete') }}";

                    $.harpia.showloading();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (response) {
                            $.harpia.hideloading();
                            getEventsData()
                            clearForm();
                            $('#btnExcluir').remove();

                        },
                        error: function (err) {
                            $.harpia.hideloading();
                            toastr.error(err.responseJSON.message, null, {progressBar: true});

                            // Reabilita o botao
                            btn.disabled = false;
                        }
                    });

                    getEventsData()
                    clearForm();
                    $('#btnExcluir').remove();
                }

                function clearForm() {
                    $('#btnExcluir').remove();

                    $('#cld_id').val('');
                    $('#cld_nome').val('');
                    $('#_tipo_evento').val('');
                    $('#cld_observacao').val('');
                    $('#cld_data').val('');

                    $('#_tipo_evento').focus().val(0);

                    $('#btnSalvar').prop("disabled", false);
                    $('#btnSalvar').html('Salvar');

                }

                $('#btnNovo').click(function (e) {
                    clearForm();
                });

            </script>

@endsection
