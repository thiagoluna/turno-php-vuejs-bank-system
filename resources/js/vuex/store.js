import Vue from 'vue';
import Vuex from 'vuex';

import Deposits from './modules/deposits/deposits';
import Preloader from "./preloader/preloader";
import Transactions from "./modules/transactions/transactions";
import Auth from "./modules/auth/auth";

Vue.use(Vuex)
Vue.config.devtools = true

export default new Vuex.Store({
    modules: {
        deposits: Deposits,
        preloader: Preloader,
        transactions: Transactions,
        auth: Auth,
    }
});
