import { defineStore } from 'pinia';
import { productsApi } from '@/services/products';

const defaultFilters = () => ({
    search: '',
    min_price: '',
    max_price: '',
    min_stock: '',
    max_stock: '',
    sort: 'created_at',
    direction: 'desc',
    per_page: 10,
    page: 1,
});

export const useProductsStore = defineStore('products', {
    state: () => ({
        items: [],
        meta: null,
        filters: defaultFilters(),
        loading: false,
    }),

    actions: {
        async fetch(overrides = {}) {
            this.loading = true;
            this.filters = { ...this.filters, ...overrides };
            try {
                const params = Object.fromEntries(
                    Object.entries(this.filters).filter(([, v]) => v !== '' && v !== null && v !== undefined),
                );
                const { data } = await productsApi.list(params);
                this.items = data.data;
                this.meta = data.meta || null;
            } finally {
                this.loading = false;
            }
        },

        resetFilters() {
            this.filters = defaultFilters();
        },

        async create(payload) {
            const { data } = await productsApi.create(payload);
            return data;
        },

        async update(id, payload) {
            const { data } = await productsApi.update(id, payload);
            return data;
        },

        async remove(id) {
            await productsApi.remove(id);
            this.items = this.items.filter((item) => item.id !== id);
        },

        async getOne(id) {
            const { data } = await productsApi.get(id);
            return data.data;
        },
    },
});
