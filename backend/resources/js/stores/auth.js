import { defineStore } from 'pinia';
import { authApi } from '@/services/auth';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        loading: false,
    }),

    getters: {
        isAuthenticated: (state) => Boolean(state.token),
    },

    actions: {
        async login(credentials) {
            this.loading = true;
            try {
                const { data } = await authApi.login(credentials);
                this.setSession(data.data.token, data.data.user);
                return data;
            } finally {
                this.loading = false;
            }
        },

        async register(payload) {
            this.loading = true;
            try {
                const { data } = await authApi.register(payload);
                this.setSession(data.data.token, data.data.user);
                return data;
            } finally {
                this.loading = false;
            }
        },

        async restore() {
            if (!this.token) return;
            try {
                const { data } = await authApi.me();
                this.user = data.data;
            } catch {
                this.clear();
            }
        },

        async logout() {
            try {
                await authApi.logout();
            } catch {
                // ignore — encerrar sessão local mesmo em falha de rede
            }
            this.clear();
        },

        setSession(token, user) {
            this.token = token;
            this.user = user;
            localStorage.setItem('auth_token', token);
        },

        clear() {
            this.token = null;
            this.user = null;
            localStorage.removeItem('auth_token');
        },
    },
});
