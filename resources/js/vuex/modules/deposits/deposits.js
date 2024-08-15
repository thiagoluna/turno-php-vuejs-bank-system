import axios from "axios";

const CONFIGS = {
    headers: {
        'content-type': 'multipart/form-data',
    }
}
export default {
    state: {
        items: {
            data: []
        },
    },
    mutations: {
        LOAD_DEPOSITS (state, deposits) {
            state.items = deposits
        }
    },
    actions: {
        loadDeposits (context) {
            context.commit('PRELOADER', true)

            axios.get('/api/v1/admin/deposits')
                .then(response => {
                    context.commit('LOAD_DEPOSITS', response.data)
                })
                .catch(errors => {
                    console.log(errors)
                })
                .finally(() => context.commit('PRELOADER', false))
        },

        loadDepositById (context, id) {
            context.commit('PRELOADER', true)

            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/admin/deposits/${id}`)
                    .then(response => resolve(response.data))
                    .catch(error => reject(error))
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        approveDeposit (context, id) {
            context.commit('PRELOADER', true)

            return new Promise((resolve, reject) => {
                axios.put(`/api/v1/admin/deposits/approve/${id}`)
                    .then(response => resolve())
                    .catch(error => reject(error))
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        rejectDeposit (context, id) {
            context.commit('PRELOADER', true)

            return new Promise((resolve, reject) => {
                axios.put(`/api/v1/admin/deposits/reject/${id}`)
                    .then(response => resolve())
                    .catch(error => reject(error))
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        addDeposit (context, formData){
            context.commit('PRELOADER', true)

            return new Promise((resolve, reject) => {
                axios.post('/api/v1/deposit', formData, CONFIGS)
                    .then(response => resolve())
                    .catch(errors => {
                        console.log(errors)
                        reject(errors)
                    })
                    .finally(() => context.commit('PRELOADER', false))
            })
        },
    },
    getters: {

    }
}
