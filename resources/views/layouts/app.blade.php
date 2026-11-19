<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Kontanos Publicidad</title>
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
                        },
                        accent: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a',
                            300: '#fcd34d', 400: '#fbbf24', 500: '#f59e0b',
                            600: '#d97706', 700: '#b45309', 800: '#92400e', 900: '#78350f',
                        },
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-size: 20px; vertical-align: middle; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50/80" x-data="{ sidebarOpen: true, mobileOpen: false }" x-cloak>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js" defer></script>

    {{-- Overlay móvil --}}
    <div x-show="mobileOpen" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="mobileOpen = false"></div>

    {{-- Sidebar --}}
    <aside class="fixed top-0 left-0 z-50 h-full bg-gradient-to-b from-brand-700 via-brand-800 to-brand-900 text-white transition-all duration-300 flex flex-col"
           :class="[sidebarOpen ? 'w-64' : 'w-20', mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="w-10 h-10 bg-accent-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <span class="material-symbols-outlined text-white" style="font-size:24px">live_tv</span>
            </div>
            <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                <h1 class="text-base font-bold leading-tight">Kontanos</h1>
                <p class="text-[11px] text-brand-200 font-medium">Publicidad LED</p>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>

            @php $userRole = auth()->user()->role; @endphp

            <p x-show="sidebarOpen" class="px-4 pt-4 pb-1 text-[10px] font-semibold text-brand-300 uppercase tracking-wider">Comercial</p>

            @php
            $navItems = [
                ['route' => 'prospects', 'icon' => 'person_search', 'label' => 'Prospectos', 'roles' => ['admin', 'vendedor']],
                ['route' => 'clients', 'icon' => 'groups', 'label' => 'Clientes', 'roles' => ['admin', 'socia', 'operador']],
                ['route' => 'contracts', 'icon' => 'description', 'label' => 'Contratos', 'roles' => ['admin', 'socia', 'operador']],
                ['route' => 'payments', 'icon' => 'payments', 'label' => 'Pagos', 'roles' => ['admin', 'socia', 'operador']],
                ['route' => 'plans', 'icon' => 'loyalty', 'label' => 'Planes', 'roles' => ['admin', 'socia', 'operador']],
            ];
            @endphp
            @foreach($navItems as $item)
                @if(in_array($userRole, $item['roles']))
                <a href="{{ route($item['route'].'.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route'].'.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                    <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                    <span x-show="sidebarOpen">{{ $item['label'] }}</span>
                </a>
                @endif
            @endforeach

            @if(in_array($userRole, ['admin', 'socia', 'operador']))
            <p x-show="sidebarOpen" class="px-4 pt-4 pb-1 text-[10px] font-semibold text-brand-300 uppercase tracking-wider">Operaciones</p>

            @php
            $opsItems = [
                ['route' => 'screens', 'icon' => 'tv', 'label' => 'Pantallas'],
                ['route' => 'media', 'icon' => 'perm_media', 'label' => 'Medios'],
                ['route' => 'maintenance', 'icon' => 'build', 'label' => 'Mantenimiento'],
            ];
            @endphp
            @foreach($opsItems as $item)
            <a href="{{ route($item['route'].'.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route'].'.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span x-show="sidebarOpen">{{ $item['label'] }}</span>
            </a>
            @endforeach

            <p x-show="sidebarOpen" class="px-4 pt-4 pb-1 text-[10px] font-semibold text-brand-300 uppercase tracking-wider">Finanzas</p>

            @php
            $finItems = [
                ['route' => 'finances', 'icon' => 'account_balance', 'label' => 'Resumen'],
                ['route' => 'expenses', 'icon' => 'trending_down', 'label' => 'Egresos'],
                ['route' => 'incomes', 'icon' => 'trending_up', 'label' => 'Ingresos'],
            ];
            @endphp
            @foreach($finItems as $item)
            <a href="{{ route($item['route'].'.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route'].'.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span x-show="sidebarOpen">{{ $item['label'] }}</span>
            </a>
            @endforeach

            <p x-show="sidebarOpen" class="px-4 pt-4 pb-1 text-[10px] font-semibold text-brand-300 uppercase tracking-wider">Gestión</p>

            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">task_alt</span>
                <span x-show="sidebarOpen">Tareas</span>
            </a>

            <a href="{{ route('events.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('events.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">celebration</span>
                <span x-show="sidebarOpen">Eventos</span>
            </a>

            <a href="{{ route('calendar.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('calendar.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">calendar_month</span>
                <span x-show="sidebarOpen">Calendario</span>
            </a>

            <a href="{{ route('accounting.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('accounting.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">account_balance_wallet</span>
                <span x-show="sidebarOpen">Contabilidad</span>
            </a>

            <a href="{{ route('critical-path.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('critical-path.*') ? 'bg-white/20 text-white shadow-sm' : 'text-brand-100 hover:bg-white/10' }}">
                <span class="material-symbols-outlined">route</span>
                <span x-show="sidebarOpen">Ruta Crítica</span>
            </a>
            @endif
        </nav>

        {{-- Footer sidebar --}}
        <div class="border-t border-white/10 p-3">
            <button @click="sidebarOpen = !sidebarOpen" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-brand-200 hover:bg-white/10 w-full hidden lg:flex">
                <span class="material-symbols-outlined" x-text="sidebarOpen ? 'chevron_left' : 'chevron_right'"></span>
                <span x-show="sidebarOpen">Colapsar</span>
            </button>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-200/60">
            <div class="flex items-center justify-between px-4 lg:px-8 h-16">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined" style="font-size:28px">menu</span>
                    </button>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-gray-400">@yield('page-subtitle', 'Kontanos Publicidad')</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                            <div class="w-8 h-8 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center font-semibold text-xs">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                            </div>
                            <span class="hidden sm:inline font-medium">{{ Auth::user()->name ?? '' }}</span>
                            <span class="material-symbols-outlined text-gray-400" style="font-size:16px">expand_more</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                                    <span class="material-symbols-outlined" style="font-size:18px">logout</span>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="p-4 lg:p-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                    {{ session('success') }}
                    <button @click="show = false" class="ml-auto"><span class="material-symbols-outlined" style="font-size:16px">close</span></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2" x-data="{ show: true }" x-show="show">
                    <span class="material-symbols-outlined" style="font-size:18px">error</span>
                    {{ session('error') }}
                    <button @click="show = false" class="ml-auto"><span class="material-symbols-outlined" style="font-size:16px">close</span></button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    @stack('scripts')
</body>
</html>
