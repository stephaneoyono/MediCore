<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister, doctor } = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    doctor: Object,
});
</script>

<template>
    <Head
        :title="`${doctor.user.name} - Détails du médecin - MICLINIX`"
    />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main class="public-medical-shell min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Back Button -->
            <div class="mb-6">
                <Link
                    href="/find-doctor"
                    class="public-link-button gap-2"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                    Retour à la recherche de médecins
                </Link>
            </div>

            <!-- Doctor Profile Card -->
            <div
                class="public-card overflow-hidden"
            >
                <!-- Doctor Photo and Basic Info -->
                <div class="relative">
                    <div
                        class="flex h-48 items-center justify-center bg-teal-50 sm:h-64"
                    >
                        <img
                            v-if="doctor.user.photo"
                            :src="`/storage/${doctor.user.photo}`"
                            alt="Photo du médecin"
                            class="h-32 w-32 rounded-2xl border-4 border-white object-cover shadow-lg sm:h-48 sm:w-48"
                        />
                        <svg
                            v-else
                            class="h-32 w-32 text-teal-700 sm:h-48 sm:w-48"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 right-0 bg-slate-950/75 p-4 backdrop-blur-sm sm:p-6"
                    >
                        <h1
                            class="text-2xl sm:text-3xl font-black text-white mb-2"
                        >
                            {{ doctor.user.name }}
                        </h1>
                        <p
                            class="font-bold text-teal-100 text-base sm:text-lg"
                        >
                            {{ doctor.speciality }}
                        </p>
                        <p class="text-white/80 text-sm sm:text-base">
                            {{ doctor.designation }}
                        </p>
                    </div>
                </div>

                <!-- Doctor Details -->
                <div class="p-4 sm:p-8 space-y-6">
                    <!-- About Section -->
                    <div>
                        <h2 class="mb-4 text-xl font-black text-slate-950 sm:text-2xl">
                            À propos
                        </h2>
                        <p
                            class="text-sm font-medium leading-7 text-slate-600 sm:text-base"
                        >
                            {{ doctor.about }}
                        </p>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h2 class="mb-4 text-xl font-black text-slate-950 sm:text-2xl">
                            Informations de contact
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="public-soft-panel flex items-center gap-4"
                            >
                                <div
                                    class="public-icon"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-950">
                                        Téléphone
                                    </h3>
                                    <p class="font-medium text-slate-600">
                                        {{ doctor.phone }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="public-soft-panel flex items-center gap-4"
                            >
                                <div
                                    class="public-icon"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-950">
                                        E-mail
                                    </h3>
                                    <p class="font-medium text-slate-600">
                                        {{ doctor.user.email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-4">
                                <a
                                :href="`tel:${doctor.phone}`"
                                class="btn-secondary flex-1 py-3 text-center"
                            >
                                📞 Appeler maintenant
                            </a>
                            <Link
                                :href="`/appointment-booking?doctor=${doctor.id}`"
                                class="btn-primary flex-1 py-3 text-center"
                            >
                                📋 Réserver un rendez-vous
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <Footer />
</template>
