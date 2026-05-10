<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/AppInput.vue';

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();

const form = reactive({
    email: 'admin@firstdecision.test',
    password: 'password',
});
const errors = ref({});
const apiError = ref('');

async function submit() {
    errors.value = {};
    apiError.value = '';

    if (!form.email) errors.value.email = 'E-mail é obrigatório.';
    if (!form.password) errors.value.password = 'Senha é obrigatória.';
    if (Object.keys(errors.value).length) return;

    try {
        await auth.login({ ...form, device_name: 'web' });
        router.push(route.query.redirect || { name: 'products.index' });
    } catch (err) {
        const data = err.response?.data;
        if (data?.errors) {
            errors.value = Object.fromEntries(
                Object.entries(data.errors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v]),
            );
        }
        apiError.value = data?.message || 'Não foi possível autenticar.';
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
        <div class="w-full max-w-sm bg-white rounded-lg shadow p-6 space-y-5">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">firstDecision</h1>
                <p class="text-sm text-slate-500 mt-1">Acesse sua conta para gerenciar os produtos.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <AppInput
                    v-model="form.email"
                    type="email"
                    label="E-mail"
                    autocomplete="username"
                    required
                    :error="errors.email"
                />
                <AppInput
                    v-model="form.password"
                    type="password"
                    label="Senha"
                    autocomplete="current-password"
                    required
                    :error="errors.password"
                />

                <p v-if="apiError" class="text-sm text-red-600">{{ apiError }}</p>

                <AppButton type="submit" :loading="auth.loading" class="w-full">
                    Entrar
                </AppButton>
            </form>

            <p class="text-xs text-slate-400 text-center">
                Usuário padrão (seed): <code>admin@firstdecision.test</code> / <code>password</code>
            </p>
        </div>
    </div>
</template>
