<?php

namespace Database\Seeders;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            try {
                User::create([
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => bcrypt('123'),
                ]);
            } catch (\Exception $e) {
                \Log::error("Error creating user: {$e->getMessage()}");
            }
        }
    }
}
