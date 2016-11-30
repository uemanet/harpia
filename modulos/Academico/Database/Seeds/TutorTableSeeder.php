<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Tutor;

class TutorTableSeeder extends Seeder
{
    public function run()
    {
        for ($i=1;$i<=50;$i++) {
            $tutor = new Tutor();
            $tutor->tut_pes_id = $i;
            $tutor->save();
        }
    }
}
