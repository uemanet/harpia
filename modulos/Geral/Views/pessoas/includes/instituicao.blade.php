<!-- Instituição -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Instituição</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if($instituicao)
                    <p>{{$instituicao->itt_nome}}</p>
                @else
                    <p>Sem instituição vinculada</p>
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
        <!-- /.box -->
    </div>
</div>