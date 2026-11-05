<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Logo from "@/assets/images/logo/logo.png";

defineProps({ title: { type: String, default: "" } });

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});
const isAdmin = computed(() => user.value?.role === "admin");
const isDoctor = computed(() => user.value?.role === "doctor");
const sidebarOpen = ref(false);
const showLogoutConfirm = ref(false);
const loggingOut = ref(false);

const isRouteActive = (routeName) => route().current(routeName);

const navItems = computed(() => {
    if (isAdmin.value) {
        return [
            { name: "Vue d'ensemble", routeName: "dashboard", icon: "home" },
            { name: "Médecins", routeName: "admin.doctors.index", icon: "users" },
            { name: "Planning", routeName: "admin.schedules.index", icon: "calendar" },
            { name: "Rendez-vous", routeName: "admin.appointments.index", icon: "clock" },
            { name: "Profil", routeName: "profile.edit", icon: "user" },
        ];
    }

    if (isDoctor.value) {
        return [
            { name: "Vue d'ensemble", routeName: "dashboard", icon: "home" },
            { name: "Planning", routeName: "doctor.schedules", icon: "calendar" },
            { name: "Rendez-vous", routeName: "doctor.appointments.index", icon: "clock" },
            { name: "Dossiers", routeName: "doctor.medical-records.index", icon: "file" },
            { name: "Notes", routeName: "doctor.hospital-notes.index", icon: "file" },
            { name: "Profil", routeName: "profile.edit", icon: "user" },
        ];
    }

    return [
        { name: "Tableau de bord", routeName: "dashboard", icon: "home" },
        { name: "Trouver un médecin", routeName: "find.doctor", icon: "search" },
        { name: "Rendez-vous", routeName: "patient.book-appointment", icon: "calendar" },
        { name: "Dossiers", routeName: "patient.medical-records", icon: "file" },
        { name: "Profil", routeName: "patient.profile", icon: "user" },
    ];
});

const portalLabel = computed(() => {
    if (isAdmin.value) return "Portail administrateur";
    if (isDoctor.value) return "Cabinet médical";
    return "Espace patient";
});

const avatarUrl = computed(() => user.value?.photo_url || null);

const getIconSvg = (icon) => {
    const common = 'class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"';
    const paths = {
        home: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z"/>',
        users: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 1 0-8 0m8 0c2.761 0 5 1.79 5 4v1H3v-1c0-2.21 2.239-4 5-4m8 0H8m8-8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>',
        calendar: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v3m10-3v3M4 9h18M6 5h16v16H4V5h2z"/>',
        clock: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v5l3 2m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>',
        search: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.2-5.2M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/>',
        file: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7V3zm7 0v5h5M9 13h6M9 17h6"/>',
        user: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0"/>',
        logout: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>',
    };

    return `<svg ${common}>${paths[icon] || paths.home}</svg>`;
};

const openLogoutModal = () => {
    sidebarOpen.value = false;
    showLogoutConfirm.value = true;
};

const closeLogoutModal = () => {
    if (!loggingOut.value) showLogoutConfirm.value = false;
};

const confirmLogout = () => {
    if (loggingOut.value) return;

    loggingOut.value = true;
    router.post(
        route("logout"),
        {},
        {
            onFinish: () => {
                loggingOut.value = false;
                showLogoutConfirm.value = false;
            },
        }
    );
};
</script>

