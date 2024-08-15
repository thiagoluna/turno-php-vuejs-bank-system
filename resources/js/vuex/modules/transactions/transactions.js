import axios from "axios";

export default {
    state: {
        items: {
            data: []
        },
    },
    mutations: {
        LOAD_TRANSACTIONS (state, transactions) {
            state.items = transactions
        }
    },
    actions: {
        loadTransactions (context, params) {
            context.commit('PRELOADER', true)

            axios.get('/api/v1/transactions', {params})
                .then(response => {
                    context.commit('LOAD_TRANSACTIONS', response.data)
                })
                .catch(errors => {
                    console.log(errors)
                })
                .finally(() => context.commit('PRELOADER', false))
        },

        addPurchase (context, params) {
            context.commit('PRELOADER', true)

            return new Promise((resolve, reject) => {
                axios.post('/api/v1/purchase', params)
                    .then(response => resolve())
                    .catch(errors => {
                        console.log(errors)
                        reject(errors)
                    })
                    .finally(() => context.commit('PRELOADER', false))
            })
        }
    },
    getters: {

    }
}
