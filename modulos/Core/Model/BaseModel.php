<?php

namespace Modulos\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Modulos\Seguranca\Observers\AuditoriaObserver;
use Stevebauman\EloquentTable\TableTrait;

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
