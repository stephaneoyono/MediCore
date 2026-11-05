<script setup lang="ts">
import Logo from "@/assets/images/logo/logo.png";
</script>

<template>
    <footer class="border-t border-white/10 bg-slate-950 py-12 text-white sm:py-16">
        <div class="max-w-7xl mx-auto px-12 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 sm:gap-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-start sm:items-center gap-4 mb-6">
                        <img :src="Logo" class="w-12 h-12 sm:w-14 sm:h-14" />
                        <div>
                            <h4 class="text-xl font-black sm:text-2xl">
                                MICLINIX
                            </h4>
                            <p
                                class="text-sm font-semibold text-slate-300 sm:text-base"
                            >
                                Excellence en soins de santé spécialisés
                            </p>
                        </div>
                    </div>
                    <p
                        class="max-w-md text-sm font-medium leading-7 text-slate-300 sm:text-base"
                    >
                        Offrir des soins de santé spécialisés avec
                        compassion, innovation et engagement inébranlable envers
                        le bien-être des patients.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h5 class="mb-4 text-sm font-black uppercase text-white sm:mb-6">Liens rapides</h5>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a
                                href="#home"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Accueil</a
                            >
                        </li>
                        <li>
                            <a
                                href="#services"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Services</a
                            >
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h5 class="mb-4 text-sm font-black uppercase text-white sm:mb-6">Nos services</h5>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a
                                href="#"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Soins d'urgence</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Cardiologie</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Neurologie</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-sm font-semibold text-slate-300 transition hover:text-teal-100 sm:text-base"
                                >Chirurgie</a
                            >
                        </li>
                    </ul>
                </div>

            </div>


            <!-- Droits d'auteur -->
            <div
                class="mt-8 border-t border-white/10 pt-8 text-center sm:mt-10 sm:pt-10"
            >
                <p class="text-xs font-semibold text-slate-400 sm:text-sm">
                    &copy; {{ new Date().getFullYear() }} MICLINIX
                    . Tous droits réservés. |
                    <a href="#" class="text-xs transition hover:text-teal-100 sm:text-sm"
                        >Politique de confidentialité</a
                    >
                    •
                    <a href="#" class="text-xs transition hover:text-teal-100 sm:text-sm"
                        >Conditions d'utilisation</a
                    >
                </p>
            </div>
        </div>
    </footer>
</template>
