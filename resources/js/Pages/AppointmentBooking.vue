<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, computed, watch } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const {
    doctors,
    canLogin,
    canRegister,
    laravelVersion,
    phpVersion,
    appointments,
    selectedDoctor,
} = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    doctors: Array,
    appointments: Object,
    selectedDoctor: Object,
});

// Function to generate time slots from doctor's schedules (start and end times only)
const generateTimeSlotsFromSchedules = (schedules) => {
    if (!schedules || schedules.length === 0) return [];

    const slots = [];
    schedules.forEach((schedule) => {
        const start = new Date(`1970-01-01T${schedule.start_time}`);
        const end = new Date(`1970-01-01T${schedule.end_time}`);

        // Format start time
        const startHours = start.getHours();
        const startMinutes = start.getMinutes();
        const startDisplayTime = `${startHours
            .toString()
            .padStart(2, "0")}:${startMinutes.toString().padStart(2, "0")}`;
        slots.push(startDisplayTime);

        // Format end time
        const endHours = end.getHours();
        const endMinutes = end.getMinutes();
        const endDisplayTime = `${endHours
            .toString()
            .padStart(2, "0")}:${endMinutes.toString().padStart(2, "0")}`;
        slots.push(endDisplayTime);
    });

    // Remove duplicates and sort
    return [...new Set(slots)].sort();
};

const form = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    gender: "",
    age: "",
    preferredDate: "",
    preferredTime: "",
    speciality: "",
    doctorId: "",
    additionalNotes: "",
});

const submitting = ref(false);
const showSuccessModal = ref(false);
const successAppointment = ref(null);
const formErrors = ref({});
const bookingNotice = ref({ show: false, type: "success", message: "" });

const fieldMap = {
    firstName: "first_name",
    lastName: "last_name",
    email: "email",
    phone: "phone",
    gender: "gender",
    age: "age",
    preferredDate: "preferred_date",
    preferredTime: "preferred_time",
    speciality: "speciality",
    doctorId: "doctor_id",
    additionalNotes: "additional_notes",
};

const getFieldError = (field) => formErrors.value[fieldMap[field]]?.[0] || "";

const showBookingNotice = (message, type = "success") => {
    bookingNotice.value = { show: true, type, message };
    window.setTimeout(() => {
        bookingNotice.value.show = false;
    }, 5500);
};

// Computed property for minimum date (today)
const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split("T")[0];
});

const weekDayKeys = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
const getWeekDayKey = (date) => weekDayKeys[date.getDay()];

const submitForm = async () => {
    submitting.value = true;
    formErrors.value = {};
    bookingNotice.value.show = false;

    try {
        const formData = new FormData();
        formData.append("first_name", form.value.firstName);
        formData.append("last_name", form.value.lastName);
        formData.append("email", form.value.email);
        formData.append("phone", form.value.phone);
        formData.append("gender", form.value.gender);
        formData.append("age", form.value.age);
        formData.append("preferred_date", form.value.preferredDate);
        formData.append("preferred_time", form.value.preferredTime);
        formData.append("speciality", form.value.speciality);
        formData.append("doctor_id", form.value.doctorId || "");
        formData.append("additional_notes", form.value.additionalNotes || "");
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
        if (csrfToken) {
            formData.append("_token", csrfToken);
        }

        const response = await fetch("/appointments", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        let result;
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
            result = await response.json();
        } else {
            await response.text();
            result = {
                message:
                    "La réservation n'a pas pu être finalisée. Vérifiez les informations saisies puis réessayez.",
            };
        }

        if (response.ok) {
            successAppointment.value = result.appointment;
            showSuccessModal.value = true;
            showBookingNotice(result.message || "Rendez-vous réservé avec succès.", "success");
        } else {
            formErrors.value = result.errors || {};
            showBookingNotice(
                result.message ||
                    "Certains champs doivent être corrigés avant de réserver le rendez-vous.",
                "error"
            );
        }
    } catch (error) {
        console.error("Error:", error);
        showBookingNotice(
            "Une erreur est survenue lors de la réservation. Veuillez réessayer.",
            "error"
        );
    } finally {
        submitting.value = false;
    }
};

// Available time slots
const timeSlots = ref([]);

// Computed property for unique specialties from doctors
const specialties = computed(() => {
    const uniqueSpecialties = new Set();
    doctors.forEach((doctor) => {
        if (doctor.speciality) {
            uniqueSpecialties.add(doctor.speciality);
        }
    });
    return Array.from(uniqueSpecialties).map((speciality) => {
        // Map speciality to label and icon (you can customize this mapping)
        const mappings = {};
        const mapping = mappings[speciality] || {
            label: speciality,
            icon: "👨‍⚕️",
        };
        return { value: speciality, label: mapping.label, icon: mapping.icon };
    });
});

