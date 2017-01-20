<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_tipo')) has-error @endif">
        {!! Form::label('ltc_tipo', 'Tipo de TCC*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ltc_tipo', $tiposdetcc, old('ltc_tipo'), ['placeholder' => 'Selecione um tipo','class' => 'form-control']) !!}
            @if ($errors->has('ltc_tipo')) <p class="help-block">{{ $errors->first('ltc_tipo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ltc_titulo')) has-error @endif">
      {!! Form::label('ltc_titulo', 'Título do TCC*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('ltc_titulo', old('ltc_titulo'), ['class' => 'form-control']) !!}
        @if ($errors->has('ltc_titulo')) <p class="help-block">{{ $errors->first('ltc_titulo') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_prf_id')) has-error @endif">
        {!! Form::label('ltc_prf_id', 'Professor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ltc_prf_id', $professores, old('ltc_prf_id'), ['placeholder' => 'Selecione um professor','class' => 'form-control']) !!}
            @if ($errors->has('ltc_prf_id')) <p class="help-block">{{ $errors->first('ltc_prf_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ltc_data_apresentacao')) has-error @endif">
      {!! Form::label('ltc_data_apresentacao', 'Data de apresentação*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('ltc_data_apresentacao', old('ltc_data_apresentacao'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
        @if ($errors->has('ltc_data_apresentacao')) <p class="help-block">{{ $errors->first('ltc_data_apresentacao') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-8 @if ($errors->has('ltc_observacao')) has-error @endif">
        {!! Form::label('ltc_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('ltc_observacao', old('ltc_observacao'), ['class' => 'form-control', 'rows' => '4']) !!}
            @if ($errors->has('ltc_observacao')) <p class="help-block">{{ $errors->first('ltc_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_file')) has-error @endif">
        {!! Form::label('ltc_file', 'Documento', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::file('ltc_file', ['class' => 'form-control file']) !!}
            @if ($errors->has('ltc_file')) <p class="help-block">{{ $errors->first('ltc_file') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-3 @if ($errors->has('ltc_anx_nome')) has-error @endif">
         {!! Form::label('ltc_anx_nome', 'Anexo', ['class' => 'control-label']) !!}
         <div class="control">
             @if($anexo != null)
             <input type="text" class="form-control first" placeholder="{{$anexo->anx_nome}}" disabled="">
             @else
             <input type="text" class="form-control" placeholder="Sem anexos" disabled="">
             @endif
         </div>
     </div>
     @if($anexo != null)
     {!! Form::label('', 'Ação', ['class' => 'control-label visivel']) !!}
     <div class="control visivel">
         <button type="button" class="btn-delete btn btn-danger visivel"><i class="fa fa-trash"></i> Excluir Anexo</button>
     </div>
     @endif

</div>
<div class="row">
    <div class="form-group col-md-8">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
     <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

     <script type="text/javascript">
         $(function () {

             var lancamentotccId = "{{$lancamentoTcc->ltc_id}}"
             var csrf_token = "{{csrf_token()}}";

             $(document).on('click', '.btn-delete', function (event) {
                 event.preventDefault();

                 swal({
                     title: "Tem certeza que deseja excluir?",
                     text: "Você não poderá recuperar essa informação!",
                     type: "warning",
                     showCancelButton: true,
                     confirmButtonColor: "#DD6B55",
                     confirmButtonText: "Sim, pode excluir!",
                     cancelButtonText: "Não, quero cancelar!",
                     closeOnConfirm: true
                 }, function(isConfirm){
                     if (isConfirm) {

                         var data = {ltc_id: lancamentotccId, _token : csrf_token};

                         $.harpia.showloading();

                         var result = false;

                         $.ajax({
                             type: 'POST',
                             url: '/academico/async/anexos/deletaranexolancamentotcc',
                             data: data,
                             success: function (data) {
                                 $.harpia.hideloading();

                                 toastr.success('Anexo excluído com sucesso!', null, {progressBar: true});
                                 $(".first").attr("placeholder", "Sem anexo").val("").focus().blur();
                                 $(".visivel").hide();
                             },
                             error: function (xhr, textStatus, error) {
                                 $.harpia.hideloading();

                                 switch (xhr.status) {
                                     case 400:
                                         toastr.error('Sem anexos para serem excluídos!', null, {progressBar: true});
                                         break;
                                     default:
                                         toastr.error(xhr.responseText, null, {progressBar: true});

                                         result = false;
                                 }
                             }
                         });
                     }
                 });

             });

         });
     </script>


 @endsection
