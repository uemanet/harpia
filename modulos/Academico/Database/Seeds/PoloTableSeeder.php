<?php
namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Polo;

class PoloTableSeeder extends Seeder
{
    public function run()
    {
        $polo1 = new Polo();

        $polo1->pol_nome = 'São Luís';

        $polo1->save();

        $polo2 = new Polo();

        $polo2->pol_nome = 'Codó';

        $polo2->save();
    }
}
