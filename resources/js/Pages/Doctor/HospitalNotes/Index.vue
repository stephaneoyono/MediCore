<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";

const props = defineProps({
    notes: { type: Array, default: () => [] },
    author: { type: String, default: "" },
});

const notes = ref([...props.notes]);
const editingId = ref(null);
const submitting = ref(false);
const deletingId = ref(null);
const notice = ref(null);
const errors = ref({});

const form = reactive({
    title: "",
    content: "",
    note_date: new Date().toISOString().slice(0, 10),
});

const isEditing = computed(() => editingId.value !== null);
const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("fr-FR", { day: "2-digit", month: "long", year: "numeric" });
};

const resetForm = () => {
    editingId.value = null;
    form.title = "";
    form.content = "";
    form.note_date = new Date().toISOString().slice(0, 10);
    errors.value = {};
};

const editNote = (note) => {
    editingId.value = note.id;
    form.title = note.title;
    form.content = note.content;
    form.note_date = note.note_date?.slice(0, 10) || new Date().toISOString().slice(0, 10);
    errors.value = {};
    notice.value = null;
};

const submitNote = async () => {
    submitting.value = true;
    notice.value = null;
    errors.value = {};

    const url = isEditing.value ? route("doctor.hospital-notes.update", editingId.value) : route("doctor.hospital-notes.store");

    try {
        const response = await fetch(url, {
            method: isEditing.value ? "PUT" : "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: JSON.stringify(form),
        });
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            errors.value = data.errors || {};
            throw new Error(data.message || "Impossible d'enregistrer la note.");
        }

        notes.value = isEditing.value
            ? notes.value.map((note) => (note.id === data.note.id ? data.note : note))
            : [data.note, ...notes.value];

        notice.value = { type: "success", message: data.message };
        resetForm();
    } catch (error) {
        notice.value = { type: "error", message: error.message };
    } finally {
        submitting.value = false;
    }
};

const deleteNote = async (note) => {
    if (!confirm("Supprimer définitivement cette note d'hôpital ?")) return;

    deletingId.value = note.id;
    notice.value = null;

    try {
        const response = await fetch(route("doctor.hospital-notes.destroy", note.id), {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken(),
            },
        });
        const data = await response.json().catch(() => ({}));

        if (!response.ok) throw new Error(data.message || "Impossible de supprimer la note.");

        notes.value = notes.value.filter((item) => item.id !== note.id);
        notice.value = { type: "success", message: data.message || "Note d'hôpital supprimée avec succès." };
        if (editingId.value === note.id) resetForm();
    } catch (error) {
        notice.value = { type: "error", message: error.message };
    } finally {
        deletingId.value = null;
    }
};
</script>

<template>
    <Head title="Notes d'hôpital" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Notes d'hôpital</h2>
                <p>Créez, consultez et organisez vos notes internes.</p>
            </div>
        </template>

        <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <article class="ds-card">
                <h3 class="text-lg font-black text-slate-950">{{ isEditing ? "Modifier la note" : "Ajouter une note d'hôpital" }}</h3>
                <p class="mt-1 text-sm font-semibold text-slate-500">Auteur : {{ author }}</p>

                <div v-if="notice" class="mt-4 rounded-xl px-4 py-3 text-sm font-bold" :class="notice.type === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'">
                    {{ notice.message }}
                </div>

                <form class="mt-5 space-y-4" @submit.prevent="submitNote">
                    <div>
                        <label for="title">Titre</label>
                        <input id="title" v-model="form.title" required type="text" class="mt-1 w-full" placeholder="Ex. consigne de suivi, observation interne..." />
                        <p v-if="errors.title" class="mt-2 text-sm font-semibold text-red-600">{{ errors.title[0] }}</p>
                    </div>

                    <div>
                        <label for="note_date">Date</label>
                        <input id="note_date" v-model="form.note_date" required type="date" class="mt-1 w-full" />
                        <p v-if="errors.note_date" class="mt-2 text-sm font-semibold text-red-600">{{ errors.note_date[0] }}</p>
                    </div>

                    <div>
                        <label for="content">Contenu</label>
                        <textarea id="content" v-model="form.content" required rows="8" class="mt-1 w-full" placeholder="Rédigez la note d'hôpital..."></textarea>
                        <p v-if="errors.content" class="mt-2 text-sm font-semibold text-red-600">{{ errors.content[0] }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn-primary" :disabled="submitting">
                            {{ submitting ? "Enregistrement..." : isEditing ? "Modifier la note" : "Créer la note" }}
                        </button>
                        <button v-if="isEditing" type="button" class="btn-secondary" :disabled="submitting" @click="resetForm">Annuler</button>
                    </div>
                </form>
            </article>

            <article class="ds-card">
                <div class="border-b border-slate-100 pb-5">
                    <h3 class="text-lg font-black text-slate-950">Mes notes</h3>
                    <p class="mt-1 text-sm font-semibold text-slate-500">{{ notes.length }} note(s) enregistrée(s).</p>
                </div>

                <div v-if="notes.length" class="mt-5 space-y-3">
                    <article v-for="note in notes" :key="note.id" class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-black text-slate-950">{{ note.title }}</p>
                                <p class="mt-1 text-xs font-bold uppercase text-teal-700">{{ formatDate(note.note_date) }} - {{ author }}</p>
                                <p class="mt-3 whitespace-pre-wrap text-sm font-semibold leading-6 text-slate-600">{{ note.content }}</p>
                            </div>
                            <div class="flex shrink-0 flex-wrap gap-2">
                                <button type="button" class="btn-secondary" @click="editNote(note)">Modifier</button>
                                <button type="button" class="btn-danger" :disabled="deletingId === note.id" @click="deleteNote(note)">
                                    {{ deletingId === note.id ? "Suppression..." : "Supprimer" }}
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <p v-else class="mt-5 rounded-xl bg-slate-50 p-4 text-sm font-semibold text-slate-500">
                    Aucune note d'hôpital n'a encore été créée.
                </p>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
