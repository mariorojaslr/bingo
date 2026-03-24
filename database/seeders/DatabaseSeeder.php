<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * 1. CREACIÓN DEL USUARIO OWNER/OMNIPOTENTE
         * Administrador Central del Sistema SaaS
         */
        $user = User::updateOrCreate(
            ['email' => 'mario.rojas.coach@gmail.com'],
            [
                'name' => 'Mario Rojas (Owner)',
                'password' => Hash::make('Rojas*250007'),
            ]
        );

        /**
         * 2. CREACIÓN DEL ROL "OWNER"
         * Conectando a la tabla 'roles' si existe.
         */
        if (Schema::hasTable('roles')) {
            $role = DB::table('roles')->where('name', 'owner')->first();
            
            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'owner',
                    'description' => 'Propietario Supremo de la Plataforma (Multi-SaaS)',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            if (Schema::hasTable('role_user')) {
                DB::table('role_user')->updateOrInsert([
                    'user_id' => $user->id,
                    'role_id' => $roleId
                ]);
            }
        }

        $this->command->info('✅ Cuenta SuperAdmin (Owner) generada con éxito para mario.rojas.coach@gmail.com');
    }
}
