<table class="table table-bordered table-striped text-center">
    <thead>
    <tr>
        <th>Aluno</th>
        <th width="10%">Nota 1</th>
        <th width="10%">Nota 2</th>
        <th width="10%">Nota 3</th>
        <th width="10%">Recuperação</th>
        <th width="10%">Final</th>
        <th>Média final</th>
        <th width="15%">Situação</th>
        <th width="15%">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($matriculas as $matricula)
        <tr id="{{ $loop->index }}">
            <td>{{ $matricula->getAluno->pessoa->pes_nome }}</td>
            <td>{!! Form::number('nota_1', $matricula->mof_nota1 ? number_format($matricula->mof_nota1, 2) : $matricula->mof_nota1, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('nota_2', $matricula->mof_nota2 ? number_format($matricula->mof_nota2, 2) : $matricula->mof_nota2, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('nota_3', $matricula->mof_nota3 ? number_format($matricula->mof_nota3, 2) : $matricula->mof_nota3, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('recuperacao', $matricula->mof_recuperacao ? number_format($matricula->mof_recuperacao, 2) : $matricula->mof_recuperacao , ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01]); !!}</td>
            <td>{!! Form::number('final', $matricula->mof_final ? number_format($matricula->mof_final, 2) : $matricula->mof_final, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01]); !!}</td>
            <td>{{ $matricula->mof_mediafinal ? number_format($matricula->mof_mediafinal, 2) : $matricula->mof_mediafinal }}</td>
            <td>{{ $matricula->situacao_matricula }}</td>
            <td><a href="#" class="btn btn-success btn-salvar-notas"><i class="fa fa-save"></i> Salvar notas</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function () {

        $('.btn-salvar-notas').on('click', function(){
            var tableRow = $(this).closest('tr');
            var inputs = document.getElementById(tableRow.attr('id')).getElementsByTagName('input');

            for(var i = 0; i < inputs.length; i++) {
                console.log([i, inputs[i].valueAsNumber]);
            }
        });
    });
</script>
