<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Super Administrateur','Administrateur','Client'];
        foreach ($roles as $role) {
            Role::create([
                'label'=>$role
            ]);
        }
    }
}
