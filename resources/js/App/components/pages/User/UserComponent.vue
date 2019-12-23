<template>
    <AppLayout :key="$route.path">
        <header class="mb-10">
            <div class='container profile-heading pt-30 pb-30'>
                <div class='columns is-mobile is-multiline'>
                    <div class="column is-2">
                        <figure>
                            <p class="image is-1by1">
                                <img class="is-rounded avatar" :src="USER.avatar">
                            </p>
                        </figure>
                    </div>
                    <div class='column is-10 name'>
                        <div class="columns is-multiline is-mobile">
                            <div class="column is-6-tablet is-12-mobile">
                                <h1 class='title is-bold has-text-light mb-0 is-inline-block'>{{ USER.username }}</h1>
                                <template v-if="!$isAuthUser(USER.id)">
                                    <a class="button is-inline-block ml-10"
                                       @click.prevent="FOLLOW_USER(USER)"
                                       :class="{'is-primary': !USER.isFollowed && !USER.isRequested}">
                                        <template v-if="USER.isRequested">
                                            Requested
                                        </template>
                                        <template v-else-if="USER.isFollowed">
                                            Following
                                        </template>
                                        <template v-else>
                                            Follow
                                        </template>
                                    </a>
                                    <a class="button" @click="startConversation">
                                        <b-icon icon="message" size="is-small" type="is-primary"></b-icon>
                                    </a>
                                </template>
                                <b-button tag="router-link" :to="{name: 'settings'}" v-else>
                                    Edit Profile
                                </b-button>

                                <h5 class='title is-5 has-text-light mt-10'>{{ USER.name }}</h5>
                            </div>
                            <div class='column is-2-tablet is-4-mobile has-text-centered mt-20'>
                                <p class="title is-2 is-bold has-text-light mb-5">{{ USER.posts ? USER.posts.length : 0
                                    }}</p>
                                <p class="title is-6 is-normal has-text-light">posts</p>
                            </div>
                            <div class='column is-2-tablet is-4-mobile has-text-centered mt-20'>
                                <p class='title is-2 is-bold has-text-light mb-5'>{{ USER.followers_count }}</p>
                                <p class='title is-6 is-normal has-text-light'>followers</p>
                            </div>
                            <div class='column is-2-tablet is-4-mobile has-text-centered mt-20'>
                                <p class='title is-2 is-normal has-text-light mb-5'>{{ USER.followings_count }}</p>
                                <p class='title is-6 is-normal has-text-light'>following</p>
                            </div>
                            <div class="column is-12">
                                <p class='tagline'>
                                    <text-helper :text="USER.bio"></text-helper>
                                </p>
                            </div>
                            <div class="column is-12">
                                <a :href="USER.website" target="_blank" class="button is-light is-small"
                                   v-if="USER.website">
                                    <b-icon icon="web" size="is-small" type="is-primary" class="mr-5"></b-icon>
                                    {{ USER.website.substring(0, 25) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class='container profile' :key="USER.id">
            <div class="column private-user"
                 v-if="USER.privacy === 'private' && USER.isFollowed === false && !$isAuthUser(USER.id)">
                <h2 class="title is-5">This Account is Private</h2>
                <p>Follow to see their photos and videos.</p>
            </div>
            <b-tabs position="is-centered" class="block" v-model="activeTab" v-else>
                <b-tab-item label="Posts" icon="grid">
                    <transition-group name="fade" class="columns is-multiline posts-area">
                        <post-component v-for="post in POSTS" :post="post" :key="post.id"></post-component>
                    </transition-group>
                    <infinite-loading @infinite="loadMorePosts">
                        <div slot="no-results" class="column no-posts">
                            <div class="camera">
                                <b-icon icon="camera" size="is-medium"></b-icon>
                            </div>
                            <h1 class="title">No Posts Yet</h1>
                        </div>
                    </infinite-loading>
                </b-tab-item>
                <b-tab-item :visible="$isAuthUser(USER.id)" v-if="$isAuthUser(USER.id)" label="Saved"
                            icon="bookmark-outline" :key="activeTab">
                    <transition-group name="fade" class="columns is-multiline posts-area">
                        <post-component v-for="(post, key) in bookmarked_posts" :post="post"
                                        :key="key"></post-component>
                    </transition-group>
                    <infinite-loading @infinite="loadMoreBookmarkedPosts">
                        <div slot="no-results" class="column no-posts">
                            <div class="camera">
                                <b-icon icon="camera" size="is-medium"></b-icon>
                            </div>
                            <h1 class="title">No Posts Yet</h1>
                        </div>
                    </infinite-loading>
                </b-tab-item>
            </b-tabs>
        </div>
    </AppLayout>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import AppLayout from "../../layouts/AppLayout";

    import TextHelper from "../../../helpers/TextHelper";
    import BButton from "buefy/src/components/button/Button";
    import PostComponent from "./PostComponent";
    import BIcon from "buefy/src/components/icon/Icon";

    export default {
        name: "UserComponent",
        components: {BIcon, PostComponent, BButton, TextHelper, AppLayout},
        data() {
            return {
                currentPage: 1,
                currentBookmarkedPage: 1,
                activeTab: 0,
                bookmarked_posts: []
            }
        },
        watch: {
            activeTab: function (val) {
                if (val === 2) {
                    this.currentBookmarkedPage = 1;
                }
            }
        },
        created() {
            this.GET_USER_BY_USERNAME(this.$route.params.username);
        },
        computed: {
            ...mapGetters('user', ['USER', 'POSTS'])
        },
        methods: {
            ...mapActions('user', ['FOLLOW_USER', 'GET_USER_BY_USERNAME', 'GET_POSTS_BY_USERNAME', 'GET_AUTH_USER_BOOKMARKED_POSTS']),
            ...mapActions('messenger', ['START_CONVERSATION']),
            loadMorePosts($state) {
                if (this.USER.privacy === 'private' &&
                    this.USER.isFollowed === false &&
                    !this.$isAuthUser(this.USER.id)) {
                    return;
                }

                this.GET_POSTS_BY_USERNAME({
                    username: this.$route.params.username,
                    page: this.currentPage
                }).then(({data}) => {
                    if (data.length) {
                        this.currentPage += 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });
            },
            loadMoreBookmarkedPosts($state) {
                this.GET_AUTH_USER_BOOKMARKED_POSTS(this.currentBookmarkedPage).then(({data}) => {
                    if (data.length) {
                        this.currentBookmarkedPage += 1;
                        this.bookmarked_posts.push(...data);
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
            },
            startConversation() {
                this.START_CONVERSATION(this.USER.username).then(r => {
                    if (r) {
                        console.log(r.conversation);
                        this.$router.push({name: 'conversation', params: {conversationId: r.conversation.id}})
                    }
                });
            }
        }
    }
 </script>

<style scoped lang="scss">
    header {
        position: relative;
        min-height: 320px;
        background-image: url("/images/backgrounds/Dragon-Scales.svg");
        background-attachment: fixed;
        background-size: contain;
        clip-path: polygon(
                        0 0,
                        100% 0,
                        100% 100%,
                        0 calc(100% - 5vw)
        );
        margin-top: -30px;
        color: #ffffff;
        .profile-heading {
            .column {
                &.is-2-tablet {
                    &.has-text-centered + .has-text-centered {
                        border-left: 1px dotted rgba(255, 255, 255, 0.2);
                    }
                }
            }
        }
    }

    .private-user {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .no-posts {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        .camera {
            .icon {
                border-radius: 100%;
                border: 1px solid;
                padding: 25px;
            }
        }
    }
</style>