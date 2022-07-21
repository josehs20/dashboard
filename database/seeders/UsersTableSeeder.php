<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            
            'name' => 'Admin Admin',
            'email' => 'admin@alltechsistemas.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'created_at' => now(),
            'updated_at' => now(),
            'perfil'    => 'administrador'
        ]);

        Empresa::create([
            'nome' => 'testeG', 
            'pasta' => '28825657000107', 
            'ultima_sincronizacao' => null, 
            'sincronizar' => true
        ]);
    }
}
