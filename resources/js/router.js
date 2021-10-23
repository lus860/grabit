import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const routes = [
    {
        path: '/',
        name: 'home',
        meta:{layout:'Main'},
        component:()=>import('./views/Home')
    },
    {
        path: '/signin',
        name: 'signin',
        meta:{layout:'Main'},
        component:()=>import('./views/Signin')
    }
];

export default new Router({
    mode: 'history',
    routes: routes
})
