<script setup>
import { Link } from "@inertiajs/vue3";
const features = [
    {
        link: "/find-doctor",
        title: "Spécialistes experts",
        desc: "Plus de 200 spécialistes médicaux réputés dans tous les départements",
        icon: "👨‍⚕️",
    },
    {
        link: "#",
        title: "Technologie avancée",
        desc: "Équipements médicaux et installations de pointe",
        icon: "🔬",
    },
    {
        link: "#",
        title: "Urgences 24/7",
        desc: "Services d'urgence et de soins critiques 24h/24",
        icon: "🚨",
    },
    {
        link: "#",
        title: "Patient d'abord",
        desc: "Plans de soins personnalisés et traitement bienveillant",
        icon: "❤️",
    },
];
</script>

<template>
    <section class="public-section-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="public-section-header">
                <div>
                    <span class="public-section-eyebrow">Qualité des soins</span>
                    <h2 class="public-title mt-4">
                    Pourquoi choisir
                    <span class="public-title-accent">
                       MICLINIX ?
                    </span>
                    </h2>
                    <p class="public-copy">
                    Découvrez l'excellence des soins grâce à notre approche centrée sur le patient
                    et notre technologie médicale de pointe.
                    </p>
                </div>
            </div>

            <div
                class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
            >
                <Link
                    v-for="feature in features"
                    :key="feature.title"
                    :href="feature.link"
                    class="public-card public-card-hover p-5"
                >
                    <div class="public-icon mb-5">
                        {{ feature.icon }}
                    </div>
                    <h3 class="text-base font-black text-slate-950">
                        {{ feature.title }}
                    </h3>
                    <p
                        class="mt-2 text-sm font-medium leading-6 text-slate-600"
                    >
                        {{ feature.desc }}
                    </p>
                </Link>
            </div>
        </div>
    </section>
</template>
