<script setup>
defineProps({
    modelValue: { type: String, default: '' },
    label: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    error: { type: String, default: '' },
    rows: { type: [String, Number], default: 4 },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <label class="block">
        <span v-if="label" class="block text-sm font-medium text-slate-700 mb-1">{{ label }}</span>
        <textarea
            :value="modelValue"
            :placeholder="placeholder"
            :rows="rows"
            :class="[
                'block w-full rounded-md border shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 text-sm bg-white',
                error ? 'border-red-400' : 'border-slate-300',
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </label>
</template>
