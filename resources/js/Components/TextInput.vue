<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-xl border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
