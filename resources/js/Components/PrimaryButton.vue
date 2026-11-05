<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-xl border border-transparent bg-teal-700 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-teal-900/10 transition duration-200 hover:bg-teal-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 active:bg-teal-900 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <slot />
    </button>
</template>
