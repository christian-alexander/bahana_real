<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BahanaLogistikInit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');
        for($i = 1; $i <= 50; $i++){
            // insert data ke table lokasi
            DB::connection('mysql_logistik')->table('lokasi')->insert([
                'lok' => $faker->city,
                'nm' => $faker->city,
            ]);
            // insert data ke table lokasi
            DB::connection('mysql_logistik')->table('mtstock')->insert([
                'tgl' => Carbon::now(),
                'tg1' => Carbon::now(),
                'kdstk' => $faker->unique()->numberBetween(1, 100000000),
                'nm' => $faker->name,
                'pn' => null,
                'sat' => 'pcs',
                'spek' => null,
                'merk' => null,
                'ket' => null,
                'pack' => null,
                'gbr' => null,
                'kd' => null,
                'lw' => null,
                'nmusr' => null,
                'tgusr' => null,
            ]);

      }
    }
}
