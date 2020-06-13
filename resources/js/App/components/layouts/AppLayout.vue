<template>
    <div class="main" id="layout-app">
        <div class="header">
            <div class="container">
                <b-navbar>
                    <template slot="brand">
                        <b-navbar-item tag="router-link" :to="{ path: '/' }">
                            <img
                                    src="images/logo.png"
                                    alt="The Modern Image Sharing & Photo Social Network Platform"
                            />
                        </b-navbar-item>
                        <b-dropdown position="is-bottom-left" aria-role="menu" trap-focus class="is-hidden-tablet">
                            <b-navbar-item slot="trigger">
                                <b-icon icon="magnify"></b-icon>
                            </b-navbar-item>
                            <b-dropdown-item tag="div" :active="false" :focusable="false"
                                             custom
                                             paddingless>
                                <b-field>
                                    <b-autocomplete
                                            rounded
                                            icon="magnify"
                                            :data="searchData"
                                            field="title"
                                            :loading="isFetching"
                                            @typing="getAsyncSearchData"
                                            @select="option => selected = option">
                                        <template slot-scope="props">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img width="32" :src="props.option.avatar"
                                                         v-if="props.option.type === 'member'">
                                                    <div class="title is-4" v-else>
                                                        #
                                                    </div>
                                                </div>
                                                <div class="media-content" v-if="props.option.type === 'member'">
                                                    <strong>{{ props.option.username }}</strong>
                                                    <br>
                                                    <small>
                                                        {{ props.option.name }}
                                                    </small>
                                                </div>
                                                <div class="media-content" v-else>
                                                    <strong>{{ props.option.slug.en }}</strong>
                                                </div>
                                            </div>
                                        </template>
                                    </b-autocomplete>
                                </b-field>
                            </b-dropdown-item>
                        </b-dropdown>
                        <b-navbar-item tag="router-link" :to="{name: 'messengerHome'}" class="is-hidden-tablet">
                            <b-icon size="is-small" icon="chat-outline"></b-icon>
                        </b-navbar-item>
                        <b-dropdown position="is-bottom-left" aria-role="menu" class="is-hidden-tablet" trap-focus>
                            <b-navbar-item slot="trigger" @click="GET_NOTIFICATIONS">
                                <b-icon size="is-small" icon="bell-outline"
                                        class="has-badge-rounded"
                                        :data-badge="AUTH_USER.unread_notifications_count">
                                </b-icon>
                            </b-navbar-item>
                            <b-dropdown-item
                                    class="notifications-container"
                                    aria-role="menu-item"
                                    :focusable="false"
                                    custom
                                    paddingless>
                                <div class="modal-card" style="width:auto">
                                    <div class="header">
                                        <strong class="ml-5">Notifications</strong>
                                        <a href="#" class="is-pulled-right mr-5"
                                           @click.prevent="markNotificationsAsRead">
                                            Mark All as Read
                                        </a>
                                    </div>
                                    <section class="has-custom-scrollbar">
                                        <div class="follow-requests-container"
                                             v-if="AUTH_USER.follow_requests && AUTH_USER.follow_requests.length">
                                            <div class="ml-5 follow-requests-link">
                                                <a href="#" @click.prevent="showFollowRequests">
                                                    <div class="count has-background-danger has-text-white">{{
                                                        AUTH_USER.follow_requests.length }}
                                                    </div>
                                                    <b-icon icon="arrow-right-drop-circle"
                                                            class="is-pulled-right m-10"></b-icon>
                                                    <strong class="has-text-black">Follow Requests</strong>
                                                    <div class="has-text-black">Approve or ignore requests</div>
                                                </a>
                                            </div>
                                            <div class="follow-requests" v-if="isShowedFollowRequests">
                                                <div class="follow-request mt-5"
                                                     v-for="follow_request in AUTH_USER.follow_requests">
                                                    <div class="is-pulled-left">
                                                        <router-link
                                                                :to="{name: 'user', params: {username: follow_request.follower.username}}">
                                                            <figure class="image is-32x32 m-5">
                                                                <img class="is-rounded"
                                                                     :src="follow_request.follower.avatar">
                                                            </figure>
                                                        </router-link>
                                                    </div>
                                                    <div class="is-pulled-right mt-5 mr-5">
                                                        <b-button type="is-primary" size="is-small"
                                                                  @click="confirmFollowRequest(follow_request.id)">
                                                            Confirm
                                                        </b-button>
                                                        <b-button size="is-small"
                                                                  @click="deleteFollowRequest(follow_request.id)">Delete
                                                        </b-button>
                                                    </div>
                                                    <router-link
                                                            :to="{name: 'user', params:{username: follow_request.follower.username}}">
                                                        {{ follow_request.follower.username }}<br>
                                                        <span class="has-text-black">{{ follow_request.follower.name }}</span>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>
                                        <b-notification v-if="!isShowedFollowRequests" :closable="false"
                                                        :type="notification.read_at ? 'is-white' : ''"
                                                        v-for="( notification, key) in NOTIFICATIONS"
                                                        :key="key">
                                            <template v-if="notification.type==='App\\Notifications\\PostLiked'">
                                                <div class="is-pulled-right">
                                                    <router-link
                                                            :to="{name: 'post', params: {id: notification.data.post.id}}">
                                                        <figure class="image is-32x32">
                                                            <img :src="notification.data.post.cover" class="post-image">
                                                        </figure>
                                                    </router-link>
                                                </div>
                                            </template>
                                            <router-link
                                                    :to="{name: 'user', params:{username: notification.data.username}}">
                                                {{ notification.data.username }}
                                            </router-link>
                                            {{ notification.data.message }}
                                            <div>
                                                {{ notification.created_at }}
                                            </div>
                                        </b-notification>
                                    </section>
                                </div>
                            </b-dropdown-item>
                        </b-dropdown>
                    </template>
                    <template slot="start">
                        <b-navbar-item tag="router-link" to="/">
                            <b-icon icon="home" size="is-small"></b-icon>
                            <span>
                                Home
                            </span>
                        </b-navbar-item>
                        <b-navbar-item tag="router-link" to="/explore">
                            <b-icon icon="earth" size="is-small"></b-icon>
                            <span>Explore</span>
                        </b-navbar-item>
                        <b-navbar-item tag="div" :active="false" class="is-hidden-mobile">
                            <b-field>
                                <b-autocomplete
                                        rounded
                                        icon="magnify"
                                        :data="searchData"
                                        field="title"
                                        :loading="isFetching"
                                        @typing="getAsyncSearchData"
                                        @select="option => selected = option">
                                    <template slot-scope="props">
                                        <div class="media">
                                            <div class="media-left">
                                                <img width="32" :src="props.option.avatar"
                                                     v-if="props.option.type === 'member'">
                                                <div class="title is-4" v-else>
                                                    #
                                                </div>
                                            </div>
                                            <div class="media-content" v-if="props.option.type === 'member'">
                                                <strong>{{ props.option.username }}</strong>
                                                <br>
                                                <small>
                                                    {{ props.option.name }}
                                                </small>
                                            </div>
                                            <div class="media-content" v-else>
                                                <strong>{{ props.option.slug.en }}</strong>
                                            </div>
                                        </div>
                                    </template>
                                </b-autocomplete>
                            </b-field>
                        </b-navbar-item>
                    </template>

                    <template slot="end">
                        <b-navbar-item tag="router-link" :to="{name: 'messengerHome'}" class="is-hidden-mobile">
                            <b-icon size="is-small" icon="chat-outline"></b-icon>
                        </b-navbar-item>
                        <b-dropdown position="is-bottom-left" aria-role="menu" class="is-hidden-mobile" trap-focus>
                            <b-navbar-item slot="trigger" @click="GET_NOTIFICATIONS">
                                <b-icon size="is-small" icon="bell-outline"
                                        class="has-badge-rounded"
                                        :data-badge="AUTH_USER.unread_notifications_count">
                                </b-icon>
                            </b-navbar-item>
                            <b-dropdown-item
                                    class="notifications-container"
                                    aria-role="menu-item"
                                    :focusable="false"
                                    custom
                                    paddingless>
                                <div class="modal-card" style="width:350px;">
                                    <div class="header">
                                        <strong class="ml-5">Notifications</strong>
                                        <a href="#" class="is-pulled-right mr-5"
                                           @click.prevent="markNotificationsAsRead">
                                            Mark All as Read
                                        </a>
                                    </div>
                                    <section class="has-custom-scrollbar">
                                        <div class="follow-requests-container"
                                             v-if="AUTH_USER.follow_requests && AUTH_USER.follow_requests.length">
                                            <div class="ml-5 follow-requests-link">
                                                <a href="#" @click.prevent="showFollowRequests">
                                                    <div class="count has-background-danger has-text-white">{{
                                                        AUTH_USER.follow_requests.length }}
                                                    </div>
                                                    <b-icon icon="arrow-right-drop-circle"
                                                            class="is-pulled-right m-10"></b-icon>
                                                    <strong class="has-text-black">Follow Requests</strong>
                                                    <div class="has-text-black">Approve or ignore requests</div>
                                                </a>
                                            </div>
                                            <div class="follow-requests" v-if="isShowedFollowRequests">
                                                <div class="follow-request mt-5"
                                                     v-for="follow_request in AUTH_USER.follow_requests">
                                                    <div class="is-pulled-left">
                                                        <router-link
                                                                :to="{name: 'user', params: {username: follow_request.follower.username}}">
                                                            <figure class="image is-32x32 m-5">
                                                                <img class="is-rounded"
                                                                     :src="follow_request.follower.avatar">
                                                            </figure>
                                                        </router-link>
                                                    </div>
                                                    <div class="is-pulled-right mt-5 mr-5">
                                                        <b-button type="is-primary" size="is-small"
                                                                  @click="confirmFollowRequest(follow_request.id)">
                                                            Confirm
                                                        </b-button>
                                                        <b-button size="is-small"
                                                                  @click="deleteFollowRequest(follow_request.id)">Delete
                                                        </b-button>
                                                    </div>
                                                    <router-link
                                                            :to="{name: 'user', params:{username: follow_request.follower.username}}">
                                                        {{ follow_request.follower.username }}<br>
                                                        <span class="has-text-black">{{ follow_request.follower.name }}</span>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>
                                        <b-notification v-if="!isShowedFollowRequests" :closable="false"
                                                        :type="notification.read_at ? 'is-white' : ''"
                                                        v-for="(notification, key) in NOTIFICATIONS"
                                                        :key="key">
                                            <template v-if="notification.type==='App\\Notifications\\PostLiked'">
                                                <div class="is-pulled-right">
                                                    <router-link
                                                            :to="{name: 'post', params: {id: notification.data.post.id}}">
                                                        <figure class="image is-32x32">
                                                            <img :src="notification.data.post.cover" class="post-image">
                                                        </figure>
                                                    </router-link>
                                                </div>
                                            </template>
                                            <router-link
                                                    :to="{name: 'user', params:{username: notification.data.username}}">
                                                {{ notification.data.username }}
                                            </router-link>
                                            {{ notification.data.message }}
                                            <div>
                                                {{ notification.created_at }}
                                            </div>
                                        </b-notification>
                                    </section>
                                </div>
                            </b-dropdown-item>
                        </b-dropdown>

                        <b-navbar-dropdown>
                            <template v-slot:label>
                                <figure class="image is-24x24">
                                    <img class="avatar is-rounded" :src="AUTH_USER.avatar">
                                </figure>
                                <span class="is-block ml-5">{{ AUTH_USER.username }}</span>
                            </template>
                            <b-navbar-item tag="router-link"
                                           :to="{name: 'user', params: {username: AUTH_USER.username}}">
                                Profile
                            </b-navbar-item>
                            <b-navbar-item tag="router-link" :to="{name: 'settings'}">
                                Settings
                            </b-navbar-item>
                            <b-navbar-item href="#" @click.prevent="LOGOUT">
                                <span>Logout</span>
                            </b-navbar-item>
                        </b-navbar-dropdown>
                    </template>
                </b-navbar>
            </div>
        </div>
        <div class="mt-30">
            <slot></slot>
        </div>
    </div>
