<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('jobs')->insert([
                'user_id' => 1,
                'title' => $faker->jobTitle,
                'description' => $faker->paragraph,
                'address' => $faker->address,
                'email' => $faker->email,
                'website' => $faker->url,
                'phone' => $faker->phoneNumber,
                'company' => $faker->company,
                'category' => $faker->jobTitle,
                'country' => $faker->country,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => $faker->postcode,
                'salary' => $faker->randomFloat(2, 1000, 10000),
                'type' => $faker->randomElement(['full-time', 'part-time']),
                'deadline' => $faker->dateTimeBetween('+0 days', '+30 days'),
                'job_status' => $faker->randomElement(['published', 'draft']),
                'published_at' => $faker->dateTimeBetween('+0 days', '+30 days')
            ]);
        }
    }
}
