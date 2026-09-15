{{-- ============================================
     LANDING PAGE: DataBoost - Python for Data Analysts
     Primary CTA: Empezar mi camino en Python
     ============================================ --}}

<x-app-layout>
    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center max-w-4xl mx-auto">
                {{-- H1: Outcome + Pain Elimination --}}
                <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight">
                    Automatiza tus reportes de Excel con Python — sin saber programar
                </h1>

                {{-- H2: What it is + Who it's for + Unique mechanism --}}
                <p class="mt-6 text-xl md:text-2xl text-emerald-200 max-w-3xl mx-auto">
                    DataBoost te enseña a usar Python para automatizar tus reportes diarios en 40 horas de video práctico, 12 proyectos reales y acceso a una comunidad que te acompaña paso a paso.
                </p>

                {{-- CTA Button --}}
                <div class="mt-10">
                    <a href="#cta" class="inline-flex items-center px-8 py-4 bg-amber-500 text-white font-semibold text-lg rounded-lg hover:bg-amber-600 transition-colors shadow-lg">
                        Empezar mi camino en Python
                    </a>
                </div>

                {{-- Trust Microcopy --}}
                <p class="mt-4 text-sm text-emerald-300">
                    No necesitas experiencia previa en programación · Garantía de devolución de 30 días · Acceso de por vida
                </p>
            </div>
        </div>

        {{-- Hero Visual --}}
        <div class="max-w-5xl mx-auto px-4 pb-16">
            <div class="rounded-xl overflow-hidden shadow-2xl border border-white/10">
                <img src="{{ asset('images/hero-course.png') }}" alt="DataBoost course preview" class="w-full">
            </div>
        </div>
    </section>

    {{-- SOCIAL PROOF --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                Más de 2,000 analistas de datos ya automatizan sus reportes con Python
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
                    De Excel manual a Python automatizado — sin curva de aprendizaje empinada
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                {{-- Benefit 1 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-play class="w-6 h-6 text-emerald-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Aprende a tu ritmo con 40 horas de video práctico</h3>
                    <p class="mt-2 text-gray-600">Cada módulo está diseñado para que apliques lo aprendido de inmediato en tus reportes reales — sin teoría innecesaria, solo resultados.</p>
                </div>

                {{-- Benefit 2 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-wrench-screwdriver class="w-6 h-6 text-emerald-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Construye 12 proyectos que puedes mostrar en tu portfolio</h3>
                    <p class="mt-2 text-gray-600">Cada proyecto es un caso real de automatización — desde limpieza de datos hasta dashboards interactivos que impresionan a tu equipo.</p>
                </div>

                {{-- Benefit 3 --}}
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-users class="w-6 h-6 text-emerald-600" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Nunca te quedas solo con acceso a la comunidad privada</h3>
                    <p class="mt-2 text-gray-600">Resuelve dudas con otros analistas en tu misma situación — la comunidad te acompaña desde tu primer script hasta tu primera automatización completa.</p>
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
                    <h3 class="font-semibold text-gray-900">¿Por qué invertir $199 en un curso cuando hay tutoriales gratis?</h3>
                    <p class="mt-2 text-gray-600">Los tutoriales sueltos no te llevan de principio a fin. DataBoost te da una ruta clara de 40 horas con 12 proyectos prácticos, acceso a comunidad privada y certificado. El costo se recupera cuando automatizas tu primer reporte y ahorras horas cada semana.</p>
                </div>

                {{-- FAQ Item 2: Complexity/Time --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Necesito saber programar para empezar?</h3>
                    <p class="mt-2 text-gray-600">No. El curso está diseñado específicamente para analistas de datos que nunca han programado. Empezamos desde cero con Python, usando ejemplos que ya conoces de Excel. Si sabes usar fórmulas en Excel, puedes aprender Python con DataBoost.</p>
                </div>

                {{-- FAQ Item 3: Guarantee/Risk --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900">¿Qué pasa si el curso no es lo que esperaba?</h3>
                    <p class="mt-2 text-gray-600">Ofrecemos garantía de devolución de 30 días sin preguntas. Si después de ver los primeros módulos sientes que no es para ti, te devolvemos el 100% de tu inversión.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section id="cta" class="py-20 bg-gradient-to-br from-emerald-900 to-teal-900">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">
                ¿Cuánto tiempo más vas a seguir haciendo reportes a mano en Excel?
            </h2>
            <p class="mt-4 text-xl text-emerald-200">
                Únete a más de 2,000 analistas que ya automatizan sus reportes con Python. Tu primer script está a una decisión de distancia.
            </p>
            <div class="mt-8">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-amber-500 text-white font-semibold text-lg rounded-lg hover:bg-amber-600 transition-colors shadow-lg">
                    Empezar mi camino en Python
                </a>
            </div>
            <p class="mt-4 text-sm text-emerald-300">
                Garantía de devolución de 30 días · Acceso de por vida · Sin experiencia previa requerida
            </p>
        </div>
    </section>
</x-app-layout>
