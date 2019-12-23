<template>
    <AppLayout>
        <header class="mb-5">
            <div class='container profile-heading pt-30 pb-30'>
                <div class='columns is-mobile is-multiline'>
                    <div class='column is-offset-1-mobile'>
                        <h1 class='title is-bold has-text-light'>
                            Explore posts
                            <tempalte v-if="$route.name === 'hashtag'">
                                #{{ $route.params.tag }}
                            </tempalte>
                        </h1>
                        <h2 class="title is-5 has-text-light">
                            We have more than <span class="has-text-black-ter is-bold">{{ TOTAL_POSTS }}</span> posts.
                        </h2>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <transition-group name="fade" class="columns is-multiline">
                <post-component v-for="post in POSTS" :post="post" :is-explorer="true" :key="post.id"></post-component>
            </transition-group>
            <infinite-loading @infinite="loadMorePosts">
                <div slot="no-results" class="column no-posts">
                    <div class="camera">
                        <b-icon icon="camera" size="is-medium"></b-icon>
                    </div>
                    <h1 class="title">No Posts Yet</h1>
                </div>
            </infinite-loading>
        </div>
    </AppLayout>
</template>

<script>
    import AppLayout from "../../layouts/AppLayout";
    import {mapGetters, mapActions} from 'vuex';
    import PostComponent from "../User/PostComponent";

    export default {
        name: "ExploreComponent",
        components: {PostComponent, AppLayout},
        data() {
            return {
                currentPage: 1
            }
        },
        computed: {
            ...mapGetters('explorer', ['POSTS', 'TOTAL_POSTS'])
        },
        created() {
        },
        methods: {
            ...mapActions('explorer', ['GET_POSTS']),
            loadMorePosts($state) {
                let details = {page: this.currentPage};

                if (this.$route.name === 'hashtag') {
                    details.tag = this.$route.params.tag;
                }

                this.GET_POSTS(details).then(({data}) => {
                    if (data.length) {
                        this.currentPage += 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    header {
        position: relative;
        min-height: 220px;
        background-image: url("/images/backgrounds/Diamond-Sunset.svg");
        background-attachment: fixed;
        background-size: cover;
        clip-path: polygon(
                        100% 0,
                        100% 50%,
                        74% 80%,
                        26% 80%,
                        0 50%,
                        0 0);
        margin-top: -30px;
        color: #ffffff;
    }
</style>