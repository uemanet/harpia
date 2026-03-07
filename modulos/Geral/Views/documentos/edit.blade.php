@extends('layouts.modulos.seguranca')

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
  <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Documento
@stop

@section('subtitle')
    Alterar documento :: {{$documentotipo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de documento</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('geral.pessoas.documentos.edit', [$documento->doc_id]) }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Form model: $documento - inputs devem usar old('campo', $documento->campo) --}}
                 @include('Geral::documentos.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
    </script>

    <script type="text/javascript">
            $('.datepicker').datepicker({
              format: 'dd/mm/yyyy',
              language: 'pt-BR'
            });
    </script>
@endsection
