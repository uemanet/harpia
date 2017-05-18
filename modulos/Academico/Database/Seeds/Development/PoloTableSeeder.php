<?php
namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Polo;

class PoloTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        for ($i=0;$i<40;$i++) {
            $polo = new Polo();
            $polo->pol_nome = $faker->city;

            $polo->save();
        }
    }
}
