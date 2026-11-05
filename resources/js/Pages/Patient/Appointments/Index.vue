<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    appointments: {
        type: Array,
        default: () => [],
    },
});

const statusLabels = {
    pending: "En attente",
    confirmed: "Confirme",
    cancelled: "Annule",
    completed: "Termine",
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

const formatFrenchDate = (date) => {
    if (!date) return "";

    return new Date(date).toLocaleDateString("fr-FR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<template>
    <Head title="Mes rendez-vous" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Mes rendez-vous</h2>
                <p>Consultez vos reservations et documents PDF.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="ds-card bg-slate-950 text-white">
                <p class="text-xs font-black uppercase text-teal-200">Parcours patient</p>
                <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight">
                    Vos consultations medicales en un seul endroit.
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-slate-300">
                    Retrouvez les details de vos rendez-vous, suivez leur statut et telechargez vos recus.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Link href="/appointment-booking" class="btn-primary bg-teal-500 hover:bg-teal-400">
                        Nouveau rendez-vous
                    </Link>
                    <Link href="/dashboard" class="rounded-xl border border-white/20 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10">
                        Tableau de bord
                    </Link>
                </div>
            </div>

            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Reservations</p>
                <h3 class="mt-2 text-4xl font-black text-slate-950">{{ appointmentCount }}</h3>
                <p class="mt-2 text-sm font-semibold text-slate-500">Rendez-vous associes a votre compte.</p>
            </article>
        </section>

        <section class="mt-6 ds-card">
            <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Historique</h3>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Liste de vos rendez-vous et recus.</p>
                </div>
                <Link href="/appointment-booking" class="btn-secondary">Nouvelle reservation</Link>
            </div>

            <div v-if="appointments.length > 0" class="mt-5 space-y-3">
                <article
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-teal-200 hover:bg-white"
                >
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-black text-slate-950">Reference {{ appointment.booking_id }}</p>
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-black ring-1 ring-inset"
                                    :class="statusClasses[appointment.status] || 'bg-slate-100 text-slate-700 ring-slate-600/20'"
                                >
                                    {{ statusLabels[appointment.status] ?? appointment.status }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm font-semibold text-slate-600">
                                {{ appointment.speciality }} - {{ formatFrenchDate(appointment.preferred_date) }} a {{ appointment.preferred_time }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <Link :href="route('patient.appointments.show', appointment.id)" class="btn-secondary">
                                Voir
                            </Link>
                            <a
                                :href="route('patient.appointments.download-pdf', appointment.id)"
                                data-inertia="false"
                                download
                                class="btn-primary"
                            >
                                Recu PDF
                            </a>
                            <a
                                v-if="appointment.prescriptions?.length"
                                :href="route('patient.prescriptions.download-pdf', [appointment.id, appointment.prescriptions[appointment.prescriptions.length - 1].id])"
                                data-inertia="false"
                                download
                                class="btn-primary"
                            >
                                Ordonnance
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <div v-else class="py-14 text-center">
                <h3 class="text-lg font-black text-slate-950">Aucun rendez-vous reserve</h3>
                <p class="mx-auto mt-2 max-w-md text-sm font-semibold leading-6 text-slate-500">
                    Planifiez votre premiere consultation pour afficher ici vos informations de suivi.
                </p>
                <Link href="/appointment-booking" class="btn-primary mt-5">Reserver maintenant</Link>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
