import Vue from 'vue'
import Vuex from 'vuex'
// import modules from './modules'
import user from './modules/user'
import post from './modules/post'
import explorer from './modules/explorer'
import messenger from './modules/messenger'
import report from './modules/report'
import page from './modules/page'
import http from '../services/http'

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        // modules,
        user,
        post,
        explorer,
        messenger,
        report,
        page,
    },

    state: {
        pageTitle: 'Photo Life',
    },

    getters: {},

    mutations: {},

    actions: {
        setTitle({state}, value) {
            state.pageTitle = value
        },
        GET_SEARCH_DATA: async ({commit}, query) => {
            const {data} = await http.get(`/search/${query}`);
            return data;
        },
        GET_RANDOM_QUOTE: async ({commit}) => {
            const {data} = await http.get('/quotes/random');
            return data;
        }
    }
});

http.onError = response => {
    if (response.status === 403) {
        store.dispatch('notifications/error', response.body.message)
        return true
    }
}