<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;


class DatabaseSeeder extends Seeder
{

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'login' => 'admin',
            'password' => app('hash')->make('123456'),
        ]);
    }
}
