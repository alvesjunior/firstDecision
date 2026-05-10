import http from './http';

export const productsApi = {
    list(params = {}) {
        return http.get('/products', { params });
    },
    get(id) {
        return http.get(`/products/${id}`);
    },
    create(payload) {
        return http.post('/products', payload);
    },
    update(id, payload) {
        return http.put(`/products/${id}`, payload);
    },
    remove(id) {
        return http.delete(`/products/${id}`);
    },
};
