<?php

namespace Modulos\Seguranca\Observers;

use Modulos\Core\Model\BaseModel;
use DB;
use Auth;

class AuditoriaObserver
{
    public function created(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->toArray(), JSON_UNESCAPED_UNICODE);

            $data = [
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'INSERT',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            DB::table('seg_auditoria')->insert($data);
        }
    }

    public function updated(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->getRawOriginal(), JSON_UNESCAPED_UNICODE);

            $data = [
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'UPDATE',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            DB::table('seg_auditoria')->insert($data);
        }
    }

    public function deleted(BaseModel $model)
    {
        if (Auth::check()) {
            $jsonObject = json_encode($model->getRawOriginal(), JSON_UNESCAPED_UNICODE);

            $data = [
                'log_usr_id' => Auth::user()->usr_id,
                'log_action' => 'DELETE',
                'log_table' => $model->getTable(),
                'log_table_id' => $model->getKey(),
                'log_object' => $jsonObject,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            DB::table('seg_auditoria')->insert($data);
        }
    }
}
