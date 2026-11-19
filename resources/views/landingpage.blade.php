<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Publiktec - Publicidad LED en Olancho</title>
    <meta name="description" content="Publicidad en pantallas LED gigantes en Catacamas y Juticalpa, Olancho. Tu negocio visible las 24 horas del dia.">
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
                            600: '#d97706', 700: '#b45309',
                        },
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
        .hero-gradient {
            background: linear-gradient(135deg, #312e81 0%, #4338ca 40%, #4f46e5 70%, #6366f1 100%);
        }
        .float-animation { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(37, 211, 102, 0.3); }
            50% { box-shadow: 0 0 40px rgba(37, 211, 102, 0.6); }
        }
    </style>
</head>
<body class="bg-white text-gray-800">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/promo/publiktec-logo.png') }}" alt="PubliKTec" class="h-10">
            </div>
            <a href="https://wa.me/50488149290?text=Hola%2C%20me%20interesa%20anunciar%20mi%20negocio%20en%20sus%20pantallas%20LED"
               class="hidden sm:inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-green-500/30 hover:shadow-green-500/50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Escríbenos
            </a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative min-h-[90vh] flex items-center pt-16 overflow-hidden">
        {{-- Video de fondo --}}
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('storage/promo/times-square-bg.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/60 to-black/80"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 lg:py-24 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="{{ asset('storage/promo/publiktec-logo.png') }}" alt="PubliKTec" class="h-16 sm:h-20 mb-6 drop-shadow-2xl">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        Tu negocio en <span class="text-accent-400">pantallas gigantes</span> LED
                    </h2>
                    <p class="text-lg text-brand-200 mb-8 max-w-lg">
                        Anuncia en las pantallas LED mas grandes de Olancho. Tu marca visible las 24 horas, los 7 dias de la semana.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="https://wa.me/50488149290?text=Hola%2C%20me%20interesa%20anunciar%20mi%20negocio%20en%20sus%20pantallas%20LED"
                           class="inline-flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-2xl text-lg font-bold transition-all shadow-2xl shadow-green-500/30 hover:shadow-green-500/50 pulse-glow">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Quiero anunciarme
                        </a>
                        <a href="#servicios" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white px-6 py-4 rounded-2xl text-lg font-semibold transition-all backdrop-blur-sm">
                            Ver mas
                            <span class="material-symbols-outlined">arrow_downward</span>
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 float-animation">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white/10 rounded-2xl p-5 text-center">
                                    <p class="text-4xl font-black text-accent-400">2</p>
                                    <p class="text-sm text-brand-200 mt-1">Pantallas LED</p>
                                </div>
                                <div class="bg-white/10 rounded-2xl p-5 text-center">
                                    <p class="text-4xl font-black text-accent-400">168</p>
                                    <p class="text-sm text-brand-200 mt-1">Reproducciones/dia</p>
                                </div>
                                <div class="bg-white/10 rounded-2xl p-5 text-center">
                                    <p class="text-4xl font-black text-accent-400">24/7</p>
                                    <p class="text-sm text-brand-200 mt-1">Visibilidad</p>
                                </div>
                                <div class="bg-white/10 rounded-2xl p-5 text-center">
                                    <p class="text-4xl font-black text-accent-400">5 min</p>
                                    <p class="text-sm text-brand-200 mt-1">Ciclos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Beneficios --}}
    <section id="servicios" class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-brand-100 text-brand-600 px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                    <span class="material-symbols-outlined" style="font-size:16px">star</span>
                    Por que elegirnos
                </span>
                <h3 class="text-3xl sm:text-4xl font-bold text-gray-800">La publicidad mas <span class="text-brand-600">impactante</span> de Olancho</h3>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Nuestras pantallas LED gigantes garantizan que tu negocio sea visto por miles de personas cada dia.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-brand-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-brand-600" style="font-size:28px">visibility</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Alta visibilidad</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Tu anuncio se reproduce 168 veces al dia en ubicaciones estrategicas de alto trafico vehicular y peatonal.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-accent-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-accent-600" style="font-size:28px">payments</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Precio accesible</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Desde <span class="font-bold text-brand-600">L 1,500/mes</span> por anuncio en ambas pantallas. La mejor relacion costo-beneficio.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-emerald-600" style="font-size:28px">schedule</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">24 horas, 7 dias</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Tus anuncios se muestran sin descanso, de dia y de noche. Maximo impacto todo el tiempo.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-purple-600" style="font-size:28px">tv</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Pantallas gigantes</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Presencia en San Francisco de la Paz, Olancho. Ubicacion estrategica de alto trafico.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-rose-600" style="font-size:28px">palette</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Diseno incluido</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Creamos tu anuncio visual de forma profesional sin costo adicional. Solo envias tu informacion y nosotros hacemos el resto.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-sky-600" style="font-size:28px">update</span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Cambios ilimitados</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Puedes actualizar tu anuncio cuando quieras: promociones, ofertas especiales, eventos. Sin costos extra.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Galeria / Promo --}}
    <section class="py-20 lg:py-28">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-accent-100 text-accent-700 px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                    <span class="material-symbols-outlined" style="font-size:16px">photo_library</span>
                    Nuestras pantallas
                </span>
                <h3 class="text-3xl sm:text-4xl font-bold text-gray-800">Asi se ve tu publicidad</h3>
            </div>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach(['publiktec-promo.jpg', 'publiktec-promo-1.jpg', 'publiktec-promo-2.jpg'] as $img)
                <div class="relative group overflow-hidden rounded-2xl shadow-lg">
                    <img src="{{ asset('storage/promo/' . $img) }}" alt="Pantalla LED Publiktec" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Precios --}}
    <section class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
                    <span class="material-symbols-outlined" style="font-size:16px">sell</span>
                    Nuestro plan
                </span>
                <h3 class="text-3xl sm:text-4xl font-bold text-gray-800">Invierte en tu marca</h3>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden max-w-lg mx-auto">
                <div class="hero-gradient p-8 text-center">
                    <p class="text-brand-200 text-sm font-medium mb-2">Anuncio en ambas pantallas</p>
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-5xl font-black text-white">L 1,500</span>
                        <span class="text-brand-200 text-lg">/mes</span>
                    </div>
                </div>
                <div class="p-8">
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600">Presencia en <strong>2 pantallas LED</strong></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600"><strong>168 reproducciones</strong> diarias</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600">Ciclos de <strong>5 minutos</strong></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600"><strong>Diseno profesional</strong> incluido</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600">Cambios <strong>ilimitados</strong> de arte</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:22px">check_circle</span>
                            <span class="text-sm text-gray-600">Visibilidad <strong>24/7</strong></span>
                        </li>
                    </ul>
                    <a href="https://wa.me/50488149290?text=Hola%2C%20me%20interesa%20el%20plan%20de%20publicidad%20LED%20de%20L%201%2C500%2Fmes"
                       class="mt-8 w-full inline-flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white px-6 py-4 rounded-2xl text-lg font-bold transition-all shadow-lg shadow-green-500/30 hover:shadow-green-500/50">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Contratar ahora
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Final --}}
    <section class="hero-gradient py-20 lg:py-28 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 w-72 h-72 bg-accent-400 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4">
                Se parte de los negocios que toda la ciudad ve
            </h3>
            <p class="text-lg text-brand-200 mb-10 max-w-xl mx-auto">
                No dejes pasar la oportunidad de mostrar tu negocio en las pantallas mas grandes de Olancho. Escribenos y comienza hoy.
            </p>
            <a href="https://wa.me/50488149290?text=Hola%2C%20quiero%20informacion%20sobre%20publicidad%20en%20pantallas%20LED"
               class="inline-flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white px-10 py-5 rounded-2xl text-xl font-bold transition-all shadow-2xl shadow-green-500/30 hover:shadow-green-500/50 pulse-glow">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Escribenos por WhatsApp
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid sm:grid-cols-3 gap-8">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('storage/promo/publiktec-logo.png') }}" alt="PubliKTec" class="h-12 brightness-0 invert opacity-80">
                    </div>
                    <p class="text-sm leading-relaxed">Se parte de los negocios que toda la ciudad ve.</p>
                </div>
                <div>
                    <h5 class="text-white font-semibold mb-4">Contacto</h5>
                    <div class="space-y-3 text-sm">
                        <a href="https://wa.me/50488149290" class="flex items-center gap-2 hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            8814-9290
                        </a>
                        <a href="mailto:kontanospublicidad@zohomail.com" class="flex items-center gap-2 hover:text-white transition-colors">
                            <span class="material-symbols-outlined" style="font-size:16px">mail</span>
                            kontanospublicidad@zohomail.com
                        </a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-semibold mb-4">Ubicaciones</h5>
                    <div class="space-y-2 text-sm">
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size:16px">location_on</span>
                            San Francisco de la Paz, Olancho
                        </p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Publiktec. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    {{-- Boton flotante WhatsApp --}}
    <a href="https://wa.me/50488149290?text=Hola%2C%20me%20interesa%20anunciar%20mi%20negocio%20en%20sus%20pantallas%20LED"
       class="fixed bottom-6 right-6 z-50 w-16 h-16 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center shadow-2xl shadow-green-500/40 transition-all hover:scale-110 pulse-glow"
       title="Escríbenos por WhatsApp">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

</body>
</html>
