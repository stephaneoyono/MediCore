import { createRouter, createWebHashHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../pages/Home.vue')
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/Login.vue')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../pages/Register.vue')
    }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

export default router;