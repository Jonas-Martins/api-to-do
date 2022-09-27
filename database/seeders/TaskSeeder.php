<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'title' => 'Minha Task de exemplo',
            'description' => 'Teste teste...',
            'due-date' => '2022-12-12- 00:00:00',
            'user_id' => 1,
            'category_id' => 1
        ]);
    }
}
