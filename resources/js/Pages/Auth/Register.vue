<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import api from "@/lib/api";
import Logo from "@/assets/images/logo/logo.png";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const emailAvailable = ref(null);
const checkingEmail = ref(false);

const emailValid = computed(() => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return form.email ? emailRegex.test(form.email) : null;
});

watch(
    () => form.email,
    async (newEmail) => {
        if (!newEmail || !emailValid.value) {
            emailAvailable.value = null;
            return;
        }

        checkingEmail.value = true;
        try {
            const response = await api.post("/api/email-check", {
                email: newEmail,
            });
            emailAvailable.value = response.data.available;
        } catch (error) {
            console.error("Email check failed:", error);
            emailAvailable.value = null;
        } finally {
            checkingEmail.value = false;
        }
    }
);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Inscription - MICLINIX" />

    <main class="auth-shell">
        <section class="auth-card auth-card-wide" aria-labelledby="register-title">
            <Link href="/" class="mx-auto mb-8 flex w-fit items-center justify-center">
                <img :src="Logo" class="h-16 w-16" alt="MICLINIX" />
            </Link>

            <div class="text-center">
                <h1 id="register-title" class="text-2xl font-black text-slate-900 sm:text-3xl">
                    Creer un compte
                </h1>
                <p class="mt-2 text-sm font-medium text-slate-500">
                    Completez vos informations pour acceder au portail.
                </p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-5">
                <div>
                    <InputLabel for="name" value="Nom complet" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-2 block w-full"
                        placeholder="Votre nom complet"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="E-mail professionnel" />
                    <div class="relative mt-2">
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full pr-12"
                            placeholder="nom@clinique.com"
                            v-model="form.email"
                            required
                            autocomplete="email"
                        />
                        <span class="absolute inset-y-0 right-4 flex items-center text-xs font-bold text-slate-400">
                            {{ checkingEmail ? "..." : emailValid ? "OK" : "" }}
                        </span>
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                    <p
                        v-if="emailValid && emailAvailable !== null"
                        class="mt-2 text-sm font-semibold"
                        :class="emailAvailable ? 'text-emerald-700' : 'text-red-600'"
                    >
                        {{ emailAvailable ? "E-mail disponible." : "E-mail deja utilise." }}
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <InputLabel for="password" value="Mot de passe" />
                        <div class="relative mt-2">
                            <TextInput
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                class="block w-full pr-12"
                                placeholder="Mot de passe"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-teal-700"
                                @click="showPassword = !showPassword"
                                :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                            >
                                <span class="text-xs font-bold">{{ showPassword ? "OFF" : "ON" }}</span>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirmation" />
                        <div class="relative mt-2">
                            <TextInput
                                id="password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                class="block w-full pr-12"
                                placeholder="Confirmer"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-teal-700"
                                @click="showConfirmPassword = !showConfirmPassword"
                                :aria-label="showConfirmPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                            >
                                <span class="text-xs font-bold">{{ showConfirmPassword ? "OFF" : "ON" }}</span>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <Checkbox name="terms" v-model:checked="form.terms" class="mt-1" />
                    <span class="text-sm font-medium leading-6 text-slate-600">
                        J'accepte les conditions d'utilisation et la politique de confidentialite.
                    </span>
                </label>
                <InputError class="mt-2" :message="form.errors.terms" />

                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing || !form.terms"
                >
                    {{ form.processing ? "Creation..." : "Creer un compte" }}
                </PrimaryButton>

                <p class="text-center text-sm font-medium text-slate-500">
                    Vous avez deja un compte ?
                    <Link :href="route('login')" class="font-bold text-teal-700 hover:text-teal-900">
                        Se connecter
                    </Link>
                </p>
            </form>
        </section>
    </main>
</template>
