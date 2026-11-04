<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Prospect;
use Illuminate\Database\Seeder;

class ProspectSeeder extends Seeder
{
    public function run(): void
    {
        $municipalities = [];

        foreach ($this->prospects() as $data) {
            $municipalityId = null;
            if (! empty($data['municipality'])) {
                if (! isset($municipalities[$data['municipality']])) {
                    $mun = Municipality::firstOrCreate(
                        ['name' => $data['municipality']],
                        ['department' => $data['department'] ?? 'Olancho', 'country' => 'Honduras']
                    );
                    $municipalities[$data['municipality']] = $mun->id;
                }
                $municipalityId = $municipalities[$data['municipality']];
            }

            Prospect::create([
                'business_name' => $data['business_name'],
                'category' => $data['category'] ?? null,
                'municipality_id' => $municipalityId,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'origin' => $data['origin'] ?? null,
                'data_source' => $data['data_source'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'por_visitar',
            ]);
        }
    }

    /** @return array<int, array<string, string|null>> */
    private function prospects(): array
    {
        return [
            ['business_name' => 'Pizza Hut', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => 'Juticalpa centro', 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Pollo Norteño', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Burger King', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Espresso Americano', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Pollolandia', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Church\'s Chicken', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Little Caesars', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Baleadas Express', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Asados El Corral', 'category' => 'Comida', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Hospital Catarino Rivas (Privado)', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Clínica Bendaña', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Farmacia Siman', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Farmacia Kielsa', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Óptica Matamoros', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Clínica Dental Sonrisa', 'category' => 'Salud', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Banco Atlántida', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Banco Occidente', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Banco de los Trabajadores (Bantrab)', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Cooperativa Sagrada Familia', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Cooperativa Elga', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Ficohsa', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'BAC Credomatic', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Banpaís', 'category' => 'Finanzas', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Ferretería La Popular', 'category' => 'Construcción', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Ferretería El Maestro', 'category' => 'Construcción', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Construmart', 'category' => 'Construcción', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Distribuidora de Materiales El Olancho', 'category' => 'Construcción', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Agrocomercial El Ganadero', 'category' => 'Agro', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Veterinaria El Rancho', 'category' => 'Agro', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Agropecuaria La Hacienda', 'category' => 'Agro', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Universidad Nacional de Agricultura (UNA)', 'category' => 'Educación', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'UNAH Olancho', 'category' => 'Educación', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'UTH Olancho', 'category' => 'Educación', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Instituto San Jorge', 'category' => 'Educación', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Escuela Internacional', 'category' => 'Educación', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Honda Motos Olancho', 'category' => 'Motos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Yamaha Motos', 'category' => 'Motos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Motociclo', 'category' => 'Motos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Italika Motos', 'category' => 'Motos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Taller de Motos Hernández', 'category' => 'Motos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Tienda Carrión', 'category' => 'Comercio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Tiendas Diunsa', 'category' => 'Comercio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Lady Lee', 'category' => 'Comercio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Almacenes El Record', 'category' => 'Comercio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Jetstereo', 'category' => 'Comercio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gasolinera Puma (Salida a Tegucigalpa)', 'category' => 'Gasolinera', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => 'Salida a Tegucigalpa', 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gasolinera UNO', 'category' => 'Gasolinera', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gasolinera Terra', 'category' => 'Gasolinera', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gasolinera Sol', 'category' => 'Gasolinera', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Floristería Detalles', 'category' => 'Floristería', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Floristería Jardín del Edén', 'category' => 'Floristería', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Hotel La Muralla', 'category' => 'Hospedaje', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Hotel Olancho', 'category' => 'Hospedaje', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Hotel Real de Oriente', 'category' => 'Hospedaje', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gimnasio Power Gym', 'category' => 'Gimnasio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Gimnasio Iron Fitness', 'category' => 'Gimnasio', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Claro Honduras', 'category' => 'Celulares', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Tigo Honduras', 'category' => 'Celulares', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Tienda Celulares y Más', 'category' => 'Celulares', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Repuestos Olancho', 'category' => 'Repuestos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Auto Repuestos La Economía', 'category' => 'Repuestos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Repuestos Génesis', 'category' => 'Repuestos', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Plaza Olancho (Mall)', 'category' => 'Plaza Comercial', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Centro Comercial La Colonia', 'category' => 'Plaza Comercial', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Plaza San Cristóbal', 'category' => 'Plaza Comercial', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Candidatos Alcaldía Municipal', 'category' => 'Político', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => 'Temporada electoral'],
            ['business_name' => 'Candidatos Diputados Olancho', 'category' => 'Político', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Olancho', 'data_source' => 'Local', 'notes' => 'Temporada electoral'],
            ['business_name' => 'Bufete Jurídico Salgado', 'category' => 'Abogado', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Abogados & Notarios Paz', 'category' => 'Abogado', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Bienes Raíces Olancho', 'category' => 'Bienes Raíces', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Inversiones La Colonial', 'category' => 'Bienes Raíces', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Supermercado La Colonia', 'category' => 'Supermercado', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Supermercado La Económica', 'category' => 'Supermercado', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Walmart / MaxiDespensa', 'category' => 'Supermercado', 'municipality' => 'Juticalpa', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Juticalpa', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Pollo Norteño Catacamas', 'category' => 'Comida', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Espresso Americano Catacamas', 'category' => 'Comida', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Farmacia Siman Catacamas', 'category' => 'Salud', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Banco Atlántida Catacamas', 'category' => 'Finanzas', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Ficohsa Catacamas', 'category' => 'Finanzas', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Gasolinera UNO Catacamas', 'category' => 'Gasolinera', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Hotel Papabeto', 'category' => 'Hospedaje', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Supermercado La Colonia Catacamas', 'category' => 'Supermercado', 'municipality' => 'Catacamas', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'Catacamas', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Pollo Norteño San Francisco', 'category' => 'Comida', 'municipality' => 'San Francisco de la Paz', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'San Francisco de la Paz', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Cooperativa Sagrada Familia SFP', 'category' => 'Finanzas', 'municipality' => 'San Francisco de la Paz', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'San Francisco de la Paz', 'data_source' => 'Google Maps', 'notes' => null],
            ['business_name' => 'Farmacia San Francisco', 'category' => 'Salud', 'municipality' => 'San Francisco de la Paz', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'San Francisco de la Paz', 'data_source' => 'Local', 'notes' => null],
            ['business_name' => 'Ferretería San Francisco', 'category' => 'Construcción', 'municipality' => 'San Francisco de la Paz', 'department' => 'Olancho', 'phone' => null, 'address' => null, 'origin' => 'San Francisco de la Paz', 'data_source' => 'Local', 'notes' => null],
        ];
    }
}
