<script setup>
import { Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import Logo from "@/assets/images/logo/logo.png";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);
const currentRoute = computed(() => route().current());

const closeAll = () => {
    isMenuOpen.value = false;
};

const navLinks = [
    { label: "Accueil", routeName: "welcome" },
    { label: "Trouver un medecin", routeName: "find.doctor" },
    { label: "Rendez-vous", routeName: "appointment.booking" },
];
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-xl"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex justify-between items-center py-3 md:py-4">
                <Link :href="route('welcome')" @click.stop>
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <img
                            class="w-8 h-8 md:w-12 md:h-12"
                            :src="Logo"
                            alt="MICLINIX Logo"
                        />
                        <div>
                            <h1
                            class="bg-gradient-to-r from-teal-800 to-teal-500 bg-clip-text text-lg font-black text-transparent md:text-2xl"
                            >
                                MICLINIX
                            </h1>
                            <p
                            class="text-xs font-semibold text-slate-600 md:text-sm"
                            >
                                Excellence en soins de sante specialises
                            </p>
                        </div>
                    </div>
                </Link>

                <nav class="hidden lg:flex space-x-6 md:space-x-8" @click.stop>
                    <Link
                        v-for="item in navLinks"
                        :key="item.routeName"
                        :href="route(item.routeName)"
                        :class="[
                            'relative py-2 text-sm font-black transition-all duration-300 group md:text-base',
                            currentRoute === item.routeName
                                ? 'text-teal-700'
                                : 'text-slate-600 hover:text-teal-700',
                        ]"
                    >
                        <span class="relative z-10">{{ item.label }}</span>
                        <span
                            :class="[
                                'absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-teal-700 to-teal-500 transition-all duration-300 rounded-full',
                                currentRoute === item.routeName
                                    ? 'w-full'
                                    : 'w-0 group-hover:w-full',
                            ]"
                        />
                    </Link>
                </nav>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <button
                        type="button"
                        @click.stop="isMenuOpen = !isMenuOpen"
                        class="lg:hidden p-1.5 md:p-2 rounded-lg hover:bg-emerald-50 transition-colors duration-200"
                        aria-label="Toggle menu"
                        :aria-expanded="isMenuOpen"
                    >
                        <svg
                            class="w-5 h-5 md:w-6 md:h-6 text-slate-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="
                                    isMenuOpen
                                        ? 'M6 18L18 6M6 6l12 12'
                                        : 'M4 6h16M4 12h16M4 18h16'
                                "
                            />
                        </svg>
                    </button>

                    <div
                        v-if="canLogin"
                        class="hidden lg:flex space-x-3 md:space-x-4"
                        @click.stop
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-xl bg-teal-700 px-4 py-2 text-sm font-black text-white shadow-md shadow-teal-900/15 transition-all duration-300 hover:bg-teal-800 hover:shadow-lg md:px-6 md:py-2.5 md:text-base"
                        >
                            Tableau de bord
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-xl px-3 py-2 text-sm font-black text-slate-600 transition-all duration-300 hover:bg-teal-50 hover:text-teal-800 md:px-4 md:text-base"
                            >
                                Se connecter
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-xl bg-teal-700 px-4 py-2 text-sm font-black text-white shadow-md shadow-teal-900/15 transition-all duration-300 hover:bg-teal-800 hover:shadow-lg md:px-6 md:py-2.5 md:text-base"
                            >
                                S'inscrire
                            </Link>
                        </template>
                    </div>
                </div>
            </div>

            <div
                v-show="isMenuOpen"
                class="lg:hidden py-3 md:py-4 border-t border-emerald-100 animate-fade-in"
                @click.stop
            >
                <nav class="flex flex-col space-y-3 md:space-y-4">
                    <Link
                        v-for="item in navLinks"
                        :key="item.routeName"
                        :href="route(item.routeName)"
                        :class="[
                            'font-semibold transition-colors duration-200 py-2 px-3 md:px-4 rounded-lg text-sm md:text-base',
                            currentRoute === item.routeName
                                ? 'text-teal-700 bg-emerald-50'
                                : 'text-slate-600 hover:text-teal-700 hover:bg-emerald-50',
                        ]"
                        @click="closeAll"
                    >
                        {{ item.label }}
                    </Link>

                    <div
                        v-if="canLogin"
                        class="pt-3 md:pt-4 border-t border-emerald-100 space-y-2 md:space-y-3"
                        @click.stop
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="block w-full bg-gradient-to-r from-teal-700 to-teal-500 text-white text-center py-2 md:py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 text-sm md:text-base"
                            @click="closeAll"
                        >
                            Tableau de bord
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="block w-full text-slate-600 hover:text-teal-700 font-semibold text-center py-2 md:py-3 rounded-xl border border-emerald-100 hover:border-teal-200 transition-all duration-300 text-sm md:text-base"
                                @click="closeAll"
                            >
                                Se connecter
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="block w-full bg-gradient-to-r from-teal-700 to-teal-500 text-white text-center py-2 md:py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 text-sm md:text-base"
                                @click="closeAll"
                            >
                                S'inscrire
                            </Link>
                        </template>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.25s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
