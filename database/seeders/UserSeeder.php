<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $faker = \Faker\Factory::create();

            for ($u = 1; $u <= 10; $u++) {
                DB::beginTransaction();

                $user = new User();
                $user->name = $faker->name;
                $user->email = $faker->unique()->freeEmail;
                $user->save();

                $randomactivityadd = $faker->numberBetween(1, 3);
                for ($ra = 1; $ra <= $randomactivityadd; $ra++) {
                    $activityadd = new ActivityLog();
                    $activityadd->user_id = $user->id;
                    $activityadd->points = 20;
                    $activityadd->activity = $faker->numberBetween(1, 4);
                    $activityadd->save();
                }

                DB::commit();
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
        }
    }
}
