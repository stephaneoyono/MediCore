<script setup>
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification de l'e-mail" />

        <div class="mb-4 text-sm text-gray-600">
            Merci pour votre inscription. Avant de commencer, veuillez vérifier
            votre adresse e-mail en cliquant sur le lien que nous venons de vous
            envoyer. Si vous ne l'avez pas reçu, nous pouvons vous en envoyer un
            nouveau.
        </div>

        <div
            class="mb-4 font-medium text-sm text-green-600"
            v-if="verificationLinkSent"
        >
            Un nouveau lien de vérification a été envoyé à l'adresse e-mail
            fournie lors de l'inscription.
        </div>

        <form @submit.prevent="submit">
            <div
                class="mt-4 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0"
            >
                <PrimaryButton
                    :class="{
                        'opacity-25': form.processing,
                        'w-full sm:w-auto': true,
                    }"
                    :disabled="form.processing"
                >
                    Renvoyer l'e-mail de vérification
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center sm:text-left"
                    >Déconnexion</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
