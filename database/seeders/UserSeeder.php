<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname'=>'MIHIN',
            'lastname'=>'Hugues',
            'email'=>'admin@gmail.com',
            'password'=> Hash::make('12345678'),
            'role_id'=>1,
            'uuid'=>Str::uuid(),
            'statut'=> 1
        ]);
    }
}
