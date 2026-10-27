<?php

namespace Database\Seeders;

use App\Models\AdType;
use App\Models\CriticalPathStep;
use App\Models\ExpenseCategory;
use App\Models\Loan;
use App\Models\Municipality;
use App\Models\Plan;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios ──
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@kontanos.com',
            'phone' => '',
            'role' => 'admin',
            'password' => Hash::make('kontanos2024'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Socia',
            'email' => 'socia@kontanos.com',
            'phone' => '',
            'role' => 'socia',
            'password' => Hash::make('kontanos2024'),
            'email_verified_at' => now(),
        ]);

        // ── Municipios ──
        $sfp = Municipality::create([
            'name' => 'San Francisco de la Paz',
            'department' => 'Olancho',
            'country' => 'Honduras',
        ]);

        // ── Pantallas LED ──
        Screen::create([
            'name' => 'Pantalla 1 — Centro',
            'code' => 'SFP-P01',
            'municipality_id' => $sfp->id,
            'address' => 'Centro de San Francisco de la Paz',
            'total_spaces' => 24,
            'spot_duration_seconds' => 10,
            'cycle_duration_seconds' => 240,
            'operating_hours' => '06:00-20:00',
            'investment_cost' => 155000.00,
            'book_value' => 155000.00,
            'useful_life_months' => 60,
            'monthly_depreciation' => 2583.33,
            'status' => 'activa',
            'notes' => 'Pantalla LED principal. Estructura metálica + instalación eléctrica incluida.',
        ]);

        Screen::create([
            'name' => 'Pantalla 2 — Entrada',
            'code' => 'SFP-P02',
            'municipality_id' => $sfp->id,
            'address' => 'Entrada principal de San Francisco de la Paz',
            'total_spaces' => 24,
            'spot_duration_seconds' => 10,
            'cycle_duration_seconds' => 240,
            'operating_hours' => '06:00-20:00',
            'investment_cost' => 155000.00,
            'book_value' => 155000.00,
            'useful_life_months' => 60,
            'monthly_depreciation' => 2583.33,
            'status' => 'activa',
            'notes' => 'Pantalla LED secundaria. Estructura metálica + instalación eléctrica incluida.',
        ]);

        // ── Tipos de anuncio (precios del Excel) ──
        $imagen = AdType::create([
            'name' => 'Imagen',
            'slug' => 'imagen',
            'price_per_spot' => 1500.00,
            'description' => 'Imagen estática publicitaria. Spot de 10 segundos.',
        ]);

        $arte = AdType::create([
            'name' => 'Arte Animado',
            'slug' => 'arte-animado',
            'price_per_spot' => 2000.00,
            'description' => 'Arte animado con movimiento. Spot de 10 segundos.',
        ]);

        $video = AdType::create([
            'name' => 'Video',
            'slug' => 'video',
            'price_per_spot' => 3000.00,
            'description' => 'Video publicitario con audio. Spot de 10 segundos.',
        ]);

        // ── Planes individuales ──
        Plan::create([
            'name' => 'Imagen — 1 Pantalla',
            'type' => 'individual',
            'ad_type_id' => $imagen->id,
            'screens_count' => 1,
            'discount_percent' => 0,
            'final_price' => 1500.00,
            'duration_months' => 1,
            'description' => '1 espacio de imagen en 1 pantalla por mes.',
        ]);

        Plan::create([
            'name' => 'Arte Animado — 1 Pantalla',
            'type' => 'individual',
            'ad_type_id' => $arte->id,
            'screens_count' => 1,
            'discount_percent' => 0,
            'final_price' => 2000.00,
            'duration_months' => 1,
            'description' => '1 espacio de arte animado en 1 pantalla por mes.',
        ]);

        Plan::create([
            'name' => 'Video — 1 Pantalla',
            'type' => 'individual',
            'ad_type_id' => $video->id,
            'screens_count' => 1,
            'discount_percent' => 0,
            'final_price' => 3000.00,
            'duration_months' => 1,
            'description' => '1 espacio de video en 1 pantalla por mes.',
        ]);

        // ── Planes combo (10% descuento, 2 pantallas) ──
        Plan::create([
            'name' => 'Combo Imagen — 2 Pantallas',
            'type' => 'combo',
            'ad_type_id' => $imagen->id,
            'screens_count' => 2,
            'discount_percent' => 10.00,
            'final_price' => 2700.00,
            'duration_months' => 1,
            'description' => '1 espacio en AMBAS pantallas. 10% de descuento. Ahorra L 300.',
        ]);

        Plan::create([
            'name' => 'Combo Arte Animado — 2 Pantallas',
            'type' => 'combo',
            'ad_type_id' => $arte->id,
            'screens_count' => 2,
            'discount_percent' => 10.00,
            'final_price' => 3600.00,
            'duration_months' => 1,
            'description' => '1 espacio en AMBAS pantallas. 10% de descuento. Ahorra L 400.',
        ]);

        Plan::create([
            'name' => 'Combo Video — 2 Pantallas',
            'type' => 'combo',
            'ad_type_id' => $video->id,
            'screens_count' => 2,
            'discount_percent' => 10.00,
            'final_price' => 5400.00,
            'duration_months' => 1,
            'description' => '1 espacio en AMBAS pantallas. 10% de descuento. Ahorra L 600.',
        ]);

        // ── Categorías de gastos ──
        ExpenseCategory::create(['name' => 'Energía eléctrica', 'icon' => 'bolt', 'is_fixed' => true]);
        ExpenseCategory::create(['name' => 'Alquiler de sitios', 'icon' => 'location_on', 'is_fixed' => true]);
        ExpenseCategory::create(['name' => 'Mantenimiento y repuestos', 'icon' => 'build', 'is_fixed' => false]);
        ExpenseCategory::create(['name' => 'Cuota de préstamo', 'icon' => 'account_balance', 'is_fixed' => true]);
        ExpenseCategory::create(['name' => 'Impuestos', 'icon' => 'receipt_long', 'is_fixed' => true]);
        ExpenseCategory::create(['name' => 'Seguro', 'icon' => 'shield', 'is_fixed' => true]);
        ExpenseCategory::create(['name' => 'Otros', 'icon' => 'more_horiz', 'is_fixed' => false]);

        // ── Préstamo Banco Atlántida ──
        Loan::create([
            'bank_name' => 'Banco Atlántida',
            'principal_amount' => 360000.00,
            'interest_rate' => 0.1602,
            'term_months' => 82,
            'grace_months' => 0,
            'monthly_payment' => 7250.00,
            'total_to_pay' => 594500.00,
            'total_interest' => 234500.00,
            'status' => 'activo',
            'notes' => 'Préstamo para compra de 2 pantallas LED + infraestructura. Sobrante para capital de trabajo: L 11,500.',
        ]);

        // ── Ruta Crítica ──
        $steps = [
            // Fase 1: Legal y Permisos
            ['order' => 1, 'phase' => 'Legal y Permisos', 'title' => 'Constitución de la empresa / sociedad', 'description' => 'Definir figura legal (empresa individual, sociedad). Registrar ante la Cámara de Comercio y SAR.', 'responsible' => 'Socios'],
            ['order' => 2, 'phase' => 'Legal y Permisos', 'title' => 'Obtener RTN comercial', 'description' => 'Tramitar el RTN de la empresa en el SAR para facturación.', 'responsible' => 'Socios'],
            ['order' => 3, 'phase' => 'Legal y Permisos', 'title' => 'Permiso de la Alcaldía', 'description' => 'Solicitar y pagar permiso de operación y uso de vía pública para pantallas LED. Costo: L 500.', 'responsible' => 'Socios'],
            ['order' => 4, 'phase' => 'Legal y Permisos', 'title' => 'Permiso de CONATEL (si aplica)', 'description' => 'Verificar si se necesita permiso de telecomunicaciones para contenido audiovisual en vía pública.', 'responsible' => 'Socios'],
            ['order' => 5, 'phase' => 'Legal y Permisos', 'title' => 'Contrato de sociedad entre socios', 'description' => 'Redactar y firmar contrato definiendo participación, roles, aportes y distribución de utilidades.', 'responsible' => 'Socios'],

            // Fase 2: Financiamiento
            ['order' => 6, 'phase' => 'Financiamiento', 'title' => 'Aprobación del préstamo — Banco Atlántida', 'description' => 'Gestionar préstamo de L 360,000. Plazo: 82 meses. Cuota: L 7,250/mes.', 'responsible' => 'Socios'],
            ['order' => 7, 'phase' => 'Financiamiento', 'title' => 'Desembolso del préstamo', 'description' => 'Recibir el desembolso y separar: L 348,500 para inversión + L 11,500 capital de trabajo.', 'responsible' => 'Socios'],
            ['order' => 8, 'phase' => 'Financiamiento', 'title' => 'Apertura de cuenta bancaria empresarial', 'description' => 'Abrir cuenta a nombre de la empresa para manejar ingresos y egresos.', 'responsible' => 'Socios'],

            // Fase 3: Infraestructura
            ['order' => 9, 'phase' => 'Infraestructura', 'title' => 'Compra de Pantalla 1 (L 155,000)', 'description' => 'Adquirir la primera pantalla LED. Verificar especificaciones, garantía y soporte técnico.', 'responsible' => 'Socios'],
            ['order' => 10, 'phase' => 'Infraestructura', 'title' => 'Compra de Pantalla 2 (L 155,000)', 'description' => 'Adquirir la segunda pantalla LED con las mismas especificaciones.', 'responsible' => 'Socios'],
            ['order' => 11, 'phase' => 'Infraestructura', 'title' => 'Estructuras metálicas y montaje (L 20,000)', 'description' => 'Fabricar e instalar las estructuras metálicas para ambas pantallas. Verificar resistencia a viento.', 'responsible' => 'Contratista'],
            ['order' => 12, 'phase' => 'Infraestructura', 'title' => 'Instalación eléctrica y protecciones (L 8,000)', 'description' => 'Instalación eléctrica dedicada para ambas pantallas con protecciones contra sobretensión y tierra.', 'responsible' => 'Electricista'],
            ['order' => 13, 'phase' => 'Infraestructura', 'title' => 'Cámaras de seguridad — 4 unidades (L 10,000)', 'description' => 'Instalar 4 cámaras con microSD para vigilar las pantallas. 2 por ubicación.', 'responsible' => 'Técnico'],
            ['order' => 14, 'phase' => 'Infraestructura', 'title' => 'Pruebas técnicas de pantallas', 'description' => 'Encender ambas pantallas, verificar brillo, resolución, conectividad y programación de contenido.', 'responsible' => 'Técnico'],

            // Fase 4: Operación
            ['order' => 15, 'phase' => 'Configuración Operativa', 'title' => 'Definir horarios y ciclos de reproducción', 'description' => 'Configurar: horario 6am-8pm, ciclo 240 seg (4 min), spot 10 seg = 24 espacios, 210 reproducciones/día.', 'responsible' => 'Admin'],
            ['order' => 16, 'phase' => 'Configuración Operativa', 'title' => 'Configurar software de gestión (este sistema)', 'description' => 'Instalar y configurar este sistema web. Cargar pantallas, planes, precios y categorías de gastos.', 'responsible' => 'Admin'],
            ['order' => 17, 'phase' => 'Configuración Operativa', 'title' => 'Crear material de ventas', 'description' => 'Diseñar catálogo de precios, propuesta comercial, y material promocional para vender espacios.', 'responsible' => 'Socios'],
            ['order' => 18, 'phase' => 'Configuración Operativa', 'title' => 'Definir políticas de cobranza', 'description' => 'Establecer fechas de corte, penalizaciones por mora, descuentos por pronto pago, y política de combos.', 'responsible' => 'Socios'],

            // Fase 5: Ventas
            ['order' => 19, 'phase' => 'Primeras Ventas', 'title' => 'Identificar prospectos en San Francisco de la Paz', 'description' => 'Listar negocios locales: farmacias, tiendas, restaurantes, servicios profesionales, instituciones.', 'responsible' => 'Ventas'],
            ['order' => 20, 'phase' => 'Primeras Ventas', 'title' => 'Conseguir primeros 4 clientes por pantalla', 'description' => 'Meta mínima para punto de equilibrio: 8 espacios totales (~4 por pantalla, ~L 15,300/mes).', 'responsible' => 'Ventas'],
            ['order' => 21, 'phase' => 'Primeras Ventas', 'title' => 'Firmar contratos y recibir artes', 'description' => 'Formalizar contratos, recibir material publicitario y programar contenido en las pantallas.', 'responsible' => 'Admin'],
            ['order' => 22, 'phase' => 'Primeras Ventas', 'title' => 'Alcanzar punto de equilibrio', 'description' => 'Necesitas vender ~8 espacios totales (L 14,250/mes en gastos). Precio promedio ~L 1,909/espacio.', 'responsible' => 'Ventas'],

            // Fase 6: Crecimiento
            ['order' => 23, 'phase' => 'Crecimiento', 'title' => 'Alcanzar escenario "Meta" (11 clientes/pantalla)', 'description' => 'Ingresos proyectados: L 42,000/mes. Ganancia: L 27,750/mes. Ocupación: ~46%.', 'responsible' => 'Ventas'],
            ['order' => 24, 'phase' => 'Crecimiento', 'title' => 'Ofrecer combos en 2 pantallas', 'description' => 'Promover el plan combo con 10% de descuento para clientes que quieran anunciarse en ambas pantallas.', 'responsible' => 'Ventas'],
            ['order' => 25, 'phase' => 'Crecimiento', 'title' => 'Evaluar expansión a otro municipio', 'description' => 'Cuando la ocupación supere el 60%, evaluar mercado en municipios cercanos para nueva pantalla.', 'responsible' => 'Socios'],
        ];

        foreach ($steps as $step) {
            CriticalPathStep::create($step);
        }
    }
}
