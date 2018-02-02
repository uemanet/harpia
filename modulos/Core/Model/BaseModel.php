<?php

namespace Modulos\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\EloquentTable\TableTrait;
use Modulos\Seguranca\Observers\AuditoriaObserver;

class BaseModel extends Model
{
    use TableTrait;

    protected $searchable = [];

    public static function boot()
    {
        parent::boot();
        parent::observe(AuditoriaObserver::class);
    }

    public function searchable()
    {
        return $this->searchable;
    }
}
