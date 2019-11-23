<?php

use App\Task;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Task::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few tasks in our database:
        for ($i = 0; $i < 100; $i++) {
            Task::create([
                'text'      => $faker->sentence,
                'user_id'   => random_int(1, 10),
            ]);
        }
    }
}
