<?php

namespace Modulos\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\EloquentTable\TableTrait;

class BaseModel extends Model
{
    use TableTrait;

    protected $searchable = [];

    public static function boot() {
        parent::boot();
    }

    public function searchable()
    {
        return $this->searchable;
    }
}