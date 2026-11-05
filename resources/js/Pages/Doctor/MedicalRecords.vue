<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    records: {
        type: Array,
        default: () => [],
    },
    appointments: {
        type: Array,
        default: () => [],
    },
});

const records = ref(props.records);
const selectedAppointmentId = ref("");
const title = ref("");
const summary = ref("");
const recordDate = ref(new Date().toISOString().slice(0, 10));
const prescriptionId = ref("");
const submitting = ref(false);
const notice = ref(null);

const selectedAppointment = computed(() =>
    props.appointments.find((appointment) => appointment.id === Number(selectedAppointmentId.value))
);

const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
};

const resetForm = () => {
    selectedAppointmentId.value = "";
    title.value = "";
    summary.value = "";
    recordDate.value = new Date().toISOString().slice(0, 10);
    prescriptionId.value = "";
};

const submitRecord = async () => {
    if (!selectedAppointment.value) return;
    submitting.value = true;
    notice.value = null;

    try {
        const response = await fetch(`/doctor/appointments/${selectedAppointment.value.id}/medical-records`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
            },
            body: JSON.stringify({
                title: title.value,
                summary: summary.value,
                record_date: recordDate.value,
                prescription_id: prescriptionId.value || null,
            }),
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || "Impossible d'enregistrer le dossier.");
        }

        records.value.unshift(data.record);
        notice.value = { type: "success", message: data.message || "Dossier médical enregistré." };
        resetForm();
    } catch (error) {
        notice.value = { type: "error", message: error.message };
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <Head title="Dossiers médicaux" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Dossiers médicaux</h2>
                <p>Suivi clinique, ordonnances et notes patient.</p>
            </div>
        </template>

        <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <article class="ds-card">
                <h3 class="text-lg font-black text-slate-950">Ajouter une note clinique</h3>
                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Associez une note au rendez-vous du patient et, si besoin, à une ordonnance.
                </p>

                <div
                    v-if="notice"
                    class="mt-4 rounded-xl px-4 py-3 text-sm font-bold"
                    :class="notice.type === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'"
                >
                    {{ notice.message }}
                </div>

                <form class="mt-5 space-y-4" @submit.prevent="submitRecord">
                    <div>
                        <label>Rendez-vous patient</label>
                        <select v-model="selectedAppointmentId" required class="mt-1 w-full">
                            <option value="">Sélectionner un rendez-vous</option>
                            <option v-for="appointment in appointments" :key="appointment.id" :value="appointment.id">
                                {{ appointment.booking_id }} - {{ appointment.first_name }} {{ appointment.last_name }} - {{ formatDate(appointment.preferred_date) }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label>Titre</label>
                        <input v-model="title" required type="text" class="mt-1 w-full" placeholder="Compte rendu, suivi, observation..." />
                    </div>

                    <div>
                        <label>Date du dossier</label>
                        <input v-model="recordDate" required type="date" class="mt-1 w-full" />
                    </div>

                    <div v-if="selectedAppointment?.prescriptions?.length">
                        <label>Ordonnance associée</label>
                        <select v-model="prescriptionId" class="mt-1 w-full">
                            <option value="">Aucune ordonnance précise</option>
                            <option
                                v-for="prescription in selectedAppointment.prescriptions"
                                :key="prescription.id"
                                :value="prescription.id"
                            >
                                Ordonnance du {{ formatDate(prescription.created_at) }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label>Résumé clinique</label>
                        <textarea v-model="summary" rows="6" class="mt-1 w-full" placeholder="Diagnostic, evolution, observations, recommandations..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full" :disabled="submitting">
                        {{ submitting ? "Enregistrement..." : "Enregistrer le dossier" }}
                    </button>
                </form>
            </article>

            <article class="ds-card">
                <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Historique des dossiers</h3>
                        <p class="mt-1 text-sm font-semibold text-slate-500">{{ records.length }} entree(s) clinique(s).</p>
                    </div>
                    <Link href="/doctor/appointments" class="btn-secondary">Rendez-vous</Link>
                </div>

                <div v-if="records.length" class="mt-5 space-y-3">
                    <article v-for="record in records" :key="record.id" class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-sm font-black text-slate-950">{{ record.title }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-500">
                                    {{ record.appointment?.first_name }} {{ record.appointment?.last_name }} - {{ formatDate(record.record_date) }}
                                </p>
                                <p class="mt-2 whitespace-pre-wrap text-sm font-semibold text-slate-600">{{ record.summary || "Aucun résumé." }}</p>
                            </div>
                            <div class="flex shrink-0 flex-wrap gap-2">
                                <Link
                                    v-if="record.appointment"
                                    :href="route('doctor.appointments.show', record.appointment.id)"
                                    class="btn-secondary"
                                >
                                    Voir RDV
                                </Link>
                                <a
                                    v-if="record.appointment && record.prescription"
                                    :href="route('doctor.appointments.download-prescription-pdf', [record.appointment.id, record.prescription.id])"
                                    data-inertia="false"
                                    download
                                    class="btn-primary"
                                >
                                    PDF
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                <p v-else class="mt-5 rounded-xl bg-slate-50 p-4 text-sm font-semibold text-slate-500">
                    Aucun dossier médical n'a encore été créé.
                </p>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
