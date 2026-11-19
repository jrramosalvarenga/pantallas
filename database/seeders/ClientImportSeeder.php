<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Municipality;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class ClientImportSeeder extends Seeder
{
    public function run(): void
    {
        $sfpId = Municipality::where('name', 'San Francisco de la Paz')->value('id') ?? 1;
        $planId = Plan::first()->id;

        $data = [
            ['Patepluma', 1000, false, 'Descuento temporal'],
            ['Domus', 1000, false, 'Descuento temporal'],
            ['Agrosur', 1500, true, null],
            ['Mejias', 1500, true, null],
            ['Agropecuaria Melisa', 1500, false, null],
            ['Bloquera La Quinta', 1500, false, null],
            ['Pinturas Americanas', 1500, false, 'Acordado'],
            ['Lotificadora Villa Rosa', 1500, false, null],
            ['Tornicenter', 1500, false, null],
            ['Bodega Jose Carlos', 0, false, 'Gratis por alianza'],
            ['Junta de Agua', 1500, false, 'Acordado'],
            ['Rafa Sarmiento', 1500, false, 'Acordado'],
            ['Repuestos Premiun', 1500, false, null],
        ];

        foreach ($data as $d) {
            if (Client::where('business_name', $d[0])->exists()) {
                continue;
            }

            $c = Client::create([
                'business_name' => $d[0],
                'contact_name' => $d[0],
                'municipality_id' => $sfpId,
                'status' => 'activo',
                'notes' => $d[3],
            ]);

            $ct = Contract::create([
                'contract_number' => Contract::generateContractNumber(),
                'client_id' => $c->id,
                'plan_id' => $planId,
                'start_date' => '2026-11-01',
                'end_date' => '2026-12-01',
                'monthly_amount' => $d[1],
                'total_amount' => $d[1],
                'payment_frequency' => 'mensual',
                'status' => 'activo',
                'notes' => $d[3],
            ]);

            if ($d[1] > 0) {
                Payment::create([
                    'contract_id' => $ct->id,
                    'client_id' => $c->id,
                    'amount' => $d[1],
                    'due_date' => '2026-11-19',
                    'status' => $d[2] ? 'pagado' : 'pendiente',
                    'amount_paid' => $d[2] ? $d[1] : 0,
                ]);
            }

            $this->command->info("OK: {$d[0]}");
        }

        $this->command->info('Total clientes: ' . Client::count());
    }
}
