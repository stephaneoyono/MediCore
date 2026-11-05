<script setup>
import Background from "@/assets/images/background/background.png";
</script>

<template>
    <section class="medical-hero relative min-h-[520px] overflow-hidden sm:min-h-[580px] lg:min-h-[680px]">
        <div
            class="absolute inset-0 scale-105 bg-cover bg-center blur-[3px]"
            :style="{ backgroundImage: `url(${Background})` }"
            aria-hidden="true"
        ></div>
        <div class="absolute inset-0 bg-slate-950/55" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(20,184,166,0.22),transparent_34rem)]" aria-hidden="true"></div>

        <div class="relative z-10 mx-auto flex min-h-[520px] max-w-5xl items-center justify-center px-4 py-24 text-center sm:min-h-[580px] sm:px-6 lg:min-h-[680px] lg:px-8">
            <div class="max-w-4xl">
                <h1 class="text-3xl font-black leading-tight text-white drop-shadow-xl sm:text-5xl lg:text-6xl">
                    Une plateforme moderne dediee a la gestion efficace de vos rendez-vous medicaux.
                </h1>
                <p class="mx-auto mt-6 max-w-3xl text-base font-semibold leading-8 text-slate-100 drop-shadow sm:text-xl">
                    Planifiez vos consultations, gerez vos suivis medicaux et accedez a vos informations de sante en toute simplicite.
                </p>
            </div>
        </div>
    </section>
</template>
