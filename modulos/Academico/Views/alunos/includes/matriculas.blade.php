<!-- Matriculas -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cursos Matriculados</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$aluno->matriculas->isEmpty())
                    <div class="box-group" id="accordion">
                        @foreach($aluno->matriculas as $matricula)
                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$loop->index}}">
                                            {{ $matricula->turma->ofertacurso->curso->crs_nome }}
                                        </a>
                                    </h4>
                                    @if($matricula->mat_situacao == 'cursando')
                                        <span class="label label-info pull-right">Cursando</span>
                                    @elseif($matricula->mat_situacao == 'reprovado')
                                        <span class="label label-danger pull-right">Reprovado</span>
                                    @elseif($matricula->mat_situacao == 'concluido')
                                        <span class="label label-success pull-right">Concluído</span>
                                    @else
                                        <span class="label label-warning pull-right">{{ucfirst($matricula->mat_situacao)}}</span>
                                    @endif
                                </div>
                                <div class="panel-collapse collapse" id="collapse{{ $loop->index }}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Nível do Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}</p>
                                                <p><strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}</p>
                                                <p><strong>Modo de Entrada:</strong> {{ $matricula->mat_modo_entrada }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Oferta de Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}</p>
                                                <p><strong>Turma:</strong> {{$matricula->turma->trm_nome}}</p>
                                                <p><strong>Polo:</strong> {{$matricula->polo->pol_nome}}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Grupo:</strong> @if($matricula->grupo) {{$matricula->grupo->grp_nome}} @else Sem Grupo @endif</p>
                                                @if($matricula->mat_situacao == 'concluido')
                                                    <p><strong>Data de Conclusão:</strong> {{ $matricula->mat_data_conclusao }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if($matricula->mat_situacao != 'concluido')
                                                <div class="btn-group col-md-4">
                                                    <button type="button" class="btn btn-primary" value="{{ $matricula->mat_id }}" id="modalButton">Matrícula</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Aluno não possui nenhuma matrícula</p>
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'action' => '/academico/matricularalunocurso/create/' . $aluno->alu_id,
                            'label' => ' Nova Matrícula',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>
<!--        Modal       -->
<div class="modal" id="matricula-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alterar situação da matrícula</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('situacao', 'Situação*', ['class' => 'control-label']) !!}
                        <div class="controls">
                            {!! Form::select('situacao', $situacao, old('situacao'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="form-group col-md-6 text-right">
                        <button type="button" class="btn btn-primary" id="save">Salvar alterações</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#modalButton').on("click", function () {
                window.buttonGroup = $(this);

                $('#matricula-modal').modal();

                $('#save').on("click", function (e) {
                    var matricula = window.buttonGroup.val();
                    var token = "{{ csrf_token() }}";


                    console.log(window.buttonGroup.val());
                })
            })
        });
    </script>
@endsection