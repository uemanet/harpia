<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Stevebauman\EloquentTable\TableCollection;

class Registro extends BaseModel
{
    protected $table = 'acd_registros';

    protected $primaryKey = 'reg_id';

    protected $fillable = [
        'reg_liv_id',
        'reg_usr_id',
        'reg_folha',
        'reg_registro',
        'reg_codigo_autenticidade'
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
    ];

    public function livro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Livro', 'reg_liv_id');
    }

    /**
     * Paginate the given query into a simple paginator.
     * @param  int  $perPage
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateUnion(TableCollection $collection, $perPage = 15, $pageName = 'page', $page = null)
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
