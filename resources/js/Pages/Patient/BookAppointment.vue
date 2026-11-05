<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    appointments: {
        type: Array,
        default: () => [],
    },
});

const showModal = ref(false);
const selectedAppointment = ref(null);

const statusLabels = {
    pending: "En attente",
    confirmed: "Confirmé",
    cancelled: "Annulé",
    completed: "Terminé",
    in_progress: "En cours",
};

const statusClasses = {
    pending: "bg-amber-50 text-amber-700 ring-amber-600/20",
    confirmed: "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
    cancelled: "bg-rose-50 text-rose-700 ring-rose-600/20",
    completed: "bg-slate-100 text-slate-700 ring-slate-600/20",
    in_progress: "bg-cyan-50 text-cyan-700 ring-cyan-600/20",
};

const appointmentCount = computed(() => props.appointments.length);
const nextAppointment = computed(() => props.appointments[0] ?? null);

const formatFrenchDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("fr-FR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

function openModal(appointment) {
    selectedAppointment.value = appointment;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedAppointment.value = null;
}
</script>

<template>
    <Head title="Mes rendez-vous" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Mes rendez-vous</h2>
                <p>Suivez vos réservations, statuts et reçus PDF.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="ds-card bg-slate-950 text-white">
                <p class="text-xs font-black uppercase text-teal-200">Parcours patient</p>
                <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight">
                    Une vue claire de vos prochaines consultations.
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-slate-300">
                    Consultez les détails de chaque réservation, téléchargez votre reçu et planifiez un nouveau rendez-vous en quelques clics.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Link href="/appointment-booking" class="btn-primary bg-teal-500 hover:bg-teal-400">
                        Réserver un rendez-vous
                    </Link>
                    <Link href="/dashboard" class="rounded-xl border border-white/20 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10">
                        Retour au tableau de bord
                    </Link>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                <article class="ds-kpi">
                    <p class="text-sm font-black uppercase text-slate-500">Réservations</p>
                    <h3 class="mt-2 text-4xl font-black text-slate-950">{{ appointmentCount }}</h3>
                    <p class="mt-2 text-sm font-semibold text-slate-500">Rendez-vous enregistrés sur votre compte.</p>
                </article>
                <article class="ds-kpi">
                    <p class="text-sm font-black uppercase text-slate-500">Prochain suivi</p>
                    <h3 class="mt-2 text-lg font-black text-slate-950">
                        {{ nextAppointment ? nextAppointment.preferred_time : "Aucun créneau" }}
                    </h3>
                    <p class="mt-2 text-sm font-semibold text-slate-500">
                        {{ nextAppointment ? formatFrenchDate(nextAppointment.preferred_date) : "Réservez une nouvelle consultation." }}
                    </p>
                </article>
            </div>
        </section>

        <section class="mt-6 ds-card">
            <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Historique des rendez-vous</h3>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Liste de vos réservations et documents associés.</p>
                </div>
                <Link href="/appointment-booking" class="btn-secondary">Nouvelle réservation</Link>
            </div>

            <div v-if="appointments.length > 0" class="mt-5 space-y-3">
                <article
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-teal-200 hover:bg-white hover:shadow-lg hover:shadow-teal-900/5"
                >
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex min-w-0 gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3m10-3v3M4 9h18M6 5h16v16H4V5h2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-black text-slate-950">Référence {{ appointment.booking_id }}</p>
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-black ring-1 ring-inset"
                                        :class="statusClasses[appointment.status] || 'bg-slate-100 text-slate-700 ring-slate-600/20'"
                                    >
                                        {{ statusLabels[appointment.status] ?? appointment.status }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm font-semibold text-slate-600">
                                    {{ appointment.speciality }} · {{ formatFrenchDate(appointment.preferred_date) }} à {{ appointment.preferred_time }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="btn-secondary" @click="openModal(appointment)">
                                Voir
                            </button>
                            <a
                                :href="route('patient.appointments.download-pdf', appointment.id)"
                                data-inertia="false"
                                download
                                class="btn-primary"
                            >
                                Reçu PDF
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <div v-else class="py-14 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3v3m10-3v3M4 9h18M6 5h16v16H4V5h2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-black text-slate-950">Aucun rendez-vous réservé</h3>
                <p class="mx-auto mt-2 max-w-md text-sm font-semibold leading-6 text-slate-500">
                    Planifiez votre première consultation pour afficher ici vos reçus, statuts et informations de suivi.
                </p>
                <Link href="/appointment-booking" class="btn-primary mt-5">Réserver maintenant</Link>
            </div>
        </section>

        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
                    role="dialog"
                    aria-modal="true"
                >
                    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="closeModal"></div>
                    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase text-teal-700">Détail réservation</p>
                                <h3 class="mt-1 text-xl font-black text-slate-950">
                                    {{ selectedAppointment?.booking_id }}
                                </h3>
                            </div>
                            <button type="button" class="portal-icon-button" @click="closeModal" aria-label="Fermer">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <dl v-if="selectedAppointment" class="mt-6 grid gap-3">
                            <div class="rounded-xl bg-slate-50 p-3">
                                <dt class="text-xs font-black uppercase text-slate-500">Spécialité</dt>
                                <dd class="mt-1 text-sm font-bold text-slate-950">{{ selectedAppointment.speciality }}</dd>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <dt class="text-xs font-black uppercase text-slate-500">Date</dt>
                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ formatFrenchDate(selectedAppointment.preferred_date) }}</dd>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <dt class="text-xs font-black uppercase text-slate-500">Heure</dt>
                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ selectedAppointment.preferred_time }}</dd>
                                </div>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <dt class="text-xs font-black uppercase text-slate-500">Statut</dt>
                                <dd class="mt-1 text-sm font-bold text-slate-950">
                                    {{ statusLabels[selectedAppointment.status] ?? selectedAppointment.status }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" class="btn-secondary" @click="closeModal">Fermer</button>
                            <a
                                v-if="selectedAppointment"
                                :href="route('patient.appointments.download-pdf', selectedAppointment.id)"
                                data-inertia="false"
                                download
                                class="btn-primary"
                            >
                                Télécharger le reçu
                            </a>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
