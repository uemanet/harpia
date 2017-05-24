@if(isset($ofertas))
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Ofertas de Disciplinas
                    @if(isset($turma))
                    - {{ $turma->trm_nome }}
                    @endif
                </h3>
                <!-- /.box-title -->
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!empty($ofertas))
                <div id="accordion" class="box-group">
                    @foreach($ofertas as $oferta)
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <div class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$loop->index}}">
                                    {{ $oferta['per_nome'] }}
                                </a>
                            </div>
                        </div>
                        <div id="collapse{{$loop->index}}" class="panel-collapse collapse in">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if($oferta['ofertas']->count())
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="2%">#</th>
                                                <th>Disciplina</th>
                                                <th width="10%">ID Nota 1</th>
                                                <th width="10%">ID Nota 2</th>
                                                <th width="10%">ID Nota 3</th>
                                                <th width="10%">ID Conceito</th>
                                                <th width="10%">ID Recuperação</th>
                                                <th width="10%">ID Final</th>
                                                <th width="2%"></th>
                                                <th width="5%"></th>
                                                <th width="5%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($oferta['ofertas'] as $key => $value)
                                            @php
                                            $numerica = '';
                                            $conceito = 'disabled';

                                            if ($value->mdc_tipo_avaliacao == 'conceitual') {
                                            $numerica = 'disabled';
                                            $conceito = '';
                                            }
                                            @endphp
                                            <tr id="{{ $value->ofd_id }}">
                                                <td>{{ $value->ofd_id }}</td>
                                                <td>{{ $value->dis_nome }}</td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_nota1" class="form-control" value="{{ !$value->min_id_nota_um ? 0 : $value->min_id_nota_um }}" {{$numerica}}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_nota2" class="form-control" value="{{ !$value->min_id_nota_dois ? 0 : $value->min_id_nota_dois }}" {{$numerica}}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_nota3" class="form-control" value="{{ !$value->min_id_nota_tres ? 0 : $value->min_id_nota_tres }}" {{$numerica}}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_conceito" class="form-control" value="{{ !$value->min_id_conceito ? 0 : $value->min_id_conceito }}" {{$conceito}}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_recuperacao" class="form-control" value="{{ !$value->min_id_recuperacao ? 0 : $value->min_id_recuperacao }}" {{$numerica}}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" id="{{ $value->ofd_id }}_final" class="form-control" value="{{ !$value->min_id_final ? 0 : $value->min_id_final }}" {{$numerica}}>
                                                    </div>
                                                </td>
                                                <td style="text-align: center;">
                                                    <button class="btn bg-olive btnSalvar" data-id="{{ $value->ofd_id }}">
                                                        <i class="fa fa-save"></i>
                                                    </button>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="#" class="btn btn-primary">
                                                        <i class="fa fa-users"></i> Alunos
                                                    </a>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="#" class="btn btn-success">
                                                        <i class="fa fa-exchange"></i> Mapear notas
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                        <p>Não há ofertas para este período</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>Não há ofertas de disciplinas para esta turma</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif