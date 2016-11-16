<?php
namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Polo;

class PoloTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0;$i<20;$i++)
        {
            $polo = new Polo();
            $polo->pol_nome = $faker->city;

            $polo->save();
        }
    }
}
