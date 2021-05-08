<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            ['title' => 'Деньги'],
            ['title' => 'Семья'],
            ['title' => 'Красота и Здоровье'],
            ['title' => 'Спорт'],
            ['title' => 'Успех'],
            ['title' => 'Отношения'],
            ['title' => 'Профессиональные навыки'],
            ['title' => 'Бизнес'],
            ['title' => 'Счастье'],
            ['title' => 'Духовное развитие'],
        ]);
    }
}
