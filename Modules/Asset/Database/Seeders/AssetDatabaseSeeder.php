<?php

namespace Modules\Asset\Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Seeder;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetHistory;
use Modules\Asset\Entities\AssetType;

class AssetDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \DB::beginTransaction();

        $_ENV['SEEDING'] = true;

        if (env('APP_ENV') !== 'codecanyon') {
            \DB::table('asset_types')->delete();
            \DB::table('assets')->delete();

            \DB::statement('ALTER TABLE asset_types AUTO_INCREMENT = 1');
            \DB::statement('ALTER TABLE assets AUTO_INCREMENT = 1');
            \DB::statement('ALTER TABLE asset_lending_history AUTO_INCREMENT = 1');

            \DB::table('asset_types')->insert([
                [
                    'name' => 'Laptop'
                ],
                [
                    'name' => 'Desktop'
                ],
                [
                    'name' => 'Mobile'
                ],
                [
                    'name' => 'Printer'
                ],
                [
                    'name' => 'Scanner'
                ],
                [
                    'name' => 'Two-Wheeler'
                ],
                [
                    'name' => 'Car'
                ],
                [
                    'name' => 'Other'
                ]
            ]);

            $faker = \Faker\Factory::create();
            $faker->seed(10);

            $factory = Factory::construct($faker, base_path() . '/modules/Asset/Database/Factories');

            $users = User::get()->pluck('id');
            $assetTypes = AssetType::get()->pluck('id');

            $admin = User::first()->id;

            $factory->of(Asset::class)->times(20)->make([

            ])->each(function ($asset) use ($factory, $users, $admin, $faker, $assetTypes) {
                $asset->asset_type_id = $assetTypes->get($faker->numberBetween(0, $assetTypes->count() - 1));
                $asset->save();

                $assetHistories = $factory->of(AssetHistory::class)->times($faker->numberBetween(2, 10))->make([
                    'asset_id' => $asset->id
                ]);

                foreach ($assetHistories as $assetHistory) {
                    $assetHistory->user_id = $users->get($faker->numberBetween(0, $users->count() - 1));
                    $assetHistory->lender_id = $admin;
                    $assetHistory->returner_id = $admin;
                    $assetHistory->save();
                }
            });
        }

        $_ENV['SEEDING'] = false;

        \DB::commit();
    }

}