</template>

<script>
  import debounce from 'lodash/debounce'

  import { mapActions, mapGetters } from 'vuex'

  import BNavbarDropdown from 'buefy/src/components/navbar/NavbarDropdown'
  import BButton from 'buefy/src/components/button/Button'
  import BDropdownItem from 'buefy/src/components/dropdown/DropdownItem'
  import BNavbarItem from 'buefy/src/components/navbar/NavbarItem'

  export default {
    name: 'AppLayout',
    components: { BNavbarItem, BDropdownItem, BButton, BNavbarDropdown },
    data () {
      return {
        isShowedFollowRequests: false,
        searchData: [],
        selected: null,
        isFetching: false,
      }
    },
    watch: {
      selected: function (val) {
        if (val.type === 'member') {
          this.$router.push({ name: 'user', params: { username: val.username } })
        } else if (val.type === 'hashtags') {
          this.$router.push({ name: 'hashtag', params: { tag: val.slug.en } })
        }
      },
    },
    created () {
      this.GET_AUTH_USER().then(r => {
        window.Echo.private('App.Models.Member.' + this.AUTH_USER.id).notification((notification) => {
          console.log(notification)
          console.log(notification.type)
          this.PUSH_NOTIFICATION({
            type: notification.type,
            data: {
              username: notification.username,
              message: notification.message,
              post: notification.post,
            },
            created_at: notification.created_at,
          })
        })
      })
    },
    computed: {
      ...mapGetters('user', ['AUTH_USER', 'NOTIFICATIONS']),
    },
    methods: {
      ...mapActions(['GET_SEARCH_DATA']),
      ...mapActions('user', [
        'GET_AUTH_USER',
        'GET_NOTIFICATIONS',
        'MARK_NOTIFICATIONS_AS_READ',
        'PUSH_NOTIFICATION',
        'CONFIRM_FOLLOW_REQUEST',
        'DELETE_FOLLOW_REQUEST',
        'LOGOUT'
      ]),
      markNotificationsAsRead () {
        this.MARK_NOTIFICATIONS_AS_READ()
      },
      showFollowRequests () {
        this.isShowedFollowRequests = !this.isShowedFollowRequests
      },
      confirmFollowRequest (request_id) {
        this.CONFIRM_FOLLOW_REQUEST(request_id)
        this.removeFollowRequestFromList(request_id)
      },
      deleteFollowRequest (request_id) {
        this.DELETE_FOLLOW_REQUEST(request_id)
        this.removeFollowRequestFromList(request_id)
      },
      removeFollowRequestFromList (request_id) {
        let index = this.AUTH_USER.follow_requests.map(x => {
          return x.id
        }).indexOf(request_id)
        this.AUTH_USER.follow_requests.splice(index, 1)
        if (!this.AUTH_USER.follow_requests.length) {
          this.isShowedFollowRequests = false
        }
      },
      getAsyncSearchData: debounce(function (query) {
        if (!query.length) {
          this.searchData = []
          return
        }
        this.isFetching = true
        this.GET_SEARCH_DATA(query).then(({ data }) => {
          this.searchData = []
          data.forEach((item) => this.searchData.push(item))
        }).catch((error) => {
          this.searchData = []
          throw error
        }).finally(() => {
          this.isFetching = false
        })
      }, 500),
    },
  }
</script>

<style scoped lang="scss">
    .notifications-container {
        .modal-card {
            max-height: 300px;

            .header {
                border-bottom: 1px solid;
            }

            section {
                overflow-y: auto;

                .notification {
                    margin: 0;
                    border-radius: 0;
                    border-bottom: 1px solid #e4e4e4;
                }

                .follow-requests-container {
                    .follow-requests-link {
                        padding: 3px 0;
                        border-bottom: 1px solid;

                        .count {
                            width: 43px;
                            line-height: 43px;
                            text-align: center;
                            border-radius: 100%;
                            font-weight: bold;
                            float: left;
                            margin-right: 5px;
                        }
                    }
                }
            }
        }
    }
</style>