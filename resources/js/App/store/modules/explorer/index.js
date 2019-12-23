import http from '@/services/http'

export default {
    namespaced: true,

    state: {
        posts: [],
        total_posts: 0
    },

    getters: {
        POSTS(state) {
            return state.posts;
        },
        TOTAL_POSTS(state) {
            return state.total_posts;
        }
    },

    mutations: {
        PUSH_POSTS: (state, payload) => {
            state.posts.push(...payload)
        },
        SET_TOTAL_POSTS: (state, payload) => {
            state.total_posts = payload;
        },
        UNSET_POSTS: (state) => {
            state.posts = [];
        }
    },
    actions: {
        GET_POSTS: async ({commit}, details) => {
            let hashtag = '?';

            if (details.tag) {
                hashtag = `?tag=${details.tag}&`
            }

            if (details.page === 1) {
                commit('UNSET_POSTS');
            }

            const {data} = await http.get(`/explorer/posts${hashtag}page=${details.page}`);
            commit('PUSH_POSTS', data.data);
            commit('SET_TOTAL_POSTS', data.meta.total);
            return data;
        },
    }
}