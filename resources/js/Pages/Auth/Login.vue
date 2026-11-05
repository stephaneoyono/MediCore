<script setup>
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Logo from "@/assets/images/logo/logo.png";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Connexion - MICLINIX" />

    <main class="auth-shell">
        <section class="auth-card" aria-labelledby="login-title">
            <Link href="/" class="mx-auto mb-8 flex w-fit items-center justify-center">
                <img :src="Logo" class="h-16 w-16" alt="MICLINIX" />
            </Link>

            <div class="text-center">
                <h1 id="login-title" class="text-2xl font-black text-slate-900 sm:text-3xl">
                    Connexion
                </h1>
                <p class="mt-2 text-sm font-medium text-slate-500">
                    Accedez a votre espace medical securise.
                </p>
            </div>

            <div
                v-if="status"
                class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-5">
                <div>
                    <InputLabel for="email" value="Adresse e-mail" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full"
                        placeholder="nom@exemple.com"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Mot de passe" />
                    <div class="relative mt-2">
                        <TextInput
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="block w-full pr-12"
                            placeholder="Votre mot de passe"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
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

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm font-medium text-slate-600">Se souvenir de moi</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-bold text-teal-700 hover:text-teal-900"
                    >
                        Mot de passe oublie ?
                    </Link>
                </div>

                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Connexion..." : "Se connecter" }}
                </PrimaryButton>

                <p class="text-center text-sm font-medium text-slate-500">
                    Vous n'avez pas de compte ?
                    <Link :href="route('register')" class="font-bold text-teal-700 hover:text-teal-900">
                        Creer un compte
                    </Link>
                </p>
            </form>
        </section>
    </main>
</template>
