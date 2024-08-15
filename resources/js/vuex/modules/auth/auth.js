import axios from "axios";
import { NAME_TOKEN } from "../../../configs/config";

export default {
    state: {
        me: {},
        authenticated: false,
        urlBack: '',
    },

    mutations: {
        AUTH_USER_OK (state, user) {
            state.authenticated = true;
            state.me = user;
        },
        AUTH_USER_LOGOUT (state) {
            state.authenticated = false;
            state.me = {};
            state.urlBack = 'signin';
        },
        CHANGE_URL_BACK (state, url) {
            state.urlBack = url;
        }
    },

    actions: {
        signin (context, formData) {
            context.commit('PRELOADER', true);

            return new Promise((resolve, reject) => {
                axios.post('/api/auth', formData)
                    .then(response => {
                        context.commit('AUTH_USER_OK', response.data.data.user);
                        const token = response.data.data.token;
                        localStorage.setItem(NAME_TOKEN, token)
                        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                        resolve(response.data.data.user)
                    })
                    .catch(errors => {
                        console.log(errors)
                        reject(errors)
                    })
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        signup (context, formData) {
            context.commit('PRELOADER', true);

            return new Promise((resolve, reject) => {
                axios.post('/api/v1/user', formData)
                    .then(response => resolve(response))
                    .catch(errors => {
                        console.log(errors)
                        reject(errors)
                    })
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        checkLogin (context) {
            context.commit('PRELOADER', true);

            return new Promise((resolve, reject) => {
                const token = localStorage.getItem(NAME_TOKEN)
                if (!token) return reject()

                axios.get('/api/me')
                    .then(response => {
                        context.commit('AUTH_USER_OK', response.data.data)
                        resolve()
                    })
                    .catch(() => reject())
                    .finally(() => context.commit('PRELOADER', false))
            })
        },

        logout (context) {
            localStorage.removeItem(NAME_TOKEN)
            context.commit('AUTH_USER_LOGOUT')
        }
    }
}
