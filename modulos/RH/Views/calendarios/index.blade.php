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
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header>
                    <h2>Adicionar Eventos</h2>
                </header>
                <div>
                    <div class="widget-body">
                        <form id="formEvent" action="javascript:func()" method="post">
                            <input type="hidden" id="cldId" name="cldId" value="">
                            <fieldset>
                                <div class="form-group">
                                    <section>
                                        <label>Tipo</label>
                                        <select name="cldTipoEvento" id="cldTipoEvento" class="form-control">
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
                                        <input class="form-control"  id="cldNome" name="cldNome" maxlength="40" type="text" placeholder="Nome do fériado">
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label class="control-label" for="ferData">Data*</label>
                                        <div class="input">
                                            <input type="date" name="cldData" id="cldData" class="form-control" value="">
                                        </div>
                                    </section>
                                </div>
                                <div class="form-group">
                                    <section>
                                        <label>Observação</label>
                                        <textarea class="form-control" name="cldObservacao" placeholder="" rows="3" maxlength="40" id="cldObservacao"></textarea>
                                    </section>
                                </div>
                                <div class="form-actions">
                                    <div class="col-md-12" id="footerForm" style="margin-bottom: 10px">
                                        <button class="btn btn-default btn-sm" type="button" id="btnNovo" >
                                            Novo
                                        </button>
                                        <button class="btn btn-success btn-sm" type="submit" id="btnSalvar" >Salvar</button>
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
    <script>
        $(function () {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function init_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex        : 1070,
                        revert        : true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            init_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()
            $('#calendar').fullCalendar({
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week : 'week',
                    day  : 'day'
                },
                //Random default events
                events    : [
                    {
                        title          : 'All Day Event',
                        start          : new Date(y, m, 1),
                        backgroundColor: '#f56954', //red
                        borderColor    : '#f56954' //red
                    },
                    {
                        title          : 'Long Event',
                        start          : new Date(y, m, d - 5),
                        end            : new Date(y, m, d - 2),
                        backgroundColor: '#f39c12', //yellow
                        borderColor    : '#f39c12' //yellow
                    },
                    {
                        title          : 'Meeting',
                        start          : new Date(y, m, d, 10, 30),
                        allDay         : false,
                        backgroundColor: '#0073b7', //Blue
                        borderColor    : '#0073b7' //Blue
                    },
                    {
                        title          : 'Lunch',
                        start          : new Date(y, m, d, 12, 0),
                        end            : new Date(y, m, d, 14, 0),
                        allDay         : false,
                        backgroundColor: '#00c0ef', //Info (aqua)
                        borderColor    : '#00c0ef' //Info (aqua)
                    },
                    {
                        title          : 'Birthday Party',
                        start          : new Date(y, m, d + 1, 19, 0),
                        end            : new Date(y, m, d + 1, 22, 30),
                        allDay         : false,
                        backgroundColor: '#00a65a', //Success (green)
                        borderColor    : '#00a65a' //Success (green)
                    },
                    {
                        title          : 'Click for Google',
                        start          : new Date(y, m, 28),
                        end            : new Date(y, m, 29),
                        url            : 'http://google.com/',
                        backgroundColor: '#3c8dbc', //Primary (light-blue)
                        borderColor    : '#3c8dbc' //Primary (light-blue)
                    }
                ],
                editable  : true,
                droppable : true, // this allows things to be dropped onto the calendar !!!
                drop      : function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject')

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject)

                    // assign it the date that was reported
                    copiedEventObject.start           = date
                    copiedEventObject.allDay          = allDay
                    copiedEventObject.backgroundColor = $(this).css('background-color')
                    copiedEventObject.borderColor     = $(this).css('border-color')

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove()
                    }

                }
            })

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            //Color chooser button
            var colorChooser = $('#color-chooser-btn')
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                //Save color
                currColor = $(this).css('color')
                //Add color effect to button
                $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault()
                //Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                //Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color'    : currColor,
                    'color'           : '#fff'
                }).addClass('external-event')
                event.html(val)
                $('#external-events').prepend(event)

                //Add draggable funtionality
                init_events(event)

                //Remove event from text input
                $('#new-event').val('')
            })
        })


    </script>

@endsection
