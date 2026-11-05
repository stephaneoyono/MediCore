<template>
    <section id="contact" class="public-section-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-10"
            >
                <div>
                    <span class="public-section-eyebrow">Contact</span>
                    <h2 class="public-title mt-4">
                        Contactez-nous
                    </h2>
                    <p class="public-copy mb-6 md:mb-8">
                        Des questions ou besoin de prendre rendez-vous ? Notre
                        équipe est là pour vous aider dans tous vos besoins de santé.
                    </p>

                    <div class="space-y-4 md:space-y-6">
                        <div
                            class="public-card flex items-center gap-3 p-4 md:gap-4 md:p-5"
                        >
                            <div
                                class="public-icon"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="text-sm font-black text-slate-950 md:text-base"
                                >
                                    Urgence
                                </h4>
                                <p class="text-sm font-medium text-slate-600 md:text-base">
                                    +237 674223816
                                </p>
                            </div>
                        </div>

                        <div
                            class="public-card flex items-center gap-3 p-4 md:gap-4 md:p-5"
                        >
                            <div
                                class="public-icon"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="text-sm font-black text-slate-950 md:text-base"
                                >
                                    Adresse
                                </h4>
                                <p class="text-sm font-medium text-slate-600 md:text-base">
                                    Rue 124, Beedi
                                </p>
                            </div>
                        </div>

                        <div
                            class="public-card flex items-center gap-3 p-4 md:gap-4 md:p-5"
                        >
                            <div
                                class="public-icon"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="text-sm font-black text-slate-950 md:text-base"
                                >
                                    Heures d'ouverture
                                </h4>
                                <p class="text-sm font-medium text-slate-600 md:text-base">
                                    Urgence : 24h/24 et 7j/7
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div
                    class="public-card p-6 md:p-8"
                >
                    <h3
                        class="mb-4 text-xl font-black text-slate-950 md:mb-6 md:text-2xl"
                    >
                        Envoyez-nous un message
                    </h3>
                    <form class="space-y-4 md:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="public-form-label"
                                    >Prénom</label
                                >
                                <input
                                    type="text"
                                    class="public-form-field"
                                />
                            </div>
                            <div>
                                <label
                                    class="public-form-label"
                                    >Nom</label
                                >
                                <input
                                    type="text"
                                    class="public-form-field"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="public-form-label"
                                >E-mail</label
                            >
                            <input
                                type="email"
                                class="public-form-field"
                            />
                        </div>
                        <div>
                            <label
                                class="public-form-label"
                                >Message</label
                            >
                            <textarea
                                rows="4"
                                class="public-form-field resize-y"
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="public-primary-button w-full py-3 md:py-4"
                        >
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
