<template>
    <AppLayout>
        <div class="container is-fullhd">
            <div class="columns is-mobile">
                <div class="column is-8-tablet is-8-desktop">
                    <div class="section sharing-panel mb-30">
                        <div class="columns">
                            <div class="column is-3">
                                <b-button size="is-medium" style="width:100%" @click="isPhotoAddModalActive = true">
                                    <b-icon icon="camera" type="is-primary"></b-icon>
                                    <span class="has-text-grey">&</span>
                                    <b-icon icon="video" type="is-success"></b-icon>
                                </b-button>
                                <b-modal :active.sync="isPhotoAddModalActive" scroll="keep" :width="600"
                                         :can-cancel="false"
                                         trap-focus>
                                    <add-post-modal v-if="isPhotoAddModalActive"></add-post-modal>
                                </b-modal>
                            </div>
                            <div class="column is-9">
                                <div class="box has-text-right pt-10 pb-10">
                                    {{ randomQuote }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section pt-0 posts">
                        <div class="columns">
                            <div class="column">
                                <h5>Latest posts</h5>
                            </div>
                        </div>
                        <transition-group name="fade">
                            <post-component v-for="post in POSTS" :post="post" :key="post.id"></post-component>
                        </transition-group>
                        <infinite-loading @infinite="loadMorePosts">
                            <div slot="no-more" class="has-text-white">
                                You've scrolled through all the posts :)
                            </div>
                            <div slot="no-results" class="has-text-white">
                                Sorry, no posts yet :(
                            </div>
                        </infinite-loading>
                    </div>
                </div>
                <div class="column is-4 is-hidden-mobile" id="sidebar">
                    <div class="columns is-multiline">
                        <div class="column is-12 is-hidden-mobile">
                            <div class="section featured-posts">
                                <h5>
                                    Popular posts
                                    <span class="icon is-small has-text-danger is-pulled-right">
                             <i class="ion-ionic ion-md-heart"></i>
                        </span>
                                </h5>
                                <div class="posts-list">
                                    <div class="columns is-multiline">
                                        <user-post-component v-for="post in POPULAR_POSTS"
                                                             :post="post"></user-post-component>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 is-hidden-mobile">
                            <ul class="navbar-footer">
                                <li v-for="page in PAGES">
                                    <router-link :to="{name: 'page', params: {alias: page.alias}}">
                                        {{ page.title }}
                                    </router-link>
                                </li>
                            </ul>
                            © 2019 PhotoLife
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex'

    import PostComponent from "./PostComponent";
    import UserPostComponent from "../User/PostComponent";
    import AppLayout from "../../layouts/AppLayout";

    const AddPostModal = () => ({
        component: import('./AddPostModal.vue')
    });


    export default {
        name: "HomeComponent",
        components: {AppLayout, PostComponent, UserPostComponent, AddPostModal},
        data() {
            return {
                randomQuote: '...',
                isPhotoAddModalActive: false,
                currentPage: 1
            }
        },
        created() {
            this.GET_RANDOM_QUOTE().then(r => {
                this.randomQuote = r.data;
            });
            this.GET_POSTS_BY_POPULARITY();
            this.GET_PAGES();

            $(function () {
                var $sidebar = $("#sidebar"),
                    $window = $(window),
                    offset = $sidebar.offset(),
                    topPadding = 0;

                $window.scroll(function () {
                    if ($window.scrollTop() > offset.top) {
                        $sidebar.stop().animate({
                            marginTop: $window.scrollTop() - offset.top + topPadding
                        });
                    } else {
                        $sidebar.stop().animate({
                            marginTop: 0
                        });
                    }
                });
            });
        },
        computed: {
            ...mapGetters('post', ['POSTS', 'POPULAR_POSTS']),
            ...mapGetters('page', ['PAGES'])
        },
        methods: {
            ...mapActions(['GET_RANDOM_QUOTE']),
            ...mapActions('post', ['GET_POSTS', 'GET_POSTS_BY_POPULARITY']),
            ...mapActions('page', ['GET_PAGES']),
            loadMorePosts($state) {
                this.GET_POSTS(this.currentPage).then(({data}) => {
                    if (data.length) {
                        this.currentPage += 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
            }
        }
    }
</script>


<style appear lang="scss">
    .sharing-panel {
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.14);
        background-color: white;
        border-radius: 3px;
    }

    .section.posts {
        padding: 0;
    }

    .section.featured-posts {
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.14);
        background-color: white;
        border-radius: 3px;
        padding: 0;

        h5 {
            padding: 10px;
            border-bottom: 1px solid #eee;
            overflow: hidden;
            color: #6a6a6a;
            font-size: 15px;
            line-height: 25px;
        }

        .posts-list {
            padding: 10px;
        }
    }

    .navbar-footer {
        li {
            display: inline-block;
            margin-right: 5px;
        }
    }

</style>
