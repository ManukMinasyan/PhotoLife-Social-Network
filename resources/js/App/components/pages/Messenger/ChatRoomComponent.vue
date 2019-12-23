<template>
    <div class="chat-room">
        <div class="top">
            <span>To: <span class="name">{{ conversation.user.name }}</span></span>
            <div class="is-pulled-right">
                <b-dropdown aria-role="list">
                    <a slot="trigger">
                        <b-icon icon="dots-vertical"></b-icon>
                    </a>
                    <b-dropdown-item aria-role="listitem" @click="deleteConversation">
                        <b-icon size="is-small" icon="trash-can"></b-icon>
                        Delete conversation
                    </b-dropdown-item>
                </b-dropdown>
            </div>
        </div>
        <div class="chat" data-chat="person1">
            <template v-for="group in group_messages">
                <div class="conversation-start">
                    <span>{{ group.group_date }}</span>
                </div>

                <div class="bubble" v-for="message in group.messages"
                     :class="{ 'me' : checkAuthUser(message.sender.id), 'you' : !checkAuthUser(message.sender.id) }">
                    <b-tooltip :label="message.created_at_fully"
                               :position="{ 'is-right' : checkAuthUser(message.sender.id), 'is-left' : !checkAuthUser(message.sender.id) }">
                        <text-helper :text="message.text"></text-helper>
                        <div class="row">
                            <file-preview :file="file" v-for="file in message.files"
                                          :key="file.id"></file-preview>
                        </div>
                    </b-tooltip>
                </div>


            </template>
            <div class="bubble you" v-if="activeUser">
                <div class="ticontainer">
                    <div class="tiblock">
                        <div class="tidot"></div>
                        <div class="tidot"></div>
                        <div class="tidot"></div>
                    </div>
                </div>
            </div>
            <infinite-loading direction="top" @infinite="loadMoreMessages">
                <div slot="no-more" class="has-text-white">
                    You've scrolled through all the posts :)
                </div>
                <div slot="no-results" class="has-text-white">
                    Sorry, no posts yet :(
                </div>
            </infinite-loading>
        </div>
        <div class="write-panel">
            <div class="write">
                <div class="pull-left">
                    <a href="javascript:;" class="write-link attach"></a>
                </div>
                <textarea maxlength="200"
                          id="textarea"
                          placeholder="Type your message here...">
                </textarea>
                <div class="pull-right">
                    <a @click.prevent="sendMessage" class="write-link send"></a>
                </div>
            </div>
        </div>
        <div class="row">
            <file-preview :file="file" v-for="file in conversation.files" :key="file.id"></file-preview>
        </div>
    </div>
</template>
<script>
    import 'emojionearea/dist/emojionearea.js';
    import "#/emojionearea.scss"

    import {mapActions, mapGetters} from 'vuex';

    import BModal from "buefy/src/components/modal/Modal";
    import BIcon from "buefy/src/components/icon/Icon";
    import TextHelper from "../../../helpers/TextHelper";

    export default {
        name: 'ChatRoomComponent',
        components: {TextHelper, BIcon, BModal},
        data() {
            return {
                conversation_id: this.$route.params.conversationId,
                conversation: {
                    user: {},
                },
                group_messages: [],
                currentPage: 1,
                activeUser: false,
                typingTimer: false,
                channel: '',
                text: '',
                constraints: {
                    audio: false,
                    video: true
                },
                incomingVideoCallModal: false
            }
        },
        created() {
            this.getConversation();
        },
        mounted() {
            let self = this;
            $(self.$el).find('textarea').emojioneArea({
                search: false,
                inline: true,
                events: {
                    keyup: function (editor, event) {
                        if (event.which === 13) {
                            self.sendMessage();
                        }
                    },
                    keydown: function (editor, event) {
                        self.sendTypingEvent();
                    }
                }
            });
            window.Echo.join('chat-room.' + this.conversation_id)
                .here((users) => {
                    // console.log('Users: ', users)
                })
                .joining((user) => {
                    // this.users.push(user);
                    // console.log('Joining user: ', user);
                })
                .leaving(user => {
                    // this.users = this.users.filter(u => u.id != user.id);
                    // console.log('Leaving user: ', user);
                })
                .listen('NewConversationMessage', (data) => {
                    if ((self.group_messages.length - 1) < 0) {
                        self.group_messages.push({
                            group_date: data.created_at_fully,
                            messages: []
                        });
                    }

                    self.group_messages[self.group_messages.length - 1].messages.push(data);
                    self.text = '';
                })
                .listenForWhisper('typing', user => {
                    self.activeUser = user;
                    if (self.typingTimer) {
                        clearTimeout(self.typingTimer);
                    }
                    self.typingTimer = setTimeout(() => {
                        self.activeUser = false;
                    }, 3000);
                });
        },
        computed: {
            ...mapGetters('user', ['AUTH_USER'])
        },
        methods: {
            ...mapActions('messenger', ['GET_CONVERSATION', 'GET_MESSAGES', 'SEND_MESSAGE', 'DELETE_CONVERSATION', 'PUSH_MESSAGE']),
            getConversation() {
                this.GET_CONVERSATION(this.conversation_id)
                    .then(({data}) => {
                        this.conversation = data;
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 404) {
                            next({name: '404', params: {resource: 'user'}})
                        } else {
                            next({name: 'network-issue'})
                        }
                    });
            },
            loadMoreMessages($state) {
                this.GET_MESSAGES({conversation_id: this.conversation_id, page: this.currentPage}).then(({data}) => {
                    if (Object.keys(data).length) {
                        this.currentPage += 1;

                        /**
                         * Dynamically Grouping by date
                         */
                        for (let i = 0; i < data.length; i++) {
                            let group = data[i];
                            let similarGroup = this.group_messages.find(g => g.group_date === group.group_date);

                            group.messages = group.messages.reverse();
                            if (typeof similarGroup !== "undefined") {
                                similarGroup.messages.unshift(...group.messages);
                            } else {
                                this.group_messages.unshift(group)
                            }
                        }

                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
            },
            loadMorePosts($state) {
                this.GET_POSTS(this.currentPage).then(({data}) => {
                    if (data.length) {
                        this.currentPage += 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
            },
            checkAuthUser(id) {
                return id === this.AUTH_USER.id;
            },
            sendMessage() {
                var text = $('#textarea').data('emojioneArea');

                this.SEND_MESSAGE({
                    conversationId: this.conversation_id,
                    text: text.getText(),
                });

                text.setText('');
            },
            deleteConversation() {
                this.DELETE_CONVERSATION(this.conversation_id)
            },
            sendTypingEvent() {
                window.Echo.join('chat-room.' + this.conversation_id)
                    .whisper('typing', this.conversation.user);
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

    .chat-room {
        position: relative;
        float: left;
        width: 100%;
        height: 100%;
        .top {
            width: 100%;
            height: 47px;
            padding: 15px 29px;
            background-color: #eceff1;
            span {
                font-size: 15px;
                color: $grey;
                .name {
                    color: $dark;
                }
            }
        }
        .chat {
            position: relative;
            padding: 0 35px 92px;
            border-width: 1px 1px 1px 0;
            border-style: solid;
            border-color: $light;
            height: calc(100% - 48px);
            display: flex;
            overflow-y: auto;
            flex-direction: column;
            flex-flow: column nowrap;
            .bubble {
                transition-timing-function: cubic-bezier(.4, -.04, 1, 1);
                @for $i from 1 through 10 {
                    &:nth-of-type(#{$i}) {
                        animation-duration: .15s * $i;
                    }
                }
            }

        }
        .write-panel {
            position: absolute;
            width: 100%;
            height: 72px;
            bottom: 0;
            /* border: 1px solid red; */
            background: #FFF;
            z-index: 100;

            .write {
                position: absolute;
                bottom: 29px;
                left: 30px;
                padding: 5px;
                padding-left: 8px;
                border: 1px solid $light;
                background-color: #eceff1;
                width: calc(100% - 58px);
                border-radius: 5px;
                .emojionearea.emojionearea-inline > .emojionearea-button {
                    right: 20px;
                }
                .pull-left, .pull-right {
                    position: absolute;
                }
                .pull-right {
                    right: 10px;
                    top: 2px;
                }
                .write-link {
                    &.attach {
                        &:before {
                            display: inline-block;
                            float: left;
                            width: 20px;
                            height: 42px;
                            content: '';
                            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/attachment.png');
                            background-repeat: no-repeat;
                            background-position: center;
                        }
                    }
                    &.smiley {
                        &:before {
                            display: inline-block;
                            float: left;
                            width: 20px;
                            height: 42px;
                            content: '';
                            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/smiley.png');
                            background-repeat: no-repeat;
                            background-position: center;
                        }
                    }
                    &.send {
                        &:before {
                            display: inline-block;
                            float: left;
                            width: 20px;
                            height: 42px;
                            margin-left: 11px;
                            content: '';
                            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/382994/send.png');
                            background-repeat: no-repeat;
                            background-position: center;
                        }
                    }
                }
            }
        }
        .bubble {
            font-size: 16px;
            position: relative;
            display: inline-block;
            clear: both;
            margin-bottom: 8px;
            padding: 13px 14px;
            vertical-align: top;
            border-radius: 5px;
            max-width: 70%;
            word-break: break-all;
            &:before {
                position: absolute;
                top: 19px;
                display: block;
                width: 8px;
                height: 6px;
                content: '\00a0';
                transform: rotate(29deg) skew(-35deg);
            }
            &.me {
                float: left;
                color: $white;
                background-color: $blue;
                align-self: flex-start;
                animation-name: slideFromLeft;
                &:before {
                    left: -3px;
                    background-color: $blue;
                }
            }
            &.you {
                float: right;
                color: $dark;
                background-color: #eceff1;
                align-self: flex-end;
                animation-name: slideFromRight;
                &:before {
                    right: -3px;
                    background-color: #eceff1;
                }
            }
        }
        .conversation-start {
            position: relative;
            width: 100%;
            margin-bottom: 27px;
            text-align: center;
            span {
                font-size: 14px;
                display: inline-block;
                color: $grey;
                &:before, &:after {
                    position: absolute;
                    top: 10px;
                    display: inline-block;
                    width: 30%;
                    height: 1px;
                    content: '';
                    background-color: $light;
                }
                &:before {
                    left: 0;
                }
                &:after {
                    right: 0;
                }
            }
        }

        .ticontainer {
            .tiblock {
                align-items: center;
                display: flex;
                height: 17px;
                .tidot {
                    background-color: #90949c;
                    -webkit-animation: mercuryTypingAnimation 1.5s infinite ease-in-out;
                    border-radius: 2px;
                    display: inline-block;
                    height: 4px;
                    margin-right: 2px;
                    width: 4px;
                    &:nth-child(1) {
                        -webkit-animation-delay: 200ms;
                    }
                    &:nth-child(2) {
                        -webkit-animation-delay: 300ms;
                    }
                    &:nth-child(3) {
                        -webkit-animation-delay: 400ms;
                    }
                }
            }
        }

        @-webkit-keyframes mercuryTypingAnimation {
            0% {
                -webkit-transform: translateY(0px)
            }
            28% {
                -webkit-transform: translateY(-5px)
            }
            44% {
                -webkit-transform: translateY(0px)
            }
        }
    }
</style>