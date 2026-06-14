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
        $user = User::firstOrNew(['email' => 'ismaelyveskabore@gmail.com']);
        $user->fill([
            'firstname' => 'KABORE',
            'lastname' => 'Ismaël Yves',
            'password' => Hash::make('Prince2110@'),
            'role_id' => 1,
            'statut' => 1,
        ]);

        if (! $user->exists) {
            $user->uuid = (string) Str::uuid();
        }

        $user->save();
    }
}
