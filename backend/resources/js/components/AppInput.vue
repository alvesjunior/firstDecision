<script setup>
defineProps({
    modelValue: { type: [String, Number], default: '' },
    type: { type: String, default: 'text' },
    label: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
    step: { type: String, default: undefined },
    min: { type: [String, Number], default: undefined },
    autocomplete: { type: String, default: undefined },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <label class="block">
        <span v-if="label" class="block text-sm font-medium text-slate-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </span>
        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :step="step"
            :min="min"
            :autocomplete="autocomplete"
            :class="[
                'block w-full rounded-md border shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 text-sm bg-white',
                error ? 'border-red-400' : 'border-slate-300',
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </label>
</template>
