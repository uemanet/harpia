<?php

namespace Modulos\Seguranca\Providers\Auditoria;

use Modulos\Core\Model\BaseModel;
use Modulos\Seguranca\Models\Auditoria;
use Auth;

class AuditoriaObserver
{
    public function created(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->toArray(), JSON_UNESCAPED_UNICODE);

            Auditoria::create([
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'INSERT',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject
            ]);
        }
    }

    public function updated(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);

            Auditoria::create([
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'UPDATE',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject
            ]);
        }
    }

    public function deleted(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);

            Auditoria::create([
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'DELETE',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject
            ]);
        }
    }
}
