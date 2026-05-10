<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useProductsStore } from '@/stores/products';
import AppButton from '@/components/AppButton.vue';

const props = defineProps({ id: { type: [String, Number], required: true } });
const router = useRouter();
const products = useProductsStore();
const product = ref(null);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
    try {
        product.value = await products.getOne(props.id);
    } catch (err) {
        error.value = err.response?.data?.message || 'Produto não encontrado.';
    } finally {
        loading.value = false;
    }
});

function formatPrice(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}
</script>

<template>
    <section class="space-y-4 max-w-2xl">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-900">Detalhes do produto</h1>
            <AppButton variant="ghost" @click="router.push({ name: 'products.index' })">
                ← Voltar
            </AppButton>
        </header>

        <p v-if="loading" class="text-slate-500 text-sm">Carregando...</p>
        <p v-else-if="error" class="text-red-600 text-sm">{{ error }}</p>

        <div v-else class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 space-y-3">
            <div>
                <p class="text-xs uppercase text-slate-500">Nome</p>
                <p class="text-lg font-medium text-slate-900">{{ product.name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-slate-500">Descrição</p>
                <p class="text-slate-700 whitespace-pre-line">{{ product.description || '—' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase text-slate-500">Preço</p>
                    <p class="text-slate-900 font-medium">{{ formatPrice(product.price) }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Estoque</p>
                    <p class="text-slate-900 font-medium">{{ product.stock }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <p>Criado em: {{ new Date(product.created_at).toLocaleString('pt-BR') }}</p>
                <p>Atualizado em: {{ new Date(product.updated_at).toLocaleString('pt-BR') }}</p>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <RouterLink
                    :to="{ name: 'products.edit', params: { id: product.id } }"
                    class="rounded-md bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 text-sm font-medium"
                >
                    Editar
                </RouterLink>
            </div>
        </div>
    </section>
</template>
