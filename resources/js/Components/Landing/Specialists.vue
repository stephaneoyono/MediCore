<script setup>
import { computed, onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import api from "@/lib/api";

const doctors = ref([]);
const loading = ref(true);

const fetchDoctors = async () => {
    try {
        const response = await api.get("/api/doctors");
        doctors.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error("Erreur lors du chargement des médecins :", error);
    } finally {
        loading.value = false;
    }
};

const photoUrl = (doctor) => {
    const photo = doctor?.user?.photo;
    if (!photo) return null;
    if (photo.startsWith("http://") || photo.startsWith("https://")) return photo;
    if (photo.startsWith("/")) return photo;
    if (photo.startsWith("public/")) return `/storage/${photo.replace(/^public\//, "")}`;
    if (photo.startsWith("storage/")) return `/${photo}`;
    return `/storage/${photo}`;
};

const visibleDoctors = computed(() => doctors.value.slice(0, 12));

onMounted(fetchDoctors);
</script>

<template>
    <section class="public-section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="public-section-header">
                <div>
                    <span class="public-section-eyebrow">Equipe médicale</span>
                    <h2 class="public-title mt-4">
                        Nos
                        <span class="public-title-accent">
                            spécialistes
                        </span>
                    </h2>
                    <p class="public-copy">
                        Des spécialistes hautement qualifiés, formés à
                        l'international, issus de collèges médicaux réputés.
                    </p>
                </div>

                <Link
                    href="/find-doctor"
                    class="public-link-button"
                >
                    Voir tous les médecins
                </Link>
            </div>

            <div v-if="loading" class="text-center">
                <p class="text-sm font-semibold text-slate-500">Chargement des spécialistes...</p>
            </div>

            <div
                v-else-if="visibleDoctors.length === 0"
                class="public-card p-8 text-center"
            >
                <p class="font-black text-slate-950">
                    Aucun médecin disponible pour le moment.
                </p>
                <p class="mt-1 text-sm font-medium text-slate-600">
                    Les spécialistes ajoutés depuis l'administration
                    apparaîtront ici.
                </p>
            </div>

            <div v-else class="horizontal-doctors">
                <Link
                    v-for="doctor in visibleDoctors"
                    :key="doctor.id"
                    :href="`/doctor/${doctor.id}`"
                    class="doctor-card"
                >
                    <div class="doctor-photo">
                        <img
                            v-if="photoUrl(doctor)"
                            :src="photoUrl(doctor)"
                            :alt="doctor.user?.name"
                            loading="lazy"
                        />
                        <svg v-else class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-black text-slate-950">
                            {{ doctor.user?.name }}
                        </h3>
                        <p class="mt-1 truncate text-xs font-bold text-slate-600">
                            {{ doctor.designation || "Médecin spécialiste" }}
                        </p>
                        <p class="mt-2 inline-flex rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-xs font-black text-teal-800">
                            {{ doctor.speciality }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
.horizontal-doctors {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: minmax(280px, 280px);
    gap: 1rem;
    overflow-x: auto;
    padding: 0.25rem 0 1rem;
    scroll-snap-type: x mandatory;
    scrollbar-width: thin;
}

.doctor-card {
    display: flex;
    height: 132px;
    scroll-snap-align: start;
    align-items: center;
    gap: 1rem;
    border-radius: 0.875rem;
    border: 1px solid var(--medical-border);
    background: var(--medical-surface);
    padding: 0.875rem;
    box-shadow: 0 1px 2px rgb(15 23 42 / 0.04), 0 18px 45px rgb(15 23 42 / 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.doctor-card:hover {
    transform: translateY(-1px);
    border-color: rgb(153 246 228);
    box-shadow: 0 18px 48px rgb(15 118 110 / 0.1);
}

.doctor-photo {
    display: grid;
    width: 88px;
    height: 104px;
    flex-shrink: 0;
    place-items: center;
    overflow: hidden;
    border-radius: 0.75rem;
    background: rgb(20 184 166 / 0.12);
    color: var(--medical-primary);
    font-weight: 900;
}

.doctor-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (min-width: 640px) {
    .horizontal-doctors {
        grid-auto-columns: minmax(330px, 330px);
    }

    .doctor-card {
        height: 148px;
        padding: 1rem;
    }

    .doctor-photo {
        width: 104px;
        height: 116px;
    }
}
</style>
