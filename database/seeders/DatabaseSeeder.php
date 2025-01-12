<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Admin::factory(1)->create();

        Admin::create([
            'name' => 'Admin',
            'email' => 'mbtransportcooperative@gmail.com',
            'password' => Hash::make('Qm88lfg;'),
        ]);
    }
}
