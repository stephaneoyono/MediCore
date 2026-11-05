<script setup>
import { Head } from "@inertiajs/vue3";

import Header from "@/Components/Landing/Header.vue";
import Banner from "@/Components/Landing/BannerSlider.vue";
import WhyChoose from "@/Components/Landing/WhyChooseUs.vue";
import Specialists from "@/Components/Landing/Specialists.vue";
import CorporateServices from "@/Components/Landing/CorporateServices.vue";
import Contact from "@/Components/Landing/Contact.vue";
import Footer from "@/Components/Landing/Footer.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Bienvenue" />

    <!-- Modern Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Hero Section -->
    <Banner />

    <!-- Specialists  Section -->
    <Specialists />

    <!-- Why Choose Us Section -->
    <WhyChoose />

    <!-- Corporate Services Section -->
    <CorporateServices />

    <!-- Contact Section -->
    <Contact />

    <!-- Modern Footer -->
    <Footer />
</template>