// Computed property for filtered doctors based on selected speciality
const filteredDoctors = computed(() => {
    if (!form.value.speciality) return [];
    return doctors.filter(
        (doctor) => doctor.speciality === form.value.speciality
    );
});

// Computed property for available dates based on selected doctor's schedule
const availableDates = computed(() => {
    if (!form.value.doctorId) return []; // No dates if no doctor selected

    const selectedDoctor = doctors.find(
        (doctor) => doctor.id == form.value.doctorId
    );
    if (
        !selectedDoctor ||
        !selectedDoctor.schedules ||
        selectedDoctor.schedules.length === 0
    ) {
        return []; // No dates available
    }

    // Collect all available days of the week from doctor's schedules
    const availableDays = new Set();
    selectedDoctor.schedules.forEach((schedule) => {
        schedule.day_of_week.forEach((day) => availableDays.add(day));
    });

    // Generate available dates starting from tomorrow for the next 7 days
    const dates = [];
    const today = new Date();
    for (let i = 1; i < 8; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        const dayName = getWeekDayKey(date);
        if (availableDays.has(dayName)) {
            const value = date.toISOString().split("T")[0];
            const label = date.toLocaleDateString("fr-FR", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });

            // Check if the date is fully booked
            const appointmentKey = `${selectedDoctor.id}-${value}`;
            const appointmentCount = appointments[appointmentKey]?.count || 0;
            const maxPatients =
                selectedDoctor.schedules.find((schedule) =>
                    schedule.day_of_week.includes(dayName)
                )?.max_patients_per_day || 0;

            let status = "";
            if (appointmentCount >= maxPatients) {
                status = "Complet";
            } else if (appointmentCount > 0) {
                status = "Places limitées";
            }

            dates.push({ value, label, status });
        }
    }

    return dates;
});

// Computed property for available time slots based on selected doctor and date
const availableTimeSlots = computed(() => {
    if (!form.value.doctorId) return timeSlots.value; // Default slots if no doctor selected

    const selectedDoctor = doctors.find(
        (doctor) => doctor.id == form.value.doctorId
    );
    if (
        !selectedDoctor ||
        !selectedDoctor.schedules ||
        selectedDoctor.schedules.length === 0
    ) {
        return []; // No slots available
    }

    let schedulesToUse = selectedDoctor.schedules;

    if (form.value.preferredDate) {
        // Get day of week for the selected date
        const selectedDate = new Date(form.value.preferredDate);
        const dayName = getWeekDayKey(selectedDate);

        // Filter schedules for that day
        schedulesToUse = selectedDoctor.schedules.filter((schedule) =>
            schedule.day_of_week.includes(dayName)
        );
    }

    return generateTimeSlotsFromSchedules(schedulesToUse);
});

// Watch for speciality changes to reset doctor selection
watch(
    () => form.value.speciality,
    (newSpeciality) => {
        if (newSpeciality && form.value.doctorId) {
            const selectedDoctor = doctors.find(
                (doctor) => doctor.id === form.value.doctorId
            );
            if (
                !selectedDoctor ||
                selectedDoctor.speciality !== newSpeciality
            ) {
                form.value.doctorId = "";
            }
        }
    }
);

// Watch for doctor changes to reset date and time selection
watch(
    () => form.value.doctorId,
    () => {
        form.value.preferredDate = "";
        form.value.preferredTime = "";
    }
);

// Pre-fill form if selectedDoctor is provided
onMounted(() => {
    if (selectedDoctor) {
        form.value.doctorId = selectedDoctor.id;
        form.value.speciality = selectedDoctor.speciality;
    }
});

