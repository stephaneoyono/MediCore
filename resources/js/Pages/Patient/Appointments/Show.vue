<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    appointment: {
        type: Object,
        required: true,
    },
});

const statusLabels = {
    pending: "En attente",
    confirmed: "Confirme",
    cancelled: "Annule",
    completed: "Termine",
    in_progress: "En cours",
};

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
    <Head :title="`Rendez-vous ${appointment.booking_id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Detail du rendez-vous</h2>
                <p>{{ appointment.booking_id }}</p>
            </div>
        </template>

        <section class="ds-card">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase text-teal-700">Reference</p>
                    <h1 class="mt-1 text-2xl font-black text-slate-950">{{ appointment.booking_id }}</h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link href="/patient/appointments" class="btn-secondary">Retour</Link>
                    <a
                        :href="route('patient.appointments.download-pdf', appointment.id)"
                        data-inertia="false"
                        download
                        class="btn-primary"
                    >
                        Telecharger le PDF
                    </a>
                </div>
            </div>

            <dl class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Patient</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">
                        {{ appointment.first_name }} {{ appointment.last_name }}
                    </dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Statut</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">
                        {{ statusLabels[appointment.status] ?? appointment.status }}
                    </dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Specialite</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ appointment.speciality }}</dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Date et heure</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">
                        {{ formatFrenchDate(appointment.preferred_date) }} a {{ appointment.preferred_time }}
                    </dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Email</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ appointment.email }}</dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-black uppercase text-slate-500">Telephone</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ appointment.phone }}</dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 md:col-span-2">
                    <dt class="text-xs font-black uppercase text-slate-500">Notes</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">
                        {{ appointment.additional_notes || "Aucune note ajoutee." }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="ds-card mt-6">
            <div class="flex flex-col gap-2 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase text-teal-700">Ordonnances</p>
                    <h3 class="mt-1 text-lg font-black text-slate-950">Documents transmis par le medecin</h3>
                </div>
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-teal-800">
                    {{ appointment.prescriptions?.length || 0 }} document(s)
                </span>
            </div>

            <div v-if="appointment.prescriptions?.length" class="mt-5 space-y-3">
                <article
                    v-for="prescription in appointment.prescriptions"
                    :key="prescription.id"
                    class="rounded-xl border border-slate-200 bg-slate-50 p-4"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-black text-slate-950">
                                Ordonnance du {{ new Date(prescription.created_at).toLocaleDateString("fr-FR") }}
                            </p>
                            <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap text-sm font-semibold text-slate-600">{{ prescription.prescription_text }}</pre>
                        </div>
                        <a
                            :href="route('patient.prescriptions.download-pdf', [appointment.id, prescription.id])"
                            data-inertia="false"
                            download
                            class="btn-primary shrink-0"
                        >
                            Telecharger
                        </a>
                    </div>
                </article>
            </div>
            <p v-else class="mt-5 rounded-xl bg-slate-50 p-4 text-sm font-semibold text-slate-500">
                Aucune ordonnance n'a encore ete transmise pour ce rendez-vous.
            </p>
        </section>
    </AuthenticatedLayout>
</template>
