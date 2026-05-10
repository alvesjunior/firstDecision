<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useProductsStore } from '@/stores/products';
import { useProductForm } from '@/composables/useProductForm';
import ProductForm from '@/components/ProductForm.vue';

const props = defineProps({ id: { type: [String, Number], required: true } });
const router = useRouter();
const products = useProductsStore();
const { form, errors, validate, payload, setServerErrors } = useProductForm();
const loading = ref(false);
const fetching = ref(true);
const fetchError = ref('');

onMounted(async () => {
    try {
        const product = await products.getOne(props.id);
        Object.assign(form, {
            name: product.name,
            description: product.description ?? '',
            price: product.price,
            stock: product.stock,
        });
    } catch (err) {
        fetchError.value = err.response?.data?.message || 'Não foi possível carregar o produto.';
    } finally {
        fetching.value = false;
    }
});

async function submit() {
    if (!validate()) return;
    loading.value = true;
    try {
        await products.update(props.id, payload());
        router.push({ name: 'products.show', params: { id: props.id } });
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
            <h1 class="text-2xl font-semibold text-slate-900">Editar produto</h1>
            <p class="text-sm text-slate-500">Atualize os dados do produto.</p>
        </header>

        <p v-if="fetching" class="text-slate-500 text-sm">Carregando...</p>
        <p v-else-if="fetchError" class="text-red-600 text-sm">{{ fetchError }}</p>

        <ProductForm
            v-else
            :form="form"
            :errors="errors"
            submit-label="Salvar alterações"
            :loading="loading"
            @submit="submit"
            @cancel="router.back()"
        />
    </section>
</template>
