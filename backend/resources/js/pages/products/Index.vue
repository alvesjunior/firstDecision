<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useProductsStore } from '@/stores/products';
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/AppInput.vue';
import AppPagination from '@/components/AppPagination.vue';
import AppModal from '@/components/AppModal.vue';

const products = useProductsStore();
const router = useRouter();

const deleting = ref(null);
const deleteLoading = ref(false);
const deleteError = ref('');

onMounted(() => products.fetch({ page: 1 }));

function search() {
    products.fetch({ page: 1 });
}

function clearFilters() {
    products.resetFilters();
    products.fetch({ page: 1 });
}

function changePage(page) {
    products.fetch({ page });
}

function formatPrice(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

function confirmDelete(product) {
    deleteError.value = '';
    deleting.value = product;
}

async function performDelete() {
    if (!deleting.value) return;
    deleteLoading.value = true;
    try {
        await products.remove(deleting.value.id);
        deleting.value = null;
    } catch (err) {
        deleteError.value = err.response?.data?.message || 'Falha ao remover.';
    } finally {
        deleteLoading.value = false;
    }
}
</script>

<template>
    <section class="space-y-5">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Produtos</h1>
                <p class="text-sm text-slate-500">Gerencie o catálogo de produtos.</p>
            </div>
            <AppButton @click="router.push({ name: 'products.create' })">
                + Novo produto
            </AppButton>
        </header>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 grid gap-3 md:grid-cols-6">
            <div class="md:col-span-2">
                <AppInput
                    v-model="products.filters.search"
                    label="Buscar"
                    placeholder="Nome ou descrição..."
                    @keydown.enter="search"
                />
            </div>
            <AppInput
                v-model="products.filters.min_price"
                label="Preço mín."
                type="number"
                step="0.01"
                min="0"
            />
            <AppInput
                v-model="products.filters.max_price"
                label="Preço máx."
                type="number"
                step="0.01"
                min="0"
            />
            <AppInput
                v-model="products.filters.min_stock"
                label="Estoque mín."
                type="number"
                min="0"
            />
            <AppInput
                v-model="products.filters.max_stock"
                label="Estoque máx."
                type="number"
                min="0"
            />
            <div class="md:col-span-6 flex justify-end gap-2">
                <AppButton variant="ghost" @click="clearFilters">Limpar</AppButton>
                <AppButton @click="search">Aplicar filtros</AppButton>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left">Nome</th>
                        <th class="px-4 py-2 text-left">Descrição</th>
                        <th class="px-4 py-2 text-right">Preço</th>
                        <th class="px-4 py-2 text-right">Estoque</th>
                        <th class="px-4 py-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="products.loading">
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">Carregando...</td>
                    </tr>
                    <tr v-else-if="!products.items.length">
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                    <tr
                        v-for="product in products.items"
                        :key="product.id"
                        class="border-t border-slate-100 hover:bg-slate-50"
                    >
                        <td class="px-4 py-2 font-medium text-slate-900">{{ product.name }}</td>
                        <td class="px-4 py-2 text-slate-600 max-w-md truncate">
                            {{ product.description || '—' }}
                        </td>
                        <td class="px-4 py-2 text-right">{{ formatPrice(product.price) }}</td>
                        <td class="px-4 py-2 text-right">
                            <span
                                :class="[
                                    'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                    product.stock === 0
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-emerald-100 text-emerald-700',
                                ]"
                            >
                                {{ product.stock }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right space-x-2 whitespace-nowrap">
                            <RouterLink
                                :to="{ name: 'products.show', params: { id: product.id } }"
                                class="text-indigo-600 hover:underline"
                            >
                                Ver
                            </RouterLink>
                            <RouterLink
                                :to="{ name: 'products.edit', params: { id: product.id } }"
                                class="text-amber-600 hover:underline"
                            >
                                Editar
                            </RouterLink>
                            <button
                                type="button"
                                class="text-red-600 hover:underline"
                                @click="confirmDelete(product)"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="border-t border-slate-100 px-4 py-3">
                <AppPagination :meta="products.meta" @change="changePage" />
            </div>
        </div>

        <AppModal :open="!!deleting" title="Confirmar exclusão" @close="deleting = null">
            <p class="text-sm text-slate-700">
                Tem certeza que deseja excluir o produto
                <strong>{{ deleting?.name }}</strong>? Essa ação não pode ser desfeita.
            </p>
            <p v-if="deleteError" class="mt-2 text-sm text-red-600">{{ deleteError }}</p>
            <template #footer>
                <AppButton variant="ghost" @click="deleting = null">Cancelar</AppButton>
                <AppButton variant="danger" :loading="deleteLoading" @click="performDelete">
                    Excluir
                </AppButton>
            </template>
        </AppModal>
    </section>
</template>
