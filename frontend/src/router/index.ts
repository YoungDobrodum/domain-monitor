import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    {
        path: '/domains',
        component: () => import('../pages/Domains.vue'),
        beforeEnter: (to: any, from: any, next: (arg0: string | undefined) => any) => {
            // @ts-ignore
            localStorage.getItem('token') ? next() : next('/login');
        }

    },
    {
        path: '/domains/:id/checks',
        component: () => import('../pages/Checks.vue'),
        name: 'checks'
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes
});
