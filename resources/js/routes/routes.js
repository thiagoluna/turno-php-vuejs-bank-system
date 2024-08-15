import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../vuex/store';

import DepositsComponent from "../components/admin/pages/deposits/DepositsComponent.vue";
import AdminComponent from "../components/admin/AdminComponent.vue";
import HomeComponent from "../components/customer/pages/home/HomeComponent.vue";
import SiteComponent from "../components/customer/pages/SiteComponent.vue";
import ExpensesComponent from "../components/customer/pages/home/ExpensesComponent.vue";
import ChecksComponent from "../components/customer/pages/home/ChecksComponent.vue";
import AddExpenseComponent from "../components/customer/pages/home/expenses/AddExpenseComponent.vue";
import AddDepositComponent from "../components/customer/pages/home/deposit/AddDepositComponent.vue";
import EditDepositComponent from "../components/admin/pages/deposits/EditDepositComponent.vue";
import SignUpComponent from "../components/login/pages/SignUpComponent.vue";
import SignInComponent from "../components/login/pages/SignInComponent.vue";

Vue.use(VueRouter)

const routes = [
    {path: '/', component: SignUpComponent, name: 'signup'},
    {path: '/signin', component: SignInComponent, name: 'signin'},
    {
        path: '/admin',
        component: AdminComponent, name: 'admin',
        meta: {auth: true, isAdmin: true},
        children: [
            {path: 'deposits', component: DepositsComponent, name: 'admin.deposits'},
            {path: 'deposits/:id/edit', component: EditDepositComponent, name: 'admin.deposits.edit', props: true},
        ]
    },
    {
        path: '/',
        component: SiteComponent,
        meta: {auth: true},
        children: [
            {path: '/home', component: HomeComponent, name: 'home'},
            {path: '/expenses', component: ExpensesComponent, name: 'expenses'},
            {path: '/expenses/add', component: AddExpenseComponent, name: 'expenses.add'},
            {path: '/checks', component: ChecksComponent, name: 'checks'},
            {path: '/checks/add', component: AddDepositComponent, name: 'checks.add'},
        ]
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth) && !store.state.auth.authenticated) {
        store.commit('CHANGE_URL_BACK', to.name);

        return router.push({name: 'signup'});
    }

    if (to.matched.some(record => record.meta.auth) && store.state.auth.authenticated &&
        to.matched.some(record => record.meta.isAdmin) && !store.state.auth.me.is_admin
    ) {
        store.commit('CHANGE_URL_BACK', to.name);

        return router.push({name: 'home'});
    }

    next()
})
export default router;
