<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    appointments: {
        type: Array,
        default: () => [],
    },
    records: {
        type: Array,
        default: () => [],
    },
});

const prescriptionDocuments = computed(() =>
    props.appointments.flatMap((appointment) =>
        (appointment.prescriptions || []).map((prescription) => ({
            appointment,
            prescription,
            date: prescription.created_at || appointment.preferred_date,
        }))
    )
);

const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
};
</script>

<template>
    <Head title="Dossiers medicaux" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Dossiers medicaux</h2>
                <p>Ordonnances, recus et historique clinique disponibles.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-3">
            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Rendez-vous</p>
                <h3 class="mt-2 text-4xl font-black text-slate-950">{{ appointments.length }}</h3>
                <p class="mt-2 text-sm font-semibold text-slate-500">Consultations rattachees a votre compte.</p>
            </article>
            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Ordonnances</p>
                <h3 class="mt-2 text-4xl font-black text-slate-950">{{ prescriptionDocuments.length }}</h3>
                <p class="mt-2 text-sm font-semibold text-slate-500">Documents telechargeables par le patient.</p>
            </article>
            <article class="ds-kpi">
                <p class="text-sm font-black uppercase text-slate-500">Dossiers</p>
                <h3 class="mt-2 text-4xl font-black text-slate-950">{{ records.length }}</h3>
                <p class="mt-2 text-sm font-semibold text-slate-500">Notes cliniques creees par le medecin.</p>
            </article>
        </section>

        <section class="ds-card mt-6">
            <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Ordonnances disponibles</h3>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Telechargez les ordonnances envoyees par vos medecins.</p>
                </div>
                <Link href="/patient/appointments" class="btn-secondary">Voir mes rendez-vous</Link>
            </div>

            <div v-if="prescriptionDocuments.length" class="mt-5 space-y-3">
                <article
                    v-for="document in prescriptionDocuments"
                    :key="document.prescription.id"
                    class="rounded-xl border border-slate-200 bg-slate-50 p-4"
                >
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm font-black text-slate-950">
                                {{ document.appointment.booking_id }} - {{ document.appointment.speciality }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-500">
                                Creee le {{ formatDate(document.date) }}
                            </p>
                            <pre class="mt-3 max-h-36 overflow-auto whitespace-pre-wrap text-sm font-semibold text-slate-600">{{ document.prescription.prescription_text }}</pre>
                        </div>
                        <a
                            :href="route('patient.prescriptions.download-pdf', [document.appointment.id, document.prescription.id])"
                            data-inertia="false"
                            download
                            class="btn-primary shrink-0"
                        >
                            Telecharger l'ordonnance
                        </a>
                    </div>
                </article>
            </div>

            <div v-else class="py-12 text-center">
                <h3 class="text-lg font-black text-slate-950">Aucune ordonnance disponible</h3>
                <p class="mx-auto mt-2 max-w-md text-sm font-semibold leading-6 text-slate-500">
                    Les ordonnances apparaîtront ici dès qu'un médecin les aura créées depuis votre rendez-vous.
                </p>
                <Link href="/appointment-booking" class="btn-primary mt-5">Réserver maintenant</Link>
            </div>
        </section>

        <section class="ds-card mt-6">
            <h3 class="text-lg font-black text-slate-950">Notes du dossier médical</h3>
            <div v-if="records.length" class="mt-5 grid gap-3">
                <article v-for="record in records" :key="record.id" class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm font-black text-slate-950">{{ record.title }}</p>
                    <p class="mt-1 text-xs font-black uppercase text-teal-700">{{ formatDate(record.record_date) }}</p>
                    <p class="mt-2 whitespace-pre-wrap text-sm font-semibold text-slate-600">{{ record.summary || "Aucun résumé ajouté." }}</p>
                </article>
            </div>
            <p v-else class="mt-4 rounded-xl bg-slate-50 p-4 text-sm font-semibold text-slate-500">
                Aucune note clinique n'a encore été ajoutée à votre dossier.
            </p>
        </section>
    </AuthenticatedLayout>
</template>
