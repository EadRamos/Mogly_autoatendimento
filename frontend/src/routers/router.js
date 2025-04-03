import { createRouter, createWebHistory } from 'vue-router';

import HelloWorld from '@/components/HelloWorld.vue';
//import { storeToRefs } from "pinia";

const routes = [
    {
        path: '/',
        component: HelloWorld,
        name: 'HelloWorld',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// checagem para rotas protegidas
router.beforeEach((to, from, next) => {
    // metodos de login
    const auth = !!localStorage.getItem('token');

    if (to.meta.requiresAuth && !auth) return next({ name: 'Home'});

    next();
});

export default router;