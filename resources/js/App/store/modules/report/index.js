import http from '@/services/http'

export default {
    namespaced: true,

    state: {
        types: []
    },

    getters: {
        TYPES(state) {
            return state.types;
        }
    },

    mutations: {
        SET_TYPES: (state, payload) => {
            state.types = payload
        }
    },
    actions: {
        GET_TYPES: async ({commit}) => {
            const {data} = await http.get('/report/types');
            commit('SET_TYPES', data.data);
        },
        REPORT_POST: async ({commit}, {post_id, report_type_id}) => {
            const {data} = await http.post(`/report/post/${post_id}/${report_type_id}`);
            return data;
        }
    }
}