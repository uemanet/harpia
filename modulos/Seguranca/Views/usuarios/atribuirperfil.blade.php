@extends('layouts.modulos.seguranca')

@section('title')
    Atribuir Perfis
@stop

@section('stylesheets')
    <link href="{{ asset('/css/plugins/jstree/style.min.css') }}" rel="stylesheet"/>
@stop

@section('subtitle')
    <b>Usuario:</b> {{ $usuario->pessoa->pes_nome }}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
            <div id="jstree">
                <ul>
                    @if(count($perfis))
                        @foreach($perfis as $key => $value)
                            <li>{{ ucfirst($key) }}
                                @if(count($value))
                                    <ul>
                                        @foreach($value as $obj)
                                            <li
                                                @if($obj->habilitado)
                                                    data-jstree='{"selected":"true", "type":"sub"}'
                                                @else
                                                    data-jstree='{"type":"sub"}'
                                                @endif
                                                id="prf_{{$obj->prf_id}}">
                                                    {{ $obj->prf_nome }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {!! Form::open(['route' => ['seguranca.usuarios.postAtribuirperfil', $usuario->usr_id], "method" => "POST", "role" => "form"]) !!}
                {!! Form::hidden('perfil','' , ['id'=>'perfil']) !!}
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::submit('Atribuir perfis ao usuário', ['class' => 'btn btn-primary pull-right', 'id' => 'btn-enviar']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/jstree/jstree.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#jstree').jstree({
                'core' : {
                    'check_callback' : true
                },
                'plugins' : [ 'types', 'checkbox' ],
                'types' : {
                    'default' : {
                        'icon' : 'fa fa-folder'
                    },
                    'sub' : {
                        'icon' : 'fa fa-cog'
                    }
                }
            });
        });

        $('#btn-enviar').on('click', function(e){
            e.preventDefault();

            $this = $(this);
            var checked_ids = [];

            var selectedItems = $("#jstree").jstree('get_selected');
            $(selectedItems).each(function(id, element){
                if(element.search(/prf_/) != -1) {
                    checked_ids.push(element.replace(/prf_/, ''));
                }
            });

            var ids = checked_ids.toString();

            $("#perfil").val(ids);

            $this.closest('form').submit();
        });
    </script>
@stop