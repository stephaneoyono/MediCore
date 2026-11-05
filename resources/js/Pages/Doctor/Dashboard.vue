<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Tableau de bord médecin" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Tableau de bord médecin</h2>
                <p>Organisez vos disponibilités, consultations et suivis patients.</p>
            </div>
        </template>

        <section class="grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="ds-card bg-slate-950 text-white">
                <p class="text-xs font-black uppercase text-teal-200">Cabinet médical</p>
                <h1 class="mt-3 text-3xl font-black leading-tight">
                    Un poste de travail plus rapide pour piloter votre journee.
                </h1>
                <p class="mt-4 text-sm font-semibold leading-7 text-slate-300">
                    Retrouvez vos plannings et rendez-vous avec une hiérarchie visuelle plus dense, adaptée aux usages quotidiens d'un cabinet.
                </p>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <Link href="/doctor/schedules" class="btn-primary bg-teal-500 hover:bg-teal-400">
                        Gérer le planning
                    </Link>
                    <Link href="/doctor/appointments" class="rounded-xl border border-white/20 px-4 py-2.5 text-center text-sm font-bold text-white transition hover:bg-white/10">
                        Voir les rendez-vous
                    </Link>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <article class="ds-kpi">
                    <div class="ds-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3m10-3v3M4 9h18M5 5h17v16H4V5h1z" />
                        </svg>
                    </div>
                    <p class="mt-5 text-sm font-black uppercase text-slate-500">Planning</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-950">Disponibilités</h3>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Créer, modifier et vérifier vos créneaux de consultation.</p>
                </article>

                <article class="ds-kpi">
                    <div class="ds-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v5l3 2m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    </div>
                    <p class="mt-5 text-sm font-black uppercase text-slate-500">Consultations</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-950">Rendez-vous</h3>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Filtrer les demandes et acceder aux fiches patients.</p>
                </article>
            </div>
        </section>

        <section class="mt-6 grid gap-5 lg:grid-cols-3">
            <div class="ds-card lg:col-span-2">
                <h3 class="text-lg font-black text-slate-950">Actions rapides</h3>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <Link href="/doctor/schedules" class="rounded-xl bg-slate-50 p-4 transition hover:bg-teal-50">
                        <p class="text-sm font-black text-slate-900">1. Planning</p>
                        <p class="mt-1 text-sm font-semibold text-slate-500">Verifiez les creneaux actifs.</p>
                    </Link>
                    <Link href="/doctor/appointments" class="rounded-xl bg-slate-50 p-4 transition hover:bg-teal-50">
                        <p class="text-sm font-black text-slate-900">2. Rendez-vous</p>
                        <p class="mt-1 text-sm font-semibold text-slate-500">Filtrez par date ou statut.</p>
                    </Link>
                    <Link href="/doctor/hospital-notes" class="rounded-xl bg-slate-50 p-4 transition hover:bg-teal-50">
                        <p class="text-sm font-black text-slate-900">3. Ajouter des notes d'hôpital</p>
                        <p class="mt-1 text-sm font-semibold text-slate-500">Créez et suivez vos notes internes.</p>
                    </Link>
                </div>
            </div>
            <div class="ds-card">
                <p class="text-sm font-black uppercase text-teal-700">Interface premium</p>
                <p class="mt-3 text-sm font-semibold leading-7 text-slate-500">
                    Les tableaux, filtres et formulaires du portail médecin utilisent maintenant le design system interne.
                </p>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
