<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const actions = [
    {
        title: "Rendez-vous",
        text: "Consulter vos reservations et telecharger vos recus.",
        href: "patient.book-appointment",
        cta: "Voir mes rendez-vous",
        icon: "calendar",
    },
    {
        title: "Dossiers medicaux",
        text: "Retrouver vos documents et votre historique de santé.",
        href: "patient.medical-records",
        cta: "Ouvrir les dossiers",
        icon: "file",
    },
    {
        title: "Profil",
        text: "Mettre a jour vos informations personnelles.",
        href: "patient.profile",
        cta: "Gerer mon profil",
        icon: "user",
    },
];
</script>

<template>
    <Head title="Tableau de bord patient" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Tableau de bord patient</h2>
                <p>Un espace clair pour suivre vos rendez-vous et vos informations de sante.</p>
            </div>
        </template>

        <section class="grid gap-5 lg:grid-cols-[1.25fr_0.75fr]">
            <div class="rounded-2xl bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/10 sm:p-8">
                <p class="text-xs font-black uppercase text-teal-200">Espace patient</p>
                <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight sm:text-4xl">
                    Vos soins, vos documents et vos rendez-vous dans un espace unifié.
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-slate-300 sm:text-base">
                    Accédez rapidement aux services essentiels de la clinique avec une interface plus lisible, plus calme et pensée pour le suivi médical.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Link :href="route('patient.book-appointment')" class="btn-primary bg-teal-500 hover:bg-teal-400">
                        Voir les rendez-vous
                    </Link>
                    <Link :href="route('find.doctor')" class="rounded-xl border border-white/20 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10">
                        Trouver un médecin
                    </Link>
                </div>
            </div>

            <div class="ds-card">
                <p class="text-sm font-black uppercase text-slate-500">Acces rapide</p>
                <div class="mt-5 space-y-3">
                    <div class="rounded-xl border border-teal-100 bg-teal-50 p-4">
                        <p class="text-sm font-bold text-teal-900">Rendez-vous</p>
                        <p class="mt-1 text-2xl font-black text-teal-800">24/7</p>
                        <p class="text-sm font-semibold text-teal-700">Suivi disponible en ligne</p>
                    </div>
                    <a href="tel:237674000000" class="flex items-center justify-between rounded-xl border border-red-100 bg-red-50 p-4 text-red-700 transition hover:bg-red-100">
                        <span class="text-sm font-black">Contact urgence</span>
                        <span class="text-lg font-black">237674000000</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-5 md:grid-cols-3">
            <article v-for="action in actions" :key="action.title" class="ds-card ds-card-hover">
                <div class="ds-icon">
                    <svg v-if="action.icon === 'calendar'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3m10-3v3M4 9h18M5 5h17v16H4V5h1z" />
                    </svg>
                    <svg v-else-if="action.icon === 'file'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7V3zm7 0v5h5M9 13h6M9 17h6" />
                    </svg>
                    <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0" />
                    </svg>
                </div>
                <h3 class="mt-5 text-lg font-black text-slate-950">{{ action.title }}</h3>
                <p class="mt-2 min-h-12 text-sm font-semibold leading-6 text-slate-500">{{ action.text }}</p>
                <Link :href="route(action.href)" class="btn-secondary mt-5 w-full">
                    {{ action.cta }}
                </Link>
            </article>
        </section>
    </AuthenticatedLayout>
</template>
