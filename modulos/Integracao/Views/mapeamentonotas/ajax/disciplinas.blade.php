@if(isset($ofertas))
    <div class="col-md-12 p-0 my-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Ofertas de Disciplinas
                        @if(isset($turma))
                            - {{ $turma->trm_nome }}
                        @endif
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(!empty($ofertas))
                        <div class="accordion" id="accordionOfertas">
                            @foreach($ofertas as $oferta)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{$loop->index}}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->index}}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{$loop->index}}">
                                            {{ $oferta['per_nome'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$loop->index}}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{$loop->index}}" data-bs-parent="#accordionOfertas">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if($oferta['ofertas']->count())
                                                        <div class="table-responsive">
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
                                                                    <th width="10%">ID Aproveitamento de Estudos</th>
                                                                    <th width="2%"></th>
                                                                    <th width="5%"></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($oferta['ofertas'] as $key => $value)
                                                                    @php
                                                                        $numerica = '';
                                                                        $conceito = 'disabled';

                                                                        if ($value->getRawOriginal('ofd_tipo_avaliacao') == 'conceitual') {
                                                                            $numerica = 'disabled';
                                                                            $conceito = '';
                                                                        }
                                                                    @endphp
                                                                    <tr id="{{ $value->ofd_id }}">
                                                                        <td>{{ $value->ofd_id }}</td>
                                                                        <td>{{ $value->dis_nome }}</td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_nota1" class="form-control" value="{{ !$value->min_id_nota1 ? 0 : $value->min_id_nota1 }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_nota2" class="form-control" value="{{ !$value->min_id_nota2 ? 0 : $value->min_id_nota2 }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_nota3" class="form-control" value="{{ !$value->min_id_nota3 ? 0 : $value->min_id_nota3 }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_conceito" class="form-control" value="{{ !$value->min_id_conceito ? 0 : $value->min_id_conceito }}" {{$conceito}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_recuperacao" class="form-control" value="{{ !$value->min_id_recuperacao ? 0 : $value->min_id_recuperacao }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_final" class="form-control" value="{{ !$value->min_id_final ? 0 : $value->min_id_final }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group mb-0">
                                                                                <input type="text" id="{{ $value->ofd_id }}_aproveitamento" class="form-control" value="{{ !$value->min_id_aproveitamento ? 0 : $value->min_id_aproveitamento }}">
                                                                            </div>
                                                                        </td>
                                                                        <td style="text-align: center; vertical-align: middle;">
                                                                            <button class="btn btn-success btnSalvar" data-id="{{ $value->ofd_id }}">
                                                                                <i class="fa fa-save"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td style="text-align: center; vertical-align: middle;">
                                                                            <a href="{{{ route('integracao.mapeamentonotas.showalunos', $value->ofd_id) }}}" class="btn btn-primary">
                                                                                <i class="fa fa-exchange"></i> Migrar Notas
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="mb-0">Não há ofertas para este período</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mb-0">Não há ofertas de disciplinas para esta turma</p>
                    @endif
                </div>
            </div>
        </div>
@endif