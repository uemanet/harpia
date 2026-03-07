
    <div class="form-group col-md-4 @if ($errors->has('grp_nome')) has-error @endif">
        <label for="grp_nome" class="control-label">Nome do Grupo*</label>
        <div class="controls">
            <input type="text" name="grp_nome" value="{{ old('grp_nome') }}" class="form-control select-control" >
            @if ($errors->has('grp_nome')) <p class="help-block">{{ $errors->first('grp_nome') }}</p> @endif
        </div>
    </div>
<!-- </div> -->
<!-- <div class="row"> -->
    <div class="form-group col-md-2">
        <label class="control-label" style="visibility: hidden">Submit</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary">Salvar dados</button>
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
