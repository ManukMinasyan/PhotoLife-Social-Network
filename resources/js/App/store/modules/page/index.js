import http from '@/services/http'

export default {
    namespaced: true,

    state: {
        pages: [],
        page: {}
    },

    getters: {
        PAGES(state) {
            return state.pages;
        },
        PAGE(state) {
            return state.page;
        }
    },

    mutations: {
        SET_PAGES: (state, payload) => {
            state.pages = payload
        },
        SET_PAGE: (state, payload) => {
            state.page = payload
        }
    },
    actions: {
        GET_PAGES: async ({commit}) => {
            const {data} = await http.get('/pages');
            commit('SET_PAGES', data.data);
        },
        GET_PAGE_BY_ALIAS: async ({commit}, alias) => {
            const {data} = await http.get(`/pages/${alias}`);
            commit('SET_PAGE', data.data);
            return data.data;
        }
    }
}