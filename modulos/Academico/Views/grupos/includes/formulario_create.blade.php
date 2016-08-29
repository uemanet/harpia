<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $cursos, old('crs_id'), ['class' => 'form-control select-control', 'placeholder' => 'Selecione um curso']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('grp_trm_id')) has-error @endif">
        {!! Form::label('grp_trm_id', 'Turma*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('grp_trm_id', [], old('grp_trm_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('grp_trm_id')) <p class="help-block">{{ $errors->first('grp_trm_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('grp_pol_id')) has-error @endif">
        {!! Form::label('grp_pol_id', 'Polo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('grp_pol_id', [], old('grp_pol_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('grp_pol_id')) <p class="help-block">{{ $errors->first('grp_pol_id') }}</p> @endif
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();

            $('#crs_id').on('change', function () {
                var curso = $(this).val();

                if(!curso) {
                    return;
                }

                $.harpia.httpget('{{url('/')}}/academico/async/turmas/turmasbycurso/'+curso).done(function(result) {
                    var selectTurma = $('#grp_trm_id');

                    selectTurma.empty();

                    if ($.isEmptyObject(result)) {
                        selectTurma.append('<option value=#>Sem turmas disponíveis</option>');
                    } else {
                        selectTurma.append("<option value='' selected>Selecione uma turma</option>");
                        console.log(result);
                        $.each(result, function(key, value) {
                            selectTurma.append('<option value=' + value.trm_id + ' >' + value.trm_nome + '</option>');
                        });
                    }

                    selectTurma.focus();
                });

                $.harpia.httpget('{{url('/')}}/academico/async/polos/polosbycurso/'+curso).done(function (result) {
                    var selectPolo = $('#grp_pol_id');

                    selectPolo.empty();

                    if ($.isEmptyObject(result)) {
                        selectPolo.append('<option value=#>Sem polos disponíveis</option>');
                    } else {
                        selectPolo.append("<option value='' selected>Selecione um Polo</option>");
                        $.each(result, function(key, value) {
                            selectPolo.append('<option value=' + value.pol_id + ' >' + value.pol_nome + '</option>');
                        });
                    }

                    selectPolo.focus();
                });
            });
        });
    </script>
@endsection

@include('Academico::grupos.includes.formulario_base')