<template>
    <div class="portal-shell">
        <aside class="portal-sidebar hidden lg:flex">
            <Link :href="route('welcome')" class="portal-brand">
                <img :src="Logo" class="h-11 w-11 rounded-xl" alt="MICLINIX" />
                <div>
                    <p class="text-base font-black text-white">MICLINIX</p>
                    <p class="text-xs font-semibold text-teal-100/80">{{ portalLabel }}</p>
                </div>
            </Link>

            <div class="portal-user-card">
                <img v-if="avatarUrl" :src="avatarUrl" class="portal-avatar object-cover" :alt="`Photo de ${user.name}`" />
                <div v-else class="portal-avatar portal-avatar-default" aria-label="Avatar par défaut">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white">{{ user.name }}</p>
                    <p class="truncate text-xs font-semibold capitalize text-teal-100/75">{{ user.role }}</p>
                </div>
            </div>

            <nav class="mt-6 flex-1 space-y-1">
                <Link
                    v-for="item in navItems"
                    :key="item.routeName"
                    :href="route(item.routeName)"
                    class="portal-nav-link"
                    :class="{ 'portal-nav-link-active': isRouteActive(item.routeName) }"
                >
                    <span v-html="getIconSvg(item.icon)"></span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <button type="button" class="portal-logout" @click="openLogoutModal">
                <span v-html="getIconSvg('logout')"></span>
                <span>Déconnexion</span>
            </button>
        </aside>

        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            class="portal-mobile-drawer lg:hidden"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <Link :href="route('welcome')" class="portal-brand">
                <img :src="Logo" class="h-11 w-11 rounded-xl" alt="MICLINIX" />
                <div>
                    <p class="text-base font-black text-white">MICLINIX</p>
                    <p class="text-xs font-semibold text-teal-100/80">{{ portalLabel }}</p>
                </div>
            </Link>

            <div class="portal-user-card">
                <img v-if="avatarUrl" :src="avatarUrl" class="portal-avatar object-cover" :alt="`Photo de ${user.name}`" />
                <div v-else class="portal-avatar portal-avatar-default" aria-label="Avatar par défaut">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white">{{ user.name }}</p>
                    <p class="truncate text-xs font-semibold capitalize text-teal-100/75">{{ user.role }}</p>
                </div>
            </div>

            <nav class="mt-6 space-y-1">
                <Link
                    v-for="item in navItems"
                    :key="item.routeName"
                    :href="route(item.routeName)"
                    class="portal-nav-link"
                    :class="{ 'portal-nav-link-active': isRouteActive(item.routeName) }"
                    @click="sidebarOpen = false"
                >
                    <span v-html="getIconSvg(item.icon)"></span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <button type="button" class="portal-logout" @click="openLogoutModal">
                <span v-html="getIconSvg('logout')"></span>
                <span>Déconnexion</span>
            </button>
        </aside>

        <div class="portal-main">
            <header class="portal-topbar">
                <button type="button" class="portal-icon-button lg:hidden" @click="sidebarOpen = true" aria-label="Ouvrir le menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <div>
                    <p class="text-xs font-black uppercase text-teal-700">{{ portalLabel }}</p>
                    <div class="portal-header-slot">
                        <slot name="header">
                            <h1 class="text-lg font-black text-slate-950 sm:text-2xl">
                            {{ title || "Tableau de bord" }}
                            </h1>
                        </slot>
                    </div>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    <div class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm sm:block">
                        {{ new Date().toLocaleDateString("fr-FR", { day: "2-digit", month: "long", year: "numeric" }) }}
                    </div>
                    <img v-if="avatarUrl" :src="avatarUrl" class="portal-avatar object-cover" :alt="`Photo de ${user.name}`" />
                    <div v-else class="portal-avatar portal-avatar-default text-sm" aria-label="Avatar par défaut">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0"/>
                        </svg>
                    </div>
                    <button type="button" class="portal-icon-button hidden sm:inline-flex" @click="openLogoutModal" aria-label="Se déconnecter">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" />
                        </svg>
                    </button>
                </div>
            </header>

            <div v-if="page.props.flash?.success" class="portal-toast portal-toast-success">
                {{ page.props.flash.success }}
            </div>
            <div v-if="page.props.flash?.error" class="portal-toast portal-toast-danger">
                {{ page.props.flash.error }}
            </div>

            <main class="portal-content">
                <slot />
            </main>
        </div>

        <nav class="portal-bottom-nav lg:hidden">
            <template v-for="item in navItems.slice(0, 5)" :key="item.routeName">
                <Link
                    :href="route(item.routeName)"
                    class="portal-bottom-link"
                    :class="{ 'text-teal-700': isRouteActive(item.routeName) }"
                >
                    <span v-html="getIconSvg(item.icon)"></span>
                    <span>{{ item.name.split(' ')[0] }}</span>
                </Link>
            </template>
            <button type="button" class="portal-bottom-link text-red-600" @click="openLogoutModal">
                <span v-html="getIconSvg('logout')"></span>
                <span>Sortir</span>
            </button>
        </nav>

        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showLogoutConfirm"
                    class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="logout-title"
                >
                    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="closeLogoutModal"></div>
                    <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
                        <h3 id="logout-title" class="text-lg font-black text-slate-950">Confirmer la déconnexion</h3>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600">
                            Vous allez quitter votre session sécurisée.
                        </p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" class="btn-secondary" :disabled="loggingOut" @click="closeLogoutModal">
                                Annuler
                            </button>
                            <button type="button" class="btn-danger" :disabled="loggingOut" @click="confirmLogout">
                                {{ loggingOut ? "Déconnexion..." : "Se déconnecter" }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
