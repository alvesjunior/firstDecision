import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/Login.vue'),
        meta: { layout: 'guest' },
    },
    {
        path: '/',
        component: () => import('@/layouts/AppLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            { path: '', redirect: { name: 'products.index' } },
            {
                path: 'products',
                name: 'products.index',
                component: () => import('@/pages/products/Index.vue'),
            },
            {
                path: 'products/create',
                name: 'products.create',
                component: () => import('@/pages/products/Create.vue'),
            },
            {
                path: 'products/:id',
                name: 'products.show',
                component: () => import('@/pages/products/Show.vue'),
                props: true,
            },
            {
                path: 'products/:id/edit',
                name: 'products.edit',
                component: () => import('@/pages/products/Edit.vue'),
                props: true,
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: { name: 'products.index' },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    const auth = useAuthStore();

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return { name: 'login', query: { redirect: to.fullPath } };
    }

    if (to.name === 'login' && auth.isAuthenticated) {
        return { name: 'products.index' };
    }
});

export default router;
