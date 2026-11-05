<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister, doctors } = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    doctors: Array,
});

const phoneNumber = "237674000000";

const searchQuery = ref("");
const selectedSpecialty = ref("");

const filteredDoctors = computed(() => {
    let filtered = doctors;

    if (searchQuery.value) {
        filtered = filtered.filter(
            (doctor) =>
                doctor.user.name
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()) ||
                doctor.speciality
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedSpecialty.value) {
        filtered = filtered.filter(
            (doctor) => doctor.speciality === selectedSpecialty.value
        );
    }

    return filtered;
});

const specialties = computed(() => {
    const uniqueSpecialties = [
        ...new Set(doctors.map((doctor) => doctor.speciality)),
    ];
    return uniqueSpecialties;
});
</script>

<template>
    <Head title="Trouver un médecin - MICLINIX" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main class="public-medical-shell min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header Section -->
            <div class="mb-8 grid gap-6 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-7">
                    <div class="public-section-eyebrow mb-4">
                        <div
                            class="w-2 h-2 bg-teal-600 rounded-full animate-pulse"
                        ></div>
                        Trouvez votre médecin
                    </div>

                    <h1 class="public-title">
                        Découvrez nos
                        <span class="public-title-accent">
                            médecins spécialistes
                        </span>
                    </h1>
                    <p class="public-copy">
                        Trouvez le spécialiste adapté à vos besoins de santé.
                        Recherchez par nom ou spécialité pour entrer en contact
                        avec nos professionnels médicaux expérimentés.
                    </p>
                </div>
                <!-- Search and Filter Section -->
                <div
                    class="public-card p-5 lg:col-span-5"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Search Bar -->
                        <div>
                                <label
                                    class="public-form-label"
                                >
                                    Rechercher par nom du médecin
                                </label>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Entrez le nom du médecin..."
                                    class="public-form-field"
                                />
                        </div>

                        <!-- Specialty Filter -->
                        <div>
                                <label
                                    class="public-form-label"
                                >
                                    Filtrer par spécialité
                                </label>
                            <select
                                v-model="selectedSpecialty"
                                class="public-form-field appearance-none"
                            >
                                <option value="">Toutes les spécialités</option>
                                <option
                                    v-for="specialty in specialties"
                                    :key="specialty"
                                    :value="specialty"
                                >
                                    {{ specialty }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctors Grid -->
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="doctor in filteredDoctors"
                    :key="doctor.id"
                    class="public-card public-card-hover group overflow-hidden"
                >
                    <!-- Doctor Photo -->
                    <div
                        class="relative h-64 w-full bg-teal-50"
                    >
                        <img
                            v-if="doctor.user.photo"
                            :src="`/storage/${doctor.user.photo}`"
                            alt="Photo du médecin"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                        />
                        <svg
                            v-else
                            class="w-20 h-20 text-teal-700 absolute inset-0 m-auto"
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

                    <!-- Doctor Info -->
                    <div class="p-5 space-y-2">
                        <h3 class="text-lg font-black text-slate-950">
                            {{ doctor.user.name }}
                        </h3>
                        <p class="public-badge">
                            {{ doctor.speciality }}
                        </p>
                        <p class="text-sm font-bold text-slate-600">
                            {{ doctor.designation }}
                        </p>
                        <p class="line-clamp-3 text-sm font-medium leading-6 text-slate-600">
                            {{ doctor.about }}
                        </p>
                        <div
                            class="flex items-center gap-2 text-sm font-semibold text-slate-600"
                        >
                            <svg
                                class="h-5 w-5 text-teal-700"
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
                            <span>{{ doctor.phone }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 p-5">
                            <a
                            :href="`tel: ${phoneNumber}`"
                            class="btn-secondary w-full"
                        >
                            📞 Appeler maintenant
                        </a>

                        <!-- Book Appointment Button -->
                        <Link
                            :href="`/appointment-booking?doctor=${doctor.id}`"
                            class="btn-primary w-full"
                        >
                            Réserver un rendez-vous
                        </Link>

                        <!-- Doctors Details -->
                        <Link
                            :href="`/doctor/${doctor.id}`"
                            class="btn-secondary w-full"
                        >
                            Détails
                        </Link>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="filteredDoctors.length === 0" class="text-center py-12">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-teal-50 text-teal-700"
                >
                    <svg
                        class="h-10 w-10"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-950 mb-2">
                    Aucun médecin trouvé
                </h3>
                <p class="font-medium text-slate-600">
                    Essayez d'ajuster vos critères de recherche ou parcourez
                    tous les médecins.
                </p>
            </div>
        </div>
    </main>

    <Footer />
</template>
