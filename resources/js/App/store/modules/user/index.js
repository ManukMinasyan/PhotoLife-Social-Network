import http from '@/services/http'

// Local Storage Service
import LocalStorageService from '@/services/localstorageservice'
const localStorageService = LocalStorageService.getService()

export default {
    namespaced: true,

    state: {
        user: {},
        auth_user: {},
        notifications: [],
        posts: []
    },

    getters: {
        AUTH_USER(state) {
            return state.auth_user;
        },
        NOTIFICATIONS(state) {
            return state.notifications;
        },
        USER(state) {
            return state.user;
        },
        POSTS(state) {
            return state.posts;
        }
    },

    mutations: {
        CREATE_SESSION(state, payload) {
            state.rules = payload.rules;
            localStorageService.setToken(payload)
        },
        DESTROY_SESSION(state) {
            state.rules = [];
            localStorageService.clearToken()
            window.location.reload();
        },
        SET_AUTH_USER(state, payload) {
            state.auth_user = payload.auth;
            state.rules = payload.rules;

            localStorage.setItem("auth_user_id", payload.auth.id);
        },
        SET_USER(state, payload) {
            state.user = payload;
        },
        SET_NOTIFICATIONS(state, payload) {
            state.notifications = payload;
        },
        PUSH_NOTIFICATION(state, payload) {
            state.notifications.unshift(payload);
            state.auth_user.unread_notifications_count += 1;
        },
        SET_UNREAD_NOTIFICATION_COUNT_TO_ZERO(state) {
            state.auth_user.unread_notifications_count = 0;
        },
        SET_POSTS: (state, payload) => {
            state.posts = payload
        },
        PUSH_POSTS: (state, payload) => {
            Object.keys(payload).map(function (objectKey, index) {
                var value = payload[objectKey];
                state.posts.push(value);
            });
        },
    },
    actions: {
        LOGIN: async ({commit}, credentials) => {
            const {data} = await http.post('/user/login', credentials);
            commit('CREATE_SESSION', data);
            return data;
        },
        LOGOUT: async ({commit}) => {
            await http.post('/user/logout');
            commit('DESTROY_SESSION');
        },
        SEND_RESET_LINK_EMAIL: async ({commit}, details) => {
            const {data} = await http.post('/user/password/reset', details);
            return data;
        },
        REGISTER: async ({commit}, details) => {
            const {data} = await http.post('/user/register', details);
            commit('CREATE_SESSION', data);
            return data;
        },
        GET_AUTH_USER: async ({commit}) => {
            const {data} = await http.get('/user/auth').catch(e => {
                if (e.response.status === 401) {
                    // commit('DESTROY_SESSION');
                }
            });
            commit('SET_AUTH_USER', data);
            return data;
        },
        SAVE_AUTH_USER_DATA: async ({commit}, details) => {
            const {data} = await http.post('/user/auth', details, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            commit('SET_AUTH_USER', {auth: data.data});
            return data;
        },
        CHANGE_PASSWORD: async ({commit}, details) => {
            const {data} = await http.patch('/user/auth/password', details);
            return data;
        },
        CHANGE_PRIVACY: async ({commit}, privacy) => {
            const {data} = await http.patch('/user/auth/privacy', {privacy: privacy});
            return data;
        },
        CONFIRM_FOLLOW_REQUEST: async ({commit}, request_id) => {
            const {data} = await http.patch(`/user/auth/follow-requests/${request_id}/confirm`);
            return data;
        },
        DELETE_FOLLOW_REQUEST: async ({commit}, request_id) => {
            const {data} = await http.patch(`/user/auth/follow-requests/${request_id}/delete`);
            return data;
        },
        GET_AUTH_USER_BOOKMARKED_POSTS: async ({commit}, page) => {
            const {data} = await http.get(`/user/auth/bookmarked-posts/?page=${page}`);
            return data;
        },
        GET_USER_BY_USERNAME: async ({commit, state}, username) => {
            const {data} = await http.get('/user/' + username);
            commit('SET_USER', data.data);
            return data.data;
        },
        FOLLOW_USER: async ({commit}, user) => {
            const {data} = await http.patch(`/user/${user.username}/follow`);
            user.isRequested = data.data.isRequested;
            user.isFollowed = data.data.isFollowed;
        },
        GET_NOTIFICATIONS: async ({commit}) => {
            const {data} = await http.get('/user/auth/notifications');
            commit('SET_NOTIFICATIONS', data.data);
        },
        MARK_NOTIFICATIONS_AS_READ: async ({commit}) => {
            const {data} = await http.patch('/user/auth/notifications');
            commit('SET_NOTIFICATIONS', data.data);
            commit('SET_UNREAD_NOTIFICATION_COUNT_TO_ZERO');
        },
        PUSH_NOTIFICATION({commit}, notification) {
            commit('PUSH_NOTIFICATION', notification);
        },
        GET_POSTS_BY_USERNAME: async ({commit}, details) => {
            const {data} = await http.get(`/user/${details.username}/posts/?page=${details.page}`);
            if (details.page === 1) {
                commit('SET_POSTS', data.data)
            } else {
                commit('PUSH_POSTS', data.data)
            }
            return data;
        },
    }
}