const downloadAppointmentDetails = (appointment) => {
    if (!appointment?.id) return;

    // Create a temporary link to download the PDF
    const link = document.createElement("a");
    link.href = `/appointments/${appointment.id}/download-pdf`;
    link.download = `appointment_${appointment.booking_id || appointment.id}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

const closeModal = () => {
    showSuccessModal.value = false;
    successAppointment.value = null;
    // Reset form
    form.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        gender: "",
        age: "",
        preferredDate: "",
        preferredTime: "",
        speciality: "",
        doctorId: "",
        additionalNotes: "",
    };
};

const formatFrenchDate = (date) =>
    new Date(date).toLocaleDateString("fr-FR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });

// Benefits information
const benefits = ref([
    {
        icon: "⏰",
        title: "Confirmation rapide",
        description: "Recevez une confirmation sous 2 heures",
    },
    {
        icon: "👨‍⚕️",
        title: "Médecins experts",
        description: "Consultez des spécialistes",
    },
    {
        icon: "💻",
        title: "Consultation en ligne",
        description: "Possibilité de rendez-vous virtuels",
    },
    {
        icon: "📱",
        title: "Alertes de rappel",
        description: "Recevez des rappels par SMS et email avant votre rendez-vous",
    },
]);

// Hospital stats
const hospitalStats = ref([
    { number: "50,000+", label: "Patients pris en charge" },
    { number: "200+", label: "Médecins experts" },
    { number: "98%", label: "Taux de réussite" },
    { number: "24/7", label: "Soins d'urgence" },
]);
</script>

<template>
    <Head title="Réservez votre rendez-vous - MICLINIX" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main class="public-medical-shell min-h-screen">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div
                    class="public-section-eyebrow mb-4"
                >
                    <div
                        class="h-2 w-2 animate-pulse rounded-full bg-teal-600"
                    ></div>
                    Réservez votre rendez-vous
                </div>
                <h1
                    class="mb-4 text-3xl font-black leading-tight text-slate-950 md:text-4xl"
                >
                    Programmez votre
                    <span
                        class="bg-gradient-to-r from-teal-800 to-teal-500 bg-clip-text text-transparent"
                    >
                        visite médicale
                    </span>
                </h1>
                <p
                    class="mx-auto max-w-3xl text-sm leading-relaxed text-slate-600 md:text-base"
                >
                    Faites le premier pas vers une meilleure santé. Prenez
                    rendez-vous avec notre équipe médicale d'experts et
                    bénéficiez de soins de qualité.
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Left Side - Information (4 columns) -->
                <div class="hidden lg:block lg:col-span-3 space-y-8">
                    <!-- Benefits Card -->
                    <div
                        class="public-card p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-6">
                            Pourquoi MICLINIX ?
                        </h3>
                        <div class="space-y-6">
                            <div
                                v-for="benefit in benefits"
                                :key="benefit.title"
                                class="public-soft-panel flex items-start gap-4 transition duration-200 hover:border-teal-200 hover:bg-teal-50/60"
                            >
                                <div class="public-icon">
                                    {{ benefit.icon }}
                                </div>
                                <div>
                                    <h4
                                        class="font-semibold text-gray-900 mb-1"
                                    >
                                        {{ benefit.title }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ benefit.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div
                            class="public-card p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-4">
                            Urgence ?
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Pour une urgence médicale, contactez immédiatement
                            notre service d'urgence.
                        </p>
                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-4 rounded-xl border border-red-200 bg-red-50 p-4"
                            >
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Ligne d'urgence
                                    </h4>
                                    <p class="text-red-600 font-bold">237674000000</p>
                                </div>
                            </div>
                            <div
                                class="public-soft-panel flex items-center gap-4"
                            >
                                <div
                                    class="public-icon"
                                >
                                    <svg
                                        class="w-6 h-6"
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
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Assistance 24h/24 et 7j/7
                                    </h4>
                                    <p class="font-semibold text-teal-700">
                                        Toujours disponible pour les urgences
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Contact -->
                    <div
                        class="rounded-2xl border border-white/10 bg-slate-950 p-8 text-white shadow-xl shadow-slate-900/10"
                    >
                        <h3 class="text-2xl font-black mb-6">Coordonnées</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-teal-100">
                                        Location
                                    </p>
                                    <p class="font-semibold">
                                        123 Avenue Médicale
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
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
                                <div>
                                    <p class="text-sm text-teal-100">Téléphone</p>
                                    <p class="font-semibold">237674000000</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-teal-100">E-mail</p>
                                    <p class="font-semibold">
                                        reservation@miclinix.com
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Appointment Form (8 columns) -->
                <div class="lg:col-span-9">
                    <div
                        class="public-card p-6 sm:p-8 lg:p-10"
                    >
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-black text-gray-900 mb-1">
                                Réserver un rendez-vous
                            </h2>
                            <p class="text-gray-600">
                                Remplissez vos coordonnées et nous vous contacterons
                                sous 2 heures
                            </p>
                        </div>

                        <div
                            v-if="bookingNotice.show"
                            class="public-alert mb-6"
                            :class="
                                bookingNotice.type === 'error'
                                    ? 'border-red-200 bg-red-50 text-red-700'
                                    : 'border-emerald-200 bg-emerald-50 text-emerald-700'
                            "
                            role="status"
                        >
                            {{ bookingNotice.message }}
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-8">
                            <!-- Appointment Details -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3"
                                >
                                    <div
                                        class="public-icon h-8 w-8"
                                    >
                                        📅
                                    </div>
                                    Détails du rendez-vous
                                </h3>

                                <div class="grid md:grid-cols-2 gap-6 mt-5">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Spécialité médicale *
                                        </label>
                                        <select
                                            v-model="form.speciality"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                        >
                                            <option value="">
                                                Sélectionner une spécialité
                                            </option>
                                            <option
                                                v-for="specialty in specialties"
                                                :key="specialty.value"
                                                :value="specialty.value"
                                            >
                                                {{ specialty.icon }}
                                                {{ specialty.label }}
                                            </option>
                                        </select>
                                        <p v-if="getFieldError('speciality')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("speciality") }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Médecin préféré
                                        </label>
                                        <select
                                            v-model="form.doctorId"
                                            :disabled="submitting"
                                            class="public-form-field"
                                        >
                                            <option value="">
                                                Sélectionner un médecin (facultatif)
                                            </option>
                                            <option
                                                v-for="doctor in filteredDoctors"
                                                :key="doctor.id"
                                                :value="doctor.id"
                                            >
                                                {{ doctor.user.name }} -
                                                {{ doctor.speciality }}
                                            </option>
                                        </select>
                                        <p v-if="getFieldError('doctorId')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("doctorId") }}
                                        </p>
                                        <!-- <p class="text-sm text-gray-500">
                                            You can leave this blank and we'll
                                            assign the best available doctor
                                        </p> -->
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-5">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Date préférée *
                                        </label>
                                        <select
                                            v-model="form.preferredDate"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                        >
                                            <option value="">
                                                Sélectionner une date préférée
                                            </option>
                                            <option
                                                v-for="date in availableDates"
                                                :key="date.value"
                                                :value="date.value"
                                                :disabled="
                                                    date.status ===
                                                    'Complet'
                                                "
                                                :class="
                                                    date.status ===
                                                    'Complet'
                                                        ? 'text-red-500'
                                                        : date.status ===
                                                          'Places limitées'
                                                        ? 'text-yellow-600'
                                                        : ''
                                                "
                                            >
                                                {{ date.label }}
                                                {{
                                                    date.status === "Complet"
                                                        ? "(Complet)"
                                                        : date.status === "Places limitées"
                                                        ? "(Places limitées)"
                                                        : ""
                                                }}
                                            </option>
                                        </select>
                                        <p
                                            v-if="getFieldError('preferredDate')"
                                            class="text-sm font-semibold text-red-600 mt-1"
                                        >
                                            {{ getFieldError("preferredDate") }}
                                        </p>
                                        <p
                                            v-if="!form.doctorId"
                                            class="text-sm text-gray-500 mt-1"
                                        >
                                            Veuillez d'abord sélectionner un médecin pour voir
                                            les dates disponibles
                                        </p>
                                        <p
                                            v-else-if="
                                                availableDates.length === 0
                                            "
                                            class="text-sm text-red-500 mt-1"
                                        >
                                            Aucune date disponible pour ce médecin
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Heure préférée *
                                        </label>
                                        <select
                                            v-model="form.preferredTime"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                        >
                                            <option value="">
                                                Sélectionner un créneau horaire
                                            </option>
                                            <option
                                                v-for="time in availableTimeSlots"
                                                :key="time"
                                                :value="time"
                                            >
                                                {{ time }}
                                            </option>
                                        </select>
                                        <p v-if="getFieldError('preferredTime')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("preferredTime") }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3"
                                >
                                    <div
                                        class="public-icon h-8 w-8"
                                    >
                                        👤
                                    </div>
                                    Informations personnelles
                                </h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Prénom *
                                        </label>
                                        <input
                                            v-model="form.firstName"
                                            type="text"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                            placeholder="Entrez votre prénom"
                                        />
                                        <p v-if="getFieldError('firstName')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("firstName") }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Nom de famille *
                                        </label>
                                        <input
                                            v-model="form.lastName"
                                            type="text"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                            placeholder="Entrez votre nom de famille"
                                        />
                                        <p v-if="getFieldError('lastName')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("lastName") }}
                                        </p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Adresse e-mail *
                                        </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                            placeholder="votre@email.com"
                                        />
                                        <p v-if="getFieldError('email')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("email") }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Numéro de téléphone *
                                        </label>
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                            placeholder="+237 689261216"
                                        />
                                        <p v-if="getFieldError('phone')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("phone") }}
                                        </p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Genre *
                                        </label>
                                        <select
                                            v-model="form.gender"
                                            required
                                            :disabled="submitting"
                                            class="public-form-field"
                                        >
                                            <option value="">
                                                Sélectionnez le genre
                                            </option>
                                            <option value="male">Homme</option>
                                            <option value="female">Femme</option>
                                            <option value="other">Autre</option>
                                        </select>
                                        <p v-if="getFieldError('gender')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("gender") }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                        >
                                            Âge *
                                        </label>
                                        <input
                                            v-model="form.age"
                                            type="number"
                                            required
                                            min="1"
                                            max="99"
                                            :disabled="submitting"
                                            class="public-form-field"
                                            placeholder="Entrez votre âge"
                                        />
                                        <p v-if="getFieldError('age')" class="mt-1 text-sm font-semibold text-red-600">
                                            {{ getFieldError("age") }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                        <label
                                        class="block text-sm font-semibold text-gray-700 mb-1"
                                    >
                                        Notes supplémentaires
                                    </label>
                                    <textarea
                                        v-model="form.additionalNotes"
                                        :disabled="submitting"
                                        rows="4"
                                        placeholder="Veuillez décrire vos symptômes, raison de la visite, antécédents médicaux ou besoin particulier..."
                                        class="public-form-field resize-none"
                                    ></textarea>
                                    <p v-if="getFieldError('additionalNotes')" class="mt-1 text-sm font-semibold text-red-600">
                                        {{ getFieldError("additionalNotes") }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Fournir des informations détaillées nous aide
                                        à mieux vous servir
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-8 border-t border-gray-200">
                                <button
                                    type="submit"
                                    :disabled="submitting"
                                    class="public-primary-button w-full py-4 text-base"
                                >
                                    {{
                                        submitting
                                            ? "📋 Réservation..."
                                            : "📋 Réserver maintenant"
                                    }}
                                </button>
                                <p
                                    class="text-center text-sm text-gray-600 mt-4"
                                >
                                    En réservant un rendez-vous, vous acceptez nos
                                    <a
                                        href="#"
                                        class="font-bold text-teal-700 hover:underline"
                                        >termes et conditions</a
                                    >
                                    et notre
                                    <a
                                        href="#"
                                        class="font-bold text-teal-700 hover:underline"
                                        >politique de confidentialité</a
                                    >.
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 grid md:grid-cols-3 gap-6 text-center">
                        <div
                            class="public-card p-6"
                        >
                            <div
                                class="public-icon mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                Sécurisé et confidentiel
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Vos informations sont protégées
                            </p>
                        </div>
                        <div
                            class="public-card p-6"
                        >
                            <div
                                class="public-icon mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-teal-700"
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
                            <h4 class="font-semibold text-gray-900">
                                Réponse rapide
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Confirmation sous 2 heures
                            </p>
                        </div>
                        <div
                            class="public-card p-6"
                        >
                            <div
                                class="public-icon mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-teal-700"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                Médecins experts
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Soins médicaux spécialisés
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <Footer />

    <!-- Success Modal -->
    <div
        v-if="showSuccessModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click="closeModal"
    >
        <div
            class="public-card relative w-full max-w-md p-8 text-center"
            @click.stop
        >
            <!-- Close Button -->
            <button
                @click="closeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <!-- Success Icon -->
            <div
                class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-emerald-50"
            >
                <svg
                    class="h-10 w-10 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-black text-gray-900 mb-4">
                Rendez-vous réservé avec succès !
            </h2>

            <!-- Message -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                La confirmation de votre rendez-vous a été envoyée par e-mail.
                Vous pouvez également télécharger les détails de votre
                rendez-vous ici.
            </p>

            <!-- Appointment Details -->
            <div
                v-if="successAppointment"
                class="public-soft-panel mb-6 text-left"
            >
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID de réservation :</span>
                        <span class="font-semibold text-gray-900">{{
                            successAppointment.booking_id
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date :</span>
                        <span class="font-semibold text-gray-900">{{
                            formatFrenchDate(successAppointment.preferred_date)
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Heure :</span>
                        <span class="font-semibold text-gray-900">{{
                            successAppointment.preferred_time
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Spécialité :</span>
                        <span class="font-semibold text-gray-900">{{
                            successAppointment.speciality
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <button
                @click="downloadAppointmentDetails(successAppointment)"
                class="btn-primary mb-4 w-full py-3"
            >
                📄 Télécharger le reçu
            </button>

            <!-- Close Button -->
            <button
                @click="closeModal"
                class="btn-secondary w-full py-3"
            >
                Fermer
            </button>
        </div>
    </div>
</template>
