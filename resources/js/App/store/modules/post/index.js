import http from '@/services/http'

export default {
    namespaced: true,

    state: {
        posts: [],
        popular_posts: [],
        post: {
            author: {},
            media: [],
            likers: [],
            comments: []
        }
    },

    getters: {
        POSTS: (state) => {
            return state.posts;
        },
        POPULAR_POSTS: (state) => {
            return state.popular_posts;
        },
        POST: (state) => {
            return state.post;
        },
        GET_POST_BY_ID: state => id => {
            return state.posts.find(p => p.id === id);
        }
    },

    mutations: {
        SET_POSTS: (state, payload) => {
            state.posts = payload
        },
        SET_POPULAR_POSTS: (state, payload) => {
            state.popular_posts = payload
        },
        PUSH_POSTS: (state, payload) => {
            Object.keys(payload).map(function (objectKey, index) {
                var value = payload[objectKey];
                state.posts.push(value);
            });
        },
        SET_POST: (state, payload) => {
            state.post = payload;
        },
        PUSH_POST: (state, payload) => {
            state.posts.unshift(payload);
        },
        DELETE_POST: (state, id) => {
            let index = state.posts.map(x => {
                return x.id;
            }).indexOf(id);
            state.posts.splice(index, 1);
        },
        SET_LIKE_UNLIKE_POST: (state, payload) => {
            let post = state.posts.find(p => p.id === payload.id);

            if (typeof post === 'undefined') {
                post = state.post;
            }

            post.isLiked = !post.isLiked;

            if (post.isLiked) {
                post.likers.unshift(payload.auth_user);
            } else {
                let index = post.likers.map(x => {
                    return x.id;
                }).indexOf(payload.auth_user.id);
                post.likers.splice(index, 1);
            }
        },
        SET_BOOKMARK_UNBOOKMARK_POST: (state, payload) => {
            let post = state.posts.find(p => p.id === payload.id);

            if (typeof post === 'undefined') {
                post = state.post;
            }

            post.isBookmarked = !post.isBookmarked;
        }
    },

    actions: {
        GET_POSTS: async ({commit}, page) => {
            const {data} = await http.get(`/posts?page=${page}`);
            if (page === 1) {
                commit('SET_POSTS', data.data)
            } else {
                commit('PUSH_POSTS', data.data)
            }

            return data;
        },
        GET_POSTS_BY_POPULARITY: async ({commit}) => {
            const {data} = await http.get(`/posts/by-popularity`);
            commit('SET_POPULAR_POSTS', data.data);
            return data;
        },
        FIND_POST_BY_ID: async ({commit, getters, state}, id) => {
            if (id === state.post.id) {
                return state.post
            }

            let post = getters.GET_POST_BY_ID(id);

            if (post) {
                commit('SET_POST', post);
                return post
            } else {
                const {data} = await http.get(`/posts/${id}`);
                commit('SET_POST', data.data);
                return data.data;
            }
        },
        UPDATE_POST: async ({commit}, post) => {
            const {data} = await http.patch(`/posts/${post.id}`, {caption: post.caption});
            commit('SET_POST', data.data);
            return data.data;
        },
        DELETE_POST: async ({commit}, post_id) => {
            const {data} = await http.delete(`/posts/${post_id}`);
            commit('DELETE_POST', post_id);
            return data.data;
        },
        LIKE_UNLIKE_POST: async ({state, commit, rootState}, id) => {
            await http.post(`/posts/${id}/like`);
            commit('SET_LIKE_UNLIKE_POST', {id: id, auth_user: rootState.user.auth_user});

        },
        BOOKMARK_UNBOOKMARK_POST:
            async ({state, commit, rootState}, id) => {
                await http.post(`/posts/${id}/bookmark`);
                commit('SET_BOOKMARK_UNBOOKMARK_POST', {id: id, auth_user: rootState.user.auth_user});
            },
        UPLOAD_POST:
            async ({commit}, formData) => {
                const {data} = await http.post(`/posts`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                commit('PUSH_POST', data.data);

                return data;
            },
        ADD_COMMENT:
            async ({commit}, details) => {
                const {data} = await http.post(`/posts/${details.post_id}/comments/store`, {
                    body: details.comment,
                    parent_id: details.replyCommentId
                });
                return data;
            },
        LIKE_UNLIKE_COMMENT:
            async ({state, commit, rootState}, id) => {
                await http.post(`/comments/${id}/like`);
            }
    }
}