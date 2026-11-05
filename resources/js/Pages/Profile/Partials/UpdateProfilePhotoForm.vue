<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);
const preview = ref(null);
const fileInput = ref(null);
const clientError = ref("");

const form = useForm({
    photo: null,
});

const photoUrl = computed(() => preview.value || user.value?.photo_url || null);

const validateFile = (file) => {
    const allowedTypes = ["image/jpeg", "image/png", "image/webp"];
    const maxSize = 2 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
        return "Veuillez choisir une image JPG, JPEG, PNG ou WEBP.";
    }

    if (file.size > maxSize) {
        return "La photo ne doit pas dépasser 2 Mo.";
    }

    return "";
};

const choosePhoto = () => fileInput.value?.click();

const onPhotoChange = (event) => {
    const file = event.target.files?.[0];
    clientError.value = "";
    form.clearErrors("photo");

    if (!file) {
        form.photo = null;
        preview.value = null;
        return;
    }

    const error = validateFile(file);
    if (error) {
        clientError.value = error;
        form.photo = null;
        preview.value = null;
        event.target.value = "";
        return;
    }

    form.photo = file;
    preview.value = URL.createObjectURL(file);
};

const submitPhoto = () => {
    if (!form.photo) {
        clientError.value = "Veuillez choisir une photo avant d'enregistrer.";
        return;
    }

    form.post(route("profile.photo.update"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset("photo");
            preview.value = null;
            if (fileInput.value) fileInput.value.value = "";
        },
    });
};

const removePhoto = () => {
    router.delete(route("profile.photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset("photo");
            preview.value = null;
            clientError.value = "";
            if (fileInput.value) fileInput.value.value = "";
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Photo de profil</h2>
            <p class="mt-1 text-sm text-gray-600">
                Ajoutez une photo professionnelle visible dans le menu, le profil et les listes du système.
            </p>
        </header>

        <div class="mt-6 flex flex-col gap-5 sm:flex-row sm:items-center">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-teal-50 text-teal-800 shadow-sm ring-1 ring-slate-200">
                <img v-if="photoUrl" :src="photoUrl" :alt="`Photo de ${user.name}`" class="h-full w-full object-cover" />
                <svg v-else class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0" />
                </svg>
            </div>

            <div class="min-w-0 flex-1">
                <input
                    ref="fileInput"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    class="hidden"
                    @change="onPhotoChange"
                />

                <div class="flex flex-wrap gap-3">
                    <SecondaryButton type="button" @click="choosePhoto">Choisir une image</SecondaryButton>
                    <PrimaryButton type="button" :disabled="form.processing || !form.photo" @click="submitPhoto">
                        Enregistrer la photo
                    </PrimaryButton>
                    <DangerButton v-if="user.photo_url || preview" type="button" :disabled="form.processing" @click="removePhoto">
                        Supprimer la photo
                    </DangerButton>
                </div>

                <p class="mt-3 text-xs font-semibold text-slate-500">Formats acceptés : JPG, JPEG, PNG, WEBP. Taille maximale : 2 Mo.</p>
                <InputError class="mt-2" :message="clientError || form.errors.photo" />
            </div>
        </div>
    </section>
</template>
