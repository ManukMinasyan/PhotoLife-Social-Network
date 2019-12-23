<template>
    <AppLayout>
        <div class="container is-fullhd">
            <div class="columns is-mobile is-gapless wrapper">
                <div class="column is-4 is-1-mobile left p-0">
                    <div class="top is-hidden">
                        <input type="text" placeholder="Search"/>
                        <a href="javascript:;" class="search"></a>
                    </div>
                    <ul class="people">
                        <router-link tag="li" class="person" data-chat="person1"
                                     v-for="conversation in CONVERSATIONS"
                                     :to="{name: 'conversation', params: {conversationId: conversation.id}}">
                            <figure class="image">
                                <img :src="conversation.user.avatar" class="avatar rounded" :alt="conversation.user.username"/>
                            </figure>
                            <span class="name is-hidden-mobile">{{ conversation.user.username }}</span>
                            <span class="time is-hidden-mobile">{{ conversation.last_message ?  conversation.last_message.created_at : '' }}</span>
                            <span class="preview is-hidden-mobile">
                                <text-helper
                                        :text="conversation.last_message ?  conversation.last_message.text : ''">
                                </text-helper>
                            </span>
                        </router-link>
                    </ul>
                </div>
                <div class="column is-8 is-10-mobile right p-0">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
    import AppLayout from "../../layouts/AppLayout";
    import {mapActions, mapGetters} from 'vuex';
    import ChatRoomComponent from './ChatRoomComponent';
    import TextHelper from "../../../helpers/TextHelper";

    export default {
        name: "MessengerComponent.vue",
        components: {TextHelper, AppLayout, ChatRoomComponent},
        data() {
            return {
                showConversation: false,
                currentConversation: {}
            }
        },
        created() {
            this.GET_CONVERSATIONS();
        },
        computed: {
            ...mapGetters('messenger', ['CONVERSATIONS']),
            ...mapGetters('user', ['AUTH_USER'])
        },
        methods: {
            ...mapActions('messenger', ['GET_CONVERSATIONS']),
            openConversation(conversation) {
                this.showConversation = true;
                this.currentConversation = conversation;
            }
        }
    }
</script>

<style scoped lang="scss">


    $white: #fff;
    $black: #000;
    $bg: #f8f8f8;
    $grey: #999;
    $dark: #1a1a1a;
    $light: #e6e6e6;
    $wrapper: 1000px;
    $blue: #00b0ff;

    .wrapper {
        height: 800px;
    }

    .container {
        position: relative;
        width: 100%;
        height: 80%;
        .left {
            height: 100%;
            border: 1px solid $light;
            background-color: $white;
            .top {
                position: relative;
                width: 100%;
                height: 96px;
                padding: 29px;
                &:after {
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    display: block;
                    width: 80%;
                    height: 1px;
                    content: '';
                    background-color: $light;
                    transform: translate(-50%, 0);
                }
            }
            input {
                float: left;
                width: 188px;
                height: 42px;
                padding: 0 15px;
                border: 1px solid $light;
                background-color: #eceff1;
                border-radius: 21px;
                &:focus {
                    outline: none;
                }
            }
            a.search {
                display: block;
                float: left;
                width: 42px;
                height: 42px;
                margin-left: 10px;
                border: 1px solid $light;
                background-color: $blue;
                background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/name-type.png');
                background-repeat: no-repeat;
                background-position: top 12px left 14px;
                border-radius: 50%;
            }
            .people {
                margin-left: -1px;
                border-right: 1px solid $light;
                border-left: 1px solid $light;
                width: calc(100% + 2px);
                height: calc(100% - 96px);
                overflow-y: auto;
                overflow-x: hidden;
                .person {
                    position: relative;
                    width: 100%;
                    height: 70px;
                    padding: 12px 10% 16px;
                    cursor: pointer;
                    background-color: $white;
                    margin-top: 1px;
                    &:after {
                        position: absolute;
                        bottom: 0;
                        left: 50%;
                        display: block;
                        width: 80%;
                        height: 1px;
                        content: '';
                        background-color: $light;
                        transform: translate(-50%, 0);
                    }
                    img {
                        float: left;
                        width: 40px;
                        height: 40px;
                        margin-right: 12px;
                        border-radius: 50%;
                    }
                    .name {
                        font-size: 14px;
                        line-height: 22px;
                        color: $dark;
                    }
                    .time {
                        font-size: 14px;
                        position: absolute;
                        top: 16px;
                        right: 10%;
                        padding: 0 0 5px 5px;
                        color: $grey;
                        background-color: white;
                    }
                    .preview {
                        font-size: 14px;
                        display: inline-block;
                        overflow: hidden !important;
                        width: 70%;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        color: $grey;
                    }
                    &.router-link-active, &:hover {
                        background-color: $blue;
                        width: calc(100% + 2px);
                        padding-left: calc(10% + 1px);
                        span {
                            color: $white;
                            background: transparent;
                        }
                        &:after {
                            display: none;
                        }
                    }
                }
            }
        }
    }

    .right {
        position: relative;
        height: 100%;
        background-color: $white;
    }

    @keyframes slideFromLeft {
        0% {
            margin-left: -200px;
            opacity: 0;
        }
        100% {
            margin-left: 0;
            opacity: 1;
        }
    }

    @-webkit-keyframes slideFromLeft {
        0% {
            margin-left: -200px;
            opacity: 0;
        }
        100% {
            margin-left: 0;
            opacity: 1;
        }
    }

    @keyframes slideFromRight {
        0% {
            margin-right: -200px;
            opacity: 0;
        }
        100% {
            margin-right: 0;
            opacity: 1;
        }
    }

    @-webkit-keyframes slideFromRight {
        0% {
            margin-right: -200px;
            opacity: 0;
        }
        100% {
            margin-right: 0;
            opacity: 1;
        }
    }
</style>