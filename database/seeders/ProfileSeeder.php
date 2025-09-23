<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener usuarios y roles existentes
        $users = User::all();
        $roles = Role::all();

        // 2. Verificar que existan ambos antes de continuar
        if ($users->isEmpty() || $roles->isEmpty()) {
            $this->command->warn('Asegúrate de ejecutar UserSeeder y RoleSeeder antes de ProfileSeeder.');
            return;
        }

        // 3. Definir algunos valores por defecto para la foto y contraseña
        $defaultPassword = bcrypt('password123');
        $defaultPhoto = 'default.jpg';

        // 4. Crear un perfil para cada usuario
        foreach ($users as $user) {
            // Evitar duplicados: solo crear si el usuario no tiene perfil
            if (!$user->profile()->exists()) {
                Profile::create([
                    'user_id'  => $user->id,
                    'role_id'  => $roles->random()->id,
                    'password' => $defaultPassword,
                    'photo'    => $defaultPhoto,    
                ]);
            }
        }

<<<<<<< Updated upstream
        // 5. Mensaje de confirmación
        $count = Profile::count();
        $this->command->info("✅ Se crearon {$count} perfiles exitosamente.");
=======
        Profile::create([
            'photo' => 'Nueva foto subida 3',
            'user_id' => $users[2]->id,
            'role_id' => $roles[2]->id,
        
            
        ]);
>>>>>>> Stashed changes
    }
}