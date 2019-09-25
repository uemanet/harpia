<?php

namespace Modulos\Academico\Models;

use Illuminate\Pagination\Paginator;
use Modulos\Core\Model\BaseModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Uemanet\EloquentTable\TableCollection;

class Aluno extends BaseModel
{
    protected $table = 'acd_alunos';

    protected $primaryKey = 'alu_id';

    protected $fillable = ['alu_pes_id'];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'alu_pes_id');
    }

    public function matriculas()
    {
        return $this->hasMany('Modulos\Academico\Models\Matricula', 'mat_alu_id');
    }

    /**
     * Paginate the given query into a simple paginator.
     * @param  int  $perPage
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateWithBonds(TableCollection $collection, $perPage = 15, $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        $total = $collection->count();

        $results = $total ? $collection->slice(($page - 1) * $perPage, $perPage) : [];
        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
