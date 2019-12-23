import http from '@/services/http'

export default {
    namespaced: true,

    state: {
        conversations: {},
        conversation: {}
    },

    getters: {
        CONVERSATIONS(state) {
            return state.conversations;
        },
        CONVERSATION(state) {
            return state.conversation;
        }
    },

    mutations: {
        PUSH_MESSAGE(state, payload) {
            state.conversation.messages.push(payload);
        },
        DELETE_CONVERSATION(state, payload) {
            state.conversation.messages.push(payload);
        },
        SET_CONVERSATIONS(state, payload) {
            state.conversations = payload;
        },
        SET_CONVERSATION(state, payload) {
            state.conversation = payload;
        }
    },
    actions: {
        START_CONVERSATION: async ({commit}, username) => {
            const {data} = await http.post(`/messenger/${username}/conversations/start`);
            return data;
        },
        GET_CONVERSATIONS: async ({commit}) => {
            const {data} = await http.get('/messenger/conversations');
            commit("SET_CONVERSATIONS", data.data);
        },
        SEND_MESSAGE: async ({commit}, details) => {
            const {data} = await http.post('/messenger/conversations/send/message', details);
        },
        PUSH_MESSAGE: ({commit}, message) => {
            commit('PUSH_MESSAGE', message);
        },
        GET_CONVERSATION: async ({commit}, conversationId) => {
            const {data} = await http.get(`/messenger/conversations/${conversationId}`);
            commit('SET_CONVERSATION', data.conversation);
            return data;
        },
        GET_MESSAGES: async ({commit}, details) => {
            const {data} = await http.get(`/messenger/conversations/${details.conversation_id}/messages?page=${details.page}`);
            return data;
        },
        DELETE_CONVERSATION: async ({commit}, conversation_id) => {
            const {data} = await http.delete(`/messenger/conversations/${conversation_id}`);
            commit('DELETE_CONVERSATION', conversation_id);
            return data;
        }
    }
}