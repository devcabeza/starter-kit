{{-- ============================================
     LANDING PAGE: CloudShift - AWS Migration Consulting
     Primary CTA: Agendar mi auditoría gratuita
     ============================================ --}}

<x-app-layout>
    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center max-w-4xl mx-auto">
                {{-- H1: Outcome + Pain Elimination --}}
                <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight">
                    Migra a AWS con cero downtime — garantizado
                </h1>

                {{-- H2: What it is + Who it's for + Unique mechanism --}}
                <p class="mt-6 text-xl md:text-2xl text-slate-300 max-w-3xl mx-auto">
                    CloudShift ayuda a empresas mid-market a migrar de servidores on-premise a AWS en 3 fases probadas — con garantía de cero interrupciones y más de 50 migraciones exitosas.
                </p>

                {{-- CTA Button --}}
                <div class="mt-10">
                    <a href="#cta" class="inline-flex items-center px-8 py-4 bg-sky-500 text-white font-semibold text-lg rounded-lg hover:bg-sky-600 transition-colors shadow-lg">
                        Agendar mi auditoría gratuita
                    </a>
                </div>

                {{-- Trust Microcopy --}}
                <p class="mt-4 text-sm text-slate-400">
                    Auditoría gratuita de 30 minutos · Sin compromiso · 50+ empresas migradas con 0 downtime
                </p>
            </div>
        </div>

        {{-- Hero Visual --}}
        <div class="max-w-5xl mx-auto px-4 pb-16">
            <div class="rounded-xl overflow-hidden shadow-2xl border border-white/10">
                <img src="{{ asset('images/hero-migration.png') }}" alt="CloudShift migration process" class="w-full">
            </div>
        </div>
    </section>

    {{-- SOCIAL PROOF --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                Más de 50 empresas migradas con 0 downtime registrado
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
                    Tu migración en 3 fases probadas — sin sorpresas
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                {{-- Benefit 1 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-magnifying-glass class="w-6 h-6 text-sky-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Descubre exactamente qué migrar y cuánto ahorrarás</h3>
                    <p class="mt-2 text-gray-600">La auditoría gratuita de 30 minutos mapea tu infraestructura actual y te muestra el plan de migración personalizado con ahorros proyectados concretos.</p>
                </div>

                {{-- Benefit 2 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-document-text class="w-6 h-6 text-sky-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Plan de migración personalizado sin downtime</h3>
                    <p class="mt-2 text-gray-600">Cada empresa es diferente — diseñamos un plan a tu medida que garantiza cero interrupciones durante toda la migración.</p>
                </div>

                {{-- Benefit 3 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-check-badge class="w-6 h-6 text-sky-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Ejecución con garantía de soporte gratis si hay downtime</h3>
                    <p class="mt-2 text-gray-600">Si ocurre alguna interrupción durante la migración, el mes de soporte es completamente gratis — asumimos el riesgo por ti.</p>
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
                {{-- FAQ Item 1: Why migrate now --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Por qué migrar ahora si mis servidores aún funcionan?</h3>
                    <p class="mt-2 text-gray-600">El costo de mantenimiento de servidores viejos ya supera el costo de la nube. Cada mes que esperas es dinero perdido en soporte de hardware obsoleto y downtime no planificado que afecta a tu equipo.</p>
                </div>

                {% FAQ Item 2: Fear of downtime %}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Qué pasa si pierdo datos o tengo downtime durante la migración?</h3>
                    <p class="mt-2 text-gray-600">Hemos migrado más de 50 empresas con 0 downtime registrado. Si ocurre alguna interrupción, el mes de soporte es gratis — asumimos el riesgo completo para que migrar sin miedo.</p>
                </div>

                {{-- FAQ Item 3: Guarantee --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Qué cubre exactamente la garantía de cero downtime?</h3>
                    <p class="mt-2 text-gray-600">Si hay cualquier interrupción durante la migración, te damos un mes de soporte técnico completamente gratis. Esta es nuestra forma de demostrarte que confiamos en nuestro proceso.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section id="cta" class="py-20 bg-gradient-to-br from-slate-900 to-blue-900">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">
                ¿Cuánto más vas a seguir pagando por servidores que fallan cada vez más?
            </h2>
            <p class="mt-4 text-xl text-slate-300">
                Agenda tu auditoría gratuita de 30 minutos y descubre cuánto puedes ahorrar migrando a AWS — sin compromiso, sin downtime.
            </p>
            <div class="mt-8">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-sky-500 text-white font-semibold text-lg rounded-lg hover:bg-sky-600 transition-colors shadow-lg">
                    Agendar mi auditoría gratuita
                </a>
            </div>
            <p class="mt-4 text-sm text-slate-400">
                Garantía de cero downtime · Si hay interrupción, soporte gratis por 1 mes · Auditoría sin compromiso
            </p>
        </div>
    </section>
</x-app-layout>
