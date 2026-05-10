<script setup>
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/AppInput.vue';
import AppTextarea from '@/components/AppTextarea.vue';

defineProps({
    form: { type: Object, required: true },
    errors: { type: Object, required: true },
    submitLabel: { type: String, default: 'Salvar' },
    loading: { type: Boolean, default: false },
});

defineEmits(['submit', 'cancel']);
</script>

<template>
    <form class="space-y-4 bg-white p-5 rounded-lg shadow-sm border border-slate-200" @submit.prevent="$emit('submit')">
        <AppInput
            v-model="form.name"
            label="Nome"
            placeholder="Ex.: Cadeira ergonômica"
            required
            :error="errors.name"
        />
        <AppTextarea
            v-model="form.description"
            label="Descrição"
            placeholder="Descrição opcional do produto"
            :error="errors.description"
        />
        <div class="grid gap-4 md:grid-cols-2">
            <AppInput
                v-model="form.price"
                label="Preço (R$)"
                type="number"
                step="0.01"
                min="0"
                required
                :error="errors.price"
            />
            <AppInput
                v-model="form.stock"
                label="Estoque"
                type="number"
                min="0"
                required
                :error="errors.stock"
            />
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <AppButton type="button" variant="ghost" @click="$emit('cancel')">Cancelar</AppButton>
            <AppButton type="submit" :loading="loading">{{ submitLabel }}</AppButton>
        </div>
    </form>
</template>
