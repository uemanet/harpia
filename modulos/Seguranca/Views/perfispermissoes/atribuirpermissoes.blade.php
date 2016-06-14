@extends('layouts.interno')

@section('stylesheets')
    <link href="{{ asset('/css/plugins/jstree/style.min.css') }}" rel="stylesheet"/>
@stop

@section('title')
    Atribuir Permissões
@stop

@section('subtitle')
    <b>Módulo:</b> {{ $perfil->mod_nome }} | <b>Perfil:</b> {{ $perfil->prf_nome }}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <div id="jstree">
                <ul>
                    @if(count($permissoes))
                        @foreach($permissoes as $permissao)
                            <li>{{ ucfirst($permissao['rcs_nome']) }}
                                @if(count($permissao['permissoes']))
                                    <ul>
                                        @foreach($permissao['permissoes'] as $perm)
                                            <li
                                                @if($perm['habilitado'])
                                                    data-jstree='{"selected":"true", "type":"sub"}'
                                                @else
                                                    data-jstree='{"type":"sub"}'
                                                @endif
                                                id="prm_{{$perm['prm_id']}}">
                                                    {{ $perm['prm_nome'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {!! Form::open(["url" => "/seguranca/perfispermissoes/atribuirpermissoes", "method" => "POST", "role" => "form"]) !!}
                {!! Form::hidden('permissoes','' , ['id'=>'permissoes']) !!}
                {!! Form::hidden('prf_id', $perfil->prf_id) !!}
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::submit('Atribuir permissões ao perfil', ['class' => 'btn btn-primary pull-right', 'id' => 'btn-enviar']) !!}
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
                if(element.search(/prm_/) != -1) {
                    checked_ids.push(element.replace(/prm_/, ''));
                }
            });

            $("#permissoes").val(checked_ids.toString());
            $this.closest('form').submit();
        });
    </script>
@stop