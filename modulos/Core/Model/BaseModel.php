<?php

namespace Modulos\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Uemanet\EloquentTable\TableTrait;
use Modulos\Seguranca\Observers\AuditoriaObserver;
use DateTimeInterface;
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

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
