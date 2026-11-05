<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    summaries: {
        type: Object,
        default: () => ({
            doctors: 0,
            doctors_percentage: 0,
            patients: 0,
            patients_percentage: 0,
            appointments: 0,
            appointments_today: 0,
        }),
    },
    recentAppointments: {
        type: Array,
        default: () => [],
    },
});

const appointmentStatusLabels = {
    pending: "En attente",
    confirmed: "Confirme",
    cancelled: "Annule",
    completed: "Termine",
    in_progress: "En cours",
};
</script>

<template>
    <Head title="Tableau de bord administrateur" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Tableau de bord administrateur</h2>
                <p>Pilotage operationnel de la clinique, des praticiens et des rendez-vous.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-3">
            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Médecins</p>
                <div class="mt-3 flex items-end justify-between">
                    <h3 class="text-4xl font-black text-slate-950">{{ summaries.doctors }}</h3>
                    <span :class="summaries.doctors_percentage >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50'" class="rounded-full px-3 py-1 text-xs font-black">
                        {{ Math.abs(summaries.doctors_percentage) }}%
                    </span>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-500">Evolution depuis le mois dernier</p>
            </article>

            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Patients</p>
                <div class="mt-3 flex items-end justify-between">
                    <h3 class="text-4xl font-black text-slate-950">{{ summaries.patients }}</h3>
                    <span :class="summaries.patients_percentage >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50'" class="rounded-full px-3 py-1 text-xs font-black">
                        {{ Math.abs(summaries.patients_percentage) }}%
                    </span>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-500">Base patient active</p>
            </article>

            <article class="ds-kpi bg-slate-950 text-white">
                <p class="text-sm font-black uppercase text-teal-200">Rendez-vous</p>
                <div class="mt-3 flex items-end justify-between">
                    <h3 class="text-4xl font-black">{{ summaries.appointments }}</h3>
                    <span class="rounded-full bg-teal-400/15 px-3 py-1 text-xs font-black text-teal-100">
                        {{ summaries.appointments_today }} aujourd'hui
                    </span>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-300">Volume global des consultations</p>
            </article>
        </section>

        <section class="mt-6 grid gap-5 lg:grid-cols-3">
            <article class="ds-card ds-card-hover">
                <div class="ds-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 1 0-8 0m8 0c2.761 0 5 1.79 5 4v1H3v-1c0-2.21 2.239-4 5-4m8 0H8" />
                    </svg>
                </div>
                <h3 class="mt-5 text-lg font-black text-slate-950">Gestion des médecins</h3>
                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Ajouter, modifier et organiser les profils praticiens.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <Link href="/admin/doctors" class="btn-primary">Ouvrir</Link>
                    <Link href="/admin/doctors" class="btn-secondary">Ajouter</Link>
                </div>
            </article>

            <article class="ds-card ds-card-hover">
                <div class="ds-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3m10-3v3M4 9h18M5 5h17v16H4V5h1z" />
                    </svg>
                </div>
                <h3 class="mt-5 text-lg font-black text-slate-950">Planning clinique</h3>
                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Superviser les disponibilites et creneaux de consultation.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <Link href="/admin/schedules" class="btn-primary">Planning</Link>
                    <Link href="/admin/appointments" class="btn-secondary">Rendez-vous</Link>
                </div>
            </article>

            <article class="ds-card">
                <h3 class="text-lg font-black text-slate-950">Rendez-vous recents</h3>
                <div class="mt-5 space-y-3">
                    <div
                        v-for="appointment in recentAppointments.slice(0, 5)"
                        :key="appointment.id"
                        class="rounded-xl border border-slate-100 bg-slate-50 p-3"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ appointment.doctor_name }}</p>
                                <p class="text-xs font-semibold text-slate-500">
                                    {{ appointment.speciality }} - {{ appointment.preferred_time }}
                                </p>
                            </div>
                            <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-black text-teal-700">
                                {{ appointmentStatusLabels[appointment.status] ?? appointment.status }}
                            </span>
                        </div>
                    </div>
                    <p v-if="recentAppointments.length === 0" class="text-sm font-semibold text-slate-500">
                        Aucun rendez-vous recent.
                    </p>
                    <Link href="/admin/appointments" class="btn-secondary w-full">Voir tout</Link>
                </div>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
