<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('planes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('planes')->insert([
            [
                'slug'                   => 'basico',
                'nombre'                 => 'Básico',
                'precio_mensual'         => 599.00,
                'max_vehiculos'          => 20,
                'max_fotos_por_vehiculo' => 8,
                'incluye_certificacion'  => false,
                'vehiculos_destacados'   => 0,
                'badge_premium'          => false,
                'features'               => json_encode([
                    'Hasta 20 vehículos activos',
                    '8 fotos por vehículo',
                    'Ficha pública con formulario de contacto',
                    'Leads registrados en portal',
                    'Estadísticas básicas',
                ]),
                'activo'                 => true,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'slug'                   => 'premium',
                'nombre'                 => 'Premium',
                'precio_mensual'         => 1299.00,
                'max_vehiculos'          => 60,
                'max_fotos_por_vehiculo' => 30,
                'incluye_certificacion'  => true,
                'vehiculos_destacados'   => 5,
                'badge_premium'          => true,
                'features'               => json_encode([
                    'Hasta 60 vehículos activos',
                    '30 fotos por vehículo',
                    '3 certificaciones incluidas por mes',
                    '5 vehículos destacados (pin arriba en resultados)',
                    'Badge Agencia Premium en listados',
                    'Estadísticas avanzadas',
                    'Soporte prioritario',
                ]),
                'activo'                 => true,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ]);
    }
}
