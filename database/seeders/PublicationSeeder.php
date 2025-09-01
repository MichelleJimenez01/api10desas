<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publication;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publications = [
            [
                'title' => 'Inundación en zona norte',
                'type' => 'Desastre natural',
                'severity' => 'Alta',
                'location' => 'Zona Norte',
                'description' => 'Una inundación grave afecta varias viviendas en la zona norte.',
                'url_imagen' => 'https://example.com/inundacion.jpg',
                'date' => now(),
                'profile_id' => 1, // Ajusta según el ID real del perfil
            ],
            [
                'title' => 'Incendio forestal',
                'type' => 'Emergencia ambiental',
                'severity' => 'Baja',
                'location' => 'Bosque Central',
                'description' => 'El fuego se propaga rápidamente en el bosque central.',
                'url_imagen' => 'https://example.com/incendio.jpg',
                'date' => now()->subDay(),
                'profile_id' => 1, // Ajusta según el ID real del perfil
            ],
        ];

        foreach ($publications as $data) {
            Publication::updateOrCreate(
                ['title' => $data['title']], // Condición única
                $data
            );
        }
    }
}
