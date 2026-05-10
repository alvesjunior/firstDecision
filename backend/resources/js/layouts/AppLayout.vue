<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

async function handleLogout() {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <RouterLink :to="{ name: 'products.index' }" class="text-lg font-semibold text-slate-900">
                    firstDecision
                </RouterLink>
                <nav class="flex items-center gap-4 text-sm">
                    <RouterLink
                        :to="{ name: 'products.index' }"
                        class="text-slate-600 hover:text-slate-900"
                        active-class="text-indigo-600 font-medium"
                    >
                        Produtos
                    </RouterLink>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500">{{ auth.user?.name }}</span>
                    <button
                        type="button"
                        class="rounded-md bg-slate-100 px-3 py-1.5 text-slate-700 hover:bg-slate-200"
                        @click="handleLogout"
                    >
                        Sair
                    </button>
                </nav>
            </div>
        </header>

        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
            <RouterView />
        </main>
    </div>
</template>
