<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});
const photoUrl = computed(() => user.value?.photo_url || null);
</script>

<template>
    <Head title="Profil patient" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Profil patient</h2>
                <p>Vos informations personnelles et paramètres de compte.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-[0.8fr_1.2fr]">
            <article class="ds-card">
                <div class="flex items-center gap-4">
                    <div class="portal-avatar h-14 w-14 overflow-hidden text-lg">
                        <img v-if="photoUrl" :src="photoUrl" :alt="`Photo de ${user.name}`" class="h-full w-full object-cover" />
                        <svg v-else class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-xl font-black text-slate-950">{{ user.name }}</h3>
                        <p class="truncate text-sm font-semibold text-slate-500">{{ user.email }}</p>
                    </div>
                </div>
                
            </article>

            <article class="ds-card">
                <p class="text-xs font-black uppercase text-teal-700">Gestion du compte</p>
                <h3 class="mt-2 text-2xl font-black text-slate-950">Mettre à jour vos informations</h3>
                <p class="mt-3 text-sm font-semibold leading-6 text-slate-500">
                    Les paramètres détaillés du profil sont centralisés dans l'écran de compte sécurisé.
                </p>
                <Link :href="route('profile.edit')" class="btn-primary mt-6">
                    Ouvrir les paramètres
                </Link>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
