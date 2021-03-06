<template>
    <AppLayout>
        <div class="container is-fullhd">
            <div class="columns is-gapless is-multiline wrapper">
                <div class="header-panel is-hidden-tablet">
                    All messages
                </div>
                <div class="column is-12-mobile is-4-tablet left p-0">
                    <div class="top is-hidden">
                        <input type="text" placeholder="Search"/>
                        <a href="javascript:;" class="search"></a>
                    </div>
                    <ul class="people">
                        <router-link tag="li" class="person is-flex" data-chat="person1"
                                     v-for="conversation in CONVERSATIONS"
                                     :key="conversation.user.id"
                                     :to="{name: 'conversation', params: {conversationId: conversation.id}}">
                            <figure class="image">
                                <img :src="conversation.user.avatar" class="avatar rounded"
                                     :alt="conversation.user.username"/>
                            </figure>
                            <div class="info">
                                <span class="name">{{ conversation.user.username }}</span>
                                <span class="time is-hidden-mobile">{{ conversation.last_message ?  conversation.last_message.created_at : '' }}</span>
                                <text-helper
                                        :text="conversation.last_message ?  conversation.last_message.text : ''"
                                        :size="20"
                                        tag="div" class="preview">
                                </text-helper>
                            </div>
                        </router-link>
                    </ul>
                </div>
                <div class="column right p-0">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
  import AppLayout from '../../layouts/AppLayout'
  import { mapActions, mapGetters } from 'vuex'
  import ChatRoomComponent from './ChatRoomComponent'
  import TextHelper from '../../../helpers/TextHelper'

  export default {
    name: 'MessengerComponent.vue',
    components: { TextHelper, AppLayout, ChatRoomComponent },
    data () {
      return {
        showConversation: false,
        currentConversation: {},
      }
    },
    created () {
      this.GET_CONVERSATIONS()
    },
    computed: {
      ...mapGetters('messenger', ['CONVERSATIONS']),
      ...mapGetters('user', ['AUTH_USER']),
    },
    methods: {
      ...mapActions('messenger', ['GET_CONVERSATIONS']),
      openConversation (conversation) {
        this.showConversation = true
        this.currentConversation = conversation
      },
    },
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
        height: calc(100vh - 150px);
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
                        overflow: hidden !important;
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
        height: auto;
        background-color: $white;
    }

    @media only screen and (max-width: 768px) {
        .container {
            .left {
                height: initial;

                .people {
                    display: flex;
                    height: auto;

                    .person {
                        width: 70px;
                        padding: 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-direction: column;

                        &.router-link-active {
                            width: 70px;
                            padding-left: initial;
                        }

                        img {
                            width: 20px;
                            height: 20px;
                            margin-right: 0;
                        }

                        .info {
                            text-align: center;
                        }
                    }
                }
            }
        }

        .wrapper {
            .header-panel {
                height: 40px;
                margin-left: 10px;
            }

            .column {
                &.right {
                    height: calc(100% - 60px);
                    margin-top: 10px;
                }
            }
        }
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