{{-- ============================================
     LANDING PAGE: TaskFlow Pro
     Primary CTA: Comenzar mi prueba gratis de 14 días
     ============================================ --}}

<x-app-layout>
    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center max-w-4xl mx-auto">
                {{-- H1: Outcome + Pain Elimination --}}
                <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight">
                    Automatiza tus reportes de status y recupera 10 horas cada semana
                </h1>

                {{-- H2: What it is + Who it's for + Unique mechanism --}}
                <p class="mt-6 text-xl md:text-2xl text-indigo-200 max-w-3xl mx-auto">
                    TaskFlow Pro genera reportes de progreso automáticos, integra Slack y Jira, y te entrega un dashboard en tiempo real — para que dejes de perder horas en reuniones de standup que podrían ser un simple mensaje.
                </p>

                {{-- CTA Button --}}
                <div class="mt-10">
                    <a href="#cta" class="inline-flex items-center px-8 py-4 bg-emerald-500 text-white font-semibold text-lg rounded-lg hover:bg-emerald-600 transition-colors shadow-lg">
                        Comenzar mi prueba gratis de 14 días
                    </a>
                </div>

                {{-- Trust Microcopy --}}
                <p class="mt-4 text-sm text-indigo-300">
                    No requiere tarjeta de crédito · Setup en 2 minutos · Cancela cuando quieras
                </p>
            </div>
        </div>

        {{-- Hero Visual --}}
        <div class="max-w-5xl mx-auto px-4 pb-16">
            <div class="rounded-xl overflow-hidden shadow-2xl border border-white/10">
                <img src="{{ asset('images/hero-screenshot.png') }}" alt="TaskFlow Pro dashboard" class="w-full">
            </div>
        </div>
    </section>

    {{-- SOCIAL PROOF --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                Ahorra un promedio de 8 horas por semana por equipo
            </p>
            <div class="mt-6 flex justify-center items-center gap-8 opacity-50">
                {{-- Logo placeholders --}}
            </div>
        </div>
    </section>

    {{-- BENEFITS / TRANSFORMATION --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Deja de perder tiempo en reuniones que podrían ser un mensaje
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                {{-- Benefit 1 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-chart-bar class="w-6 h-6 text-indigo-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Recupera 10+ horas semanales por equipo</h3>
                    <p class="mt-2 text-gray-600">TaskFlow genera reportes de progreso automáticos desde Jira y Slack, eliminando las reuniones de standup y status updates que consumen tu tiempo.</p>
                </div>

                {{-- Benefit 2 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-bolt class="w-6 h-6 text-indigo-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Toma decisiones con datos en tiempo real</h3>
                    <p class="mt-2 text-gray-600">El dashboard en tiempo real te muestra el estado exacto de cada proyecto sin esperar al fin de semana — alertas inteligentes te avisan antes de que sea tarde.</p>
                </div>

                {{-- Benefit 3 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-puzzle-piece class="w-6 h-6 text-indigo-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Integra las herramientas que ya usas</h3>
                    <p class="mt-2 text-gray-600">Conecta Slack y Jira en 2 minutos — TaskFlow sincroniza actualizaciones directo a tu canal de Slack para que nadie se pierda nada.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- OBJECTION HANDLING (FAQ) --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Preguntas frecuentes
            </h2>

            <div class="space-y-6">
                {{-- FAQ Item 1: Price/Value --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Vale la pena el precio si solo tengo un equipo pequeño?</h3>
                    <p class="mt-2 text-gray-600">TaskFlow Pro se paga solo: si cada miembro de tu equipo recupera 8 horas semanales, eso son 32 horas mensuales de productividad recuperada por un equipo de 4 personas. El ROI es tangible desde la primera semana.</p>
                </div>

                {{-- FAQ Item 2: Complexity/Time --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Cuánto tiempo toma configurar TaskFlow con mis herramientas actuales?</h3>
                    <p class="mt-2 text-gray-600">Setup en 2 minutos. Conectas tu workspace de Slack y tu cuenta de Jira, y TaskFlow comienza a generar reportes automáticos de inmediato. No necesitas configuración técnica ni capacitación.</p>
                </div>

                {{-- FAQ Item 3: Guarantee/Risk --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Qué pasa si no me convence después de probarlo?</h3>
                    <p class="mt-2 text-gray-600">La prueba gratis es de 14 días sin tarjeta de crédito. Si no ves resultados, simplemente no continúas. Sin compromisos, sin costos ocultos, sin preguntas.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section id="cta" class="py-20 bg-gradient-to-br from-indigo-900 to-purple-900">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">
                ¿Sigues perdiendo horas en reuniones que podrían ser un mensaje?
            </h2>
            <p class="mt-4 text-xl text-indigo-200">
                Únete a los equipos que ya automatizan sus reportes y recuperan 10+ horas semanales con TaskFlow Pro.
            </p>
            <div class="mt-8">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-emerald-500 text-white font-semibold text-lg rounded-lg hover:bg-emerald-600 transition-colors shadow-lg">
                    Comenzar mi prueba gratis de 14 días
                </a>
            </div>
            <p class="mt-4 text-sm text-indigo-300">
                No requiere tarjeta de crédito · Cancela cuando quieras · Setup en 2 minutos
            </p>
        </div>
    </section>
</x-app-layout>
