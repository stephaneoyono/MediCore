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
        class="inline-flex items-center rounded-xl border border-transparent bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-red-900/10 transition duration-200 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-800 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <slot />
    </button>
</template>
