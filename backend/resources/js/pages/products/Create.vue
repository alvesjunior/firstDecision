<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useProductsStore } from '@/stores/products';
import { useProductForm } from '@/composables/useProductForm';
import ProductForm from '@/components/ProductForm.vue';

const router = useRouter();
const products = useProductsStore();
const { form, errors, validate, payload, setServerErrors } = useProductForm();
const loading = ref(false);

async function submit() {
    if (!validate()) return;
    loading.value = true;
    try {
        await products.create(payload());
        router.push({ name: 'products.index' });
    } catch (err) {
        const data = err.response?.data;
        if (data?.errors) setServerErrors(data.errors);
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <section class="space-y-4 max-w-2xl">
        <header>
            <h1 class="text-2xl font-semibold text-slate-900">Novo produto</h1>
            <p class="text-sm text-slate-500">Preencha os dados abaixo para cadastrar.</p>
        </header>

        <ProductForm
            :form="form"
            :errors="errors"
            submit-label="Cadastrar"
            :loading="loading"
            @submit="submit"
            @cancel="router.push({ name: 'products.index' })"
        />
    </section>
</template>
