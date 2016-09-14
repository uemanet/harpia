
    <div class="form-group col-md-4 @if ($errors->has('grp_nome')) has-error @endif">
        {!! Form::label('grp_nome', 'Nome do Grupo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('grp_nome', old('grp_nome'), ['class' => 'form-control select-control']) !!}
            @if ($errors->has('grp_nome')) <p class="help-block">{{ $errors->first('grp_nome') }}</p> @endif
        </div>
    </div>
<!-- </div> -->
<!-- <div class="row"> -->
    <div class="form-group col-md-2">
        <label class="control-label" style="visibility: hidden">Submit</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>
@endsection
