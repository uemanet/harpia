@section('stylesheets')
    <style>
        .title-box {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop
<!--  Dados da fonte pagadora  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados da Fonte Pagadora</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>


            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Razão Social: </strong> {{$fonte_pagadora->fpg_razao_social}}</p>
                        <p><strong>Nome Fantasia: </strong> {{$fonte_pagadora->fpg_nome_fantasia}}</p>
                        <p><strong>CNPJ: </strong> {{$fonte_pagadora->fpg_cnpj}}</p>

                    </div>

                    <div class="col-md-4">
                        <p><strong>Email: </strong> {{$fonte_pagadora->fpg_email}}</p>
                        <p><strong>Telefone: </strong> {{$fonte_pagadora->fpg_telefone}}</p>
                        <p><strong>Celular: </strong> {{$fonte_pagadora->fpg_celular}}</p>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Observação: </strong> {{$fonte_pagadora->fpg_observacao}}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>


<!--  Endereço  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Endereço</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>



            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>CEP: </strong> {{$fonte_pagadora->fpg_cep}}</p>
                        <p><strong>Endereço: </strong> {{$fonte_pagadora->fpg_endereco}}</p>
                        <p><strong>Bairro: </strong> {{$fonte_pagadora->fpg_bairro}}</p>
                        <p><strong>Número: </strong> {{$fonte_pagadora->fpg_numero}}</p>

                    </div>

                    <div class="col-md-4">
                        <p><strong>Complemento: </strong> {{$fonte_pagadora->fpg_complemento}}</p>
                        <p><strong>Cidade: </strong> {{$fonte_pagadora->fpg_cidade}}</p>
                        <p><strong>UF: </strong> {{$fonte_pagadora->fpg_uf}}</p>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Observação: </strong> {{$fonte_pagadora->fpg_observacao}}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>