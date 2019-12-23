<template>
    <div class="column is-12">
        <b-modal :active.sync="isEmbedModalActive" :width="440" scroll="keep">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <b-input :value="embed_post_code" ref="embedCode"></b-input>
                        <b-button type="is-primary" class="mt-10" @click="copyEmbedCode">Copy Embed Code</b-button>
                    </div>
                </div>
            </div>
        </b-modal>
        <div class="box p-0">
            <article>
                <div class="media-content">
                    <article class="media p-10">
                        <figure class="media-left">
                            <router-link :to="{name: 'user', params: {username: post.author.username}}">
                                <p class="image is-32x32">
                                    <img class="avatar is-rounded" :src="post.author.avatar">
                                </p>
                            </router-link>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <router-link :to="{name: 'user', params: {username: post.author.username}}">
                                        <strong>{{ post.author.username }}</strong>
                                    </router-link>
                                </p>
                            </div>
                        </div>
                        <div class="media-right">
                            <router-link :to="{name: 'post', params: {id: post.id}}" class="has-text-grey">
                                <time>{{ post.created_at }}</time>
                            </router-link>
                            <b-dropdown aria-role="list">
                                <a slot="trigger">
                                    <b-icon icon="dots-horizontal"></b-icon>
                                </a>

                                <template v-if="$isAuthUser(post.author.id)">
                                    <b-dropdown-item aria-role="listitem" @click="openEditPostModal(post)">
                                        <b-icon size="is-small" icon="account-lock"></b-icon>
                                        Edit
                                    </b-dropdown-item>
                                    <b-dropdown-item aria-role="listitem" class="has-text-danger"
                                                     @click="deletePost(post.id)">
                                        <b-icon size="is-small" icon="trash-can"></b-icon>
                                        Delete
                                    </b-dropdown-item>
                                </template>
                                <template v-else>
                                    <b-dropdown-item aria-role="listitem" class="has-text-danger"
                                                     @click="openReportModal(post)">
                                        <b-icon size="is-small" icon="alert-octagon"></b-icon>
                                        Report inappropriate
                                    </b-dropdown-item>
                                </template>
                                <b-dropdown-item aria-role="listitem" @click="openEmbedModal(post.id)"
                                                 v-if="post.author.privacy === 'public'">
                                    <b-icon size="is-small" icon="code-braces-box"></b-icon>
                                    Embed
                                </b-dropdown-item>
                            </b-dropdown>
                        </div>
                    </article>
                    <template v-if="post.media[0].mime_type === 'image/jpg' || post.media[0].mime_type === 'image/jpeg' || post.media[0].mime_type === 'image/png' || post.media[0].mime_type === 'image/gif'">
                        <figure class="image is-1by1">
                            <img :src="post.media[0].full_url" alt="Image" class="post-image">
                        </figure>
                    </template>
                    <template v-else>
                        <video-preview-component :video="post.media[0]"></video-preview-component>
                    </template>
                    <nav class="level is-mobile m-0 mt-5 p-10 pb-0">
                        <div class="level-left">
                            <a class="level-item" @click.prevent="LIKE_UNLIKE_POST(post.id)">
                                <b-icon icon="heart-outline" v-if="!post.isLiked"></b-icon>
                                <b-icon icon="heart" type="is-danger" v-else></b-icon>
                            </a>
                            <router-link :to="{name: 'post', params: {id: post.id}}" class="level-item">
                                <b-icon icon="comment-outline"></b-icon>
                            </router-link>
                        </div>
                        <div class="level-right">
                            <a class="level-item" @click.prevent="BOOKMARK_UNBOOKMARK_POST(post.id)">
                                <b-icon icon="bookmark-outline" v-if="!post.isBookmarked"></b-icon>
                                <b-icon icon="bookmark" v-else></b-icon>
                            </a>
                        </div>
                    </nav>
                    <nav class="level is-mobile m-0 pl-10">
                        <div class="level-left">
                            <a class="level-item" @click.prevent="likersModal(post.likers)">
                                {{ post.likers.length }} likes
                            </a>
                        </div>
                    </nav>
                    <div class="mb-5 pl-10 pr-10">
                        <router-link :to="{name: 'user', params: {username: post.author.username}}">
                            <strong class="has-text-black">{{ post.author.username }}</strong>
                        </router-link>
                        <text-helper :text="post.caption"></text-helper>
                    </div>
                    <template v-if="post.comments.length">
                        <router-link :to="{name: 'post', params: {id: post.id}}" class="pl-10 has-text-grey">
                            View all comments
                            <span>({{ post.comments.length }})</span>
                        </router-link>
                        <article class="media p-10">
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <strong>{{ post.comments[0].author.username }}</strong>
                                        <text-helper :text="post.comments[0].body"></text-helper>
                                        <br>
                                        <small>
                                            {{ post.comments[0].created_at }}
                                            <a href="#" @click.prevent="likersModal(post.comments[0].likers)"
                                               v-if="post.comments[0].likers.length" class="ml-5 mr-5">
                                                {{ post.comments[0].likers.length }} likes
                                            </a>
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="media-right">
                                <div class="level-right">
                                    <a href="#" @click.prevent="likeComment(post.comments[0])">
                                        <b-icon icon="heart" size="is-small" type="is-danger"
                                                v-if="post.comments[0].isLiked"></b-icon>
                                        <b-icon icon="heart-outline" size="is-small" v-else></b-icon>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </template>
                    <article class="media p-10">
                        <div class="media-content">
                            <b-field>
                                <b-input placeholder="Write a comment..." v-model="post.comment"
                                         @keyup.enter.native="storeComment(post)"></b-input>
                            </b-field>
                        </div>
                    </article>
                </div>
            </article>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'

    const ReportModalComponent = () => ({
        component: import("../Post/ReportModalComponent")
    });

    const LikersModalComponent = () => ({
        component: import("../Post/LikersModalComponent")
    });

    const EditPostModalComponent = () => ({
        component: import("../Post/EditPostModalComponent")
    });

    import TextHelper from "../../../helpers/TextHelper";
    import VideoPreviewComponent from "../Post/VideoPreviewComponent";

    export default {
        name: "PostComponent",
        components: {VideoPreviewComponent, TextHelper},
        props: ["post"],
        data() {
            return {
                embed_post_code: null,
                isEmbedModalActive: false,
            }
        },
        computed: {
            ...mapGetters('user', ['AUTH_USER'])
        },
        methods: {
            ...mapActions('post', ['LIKE_UNLIKE_POST', 'BOOKMARK_UNBOOKMARK_POST', 'ADD_COMMENT', 'LIKE_UNLIKE_COMMENT', 'DELETE_POST']),
            storeComment(post) {
                let details = {
                    post_id: post.id,
                    comment: post.comment
                };

                if (post.replyCommentId) {
                    details.replyCommentId = post.replyCommentId;
                }

                this.ADD_COMMENT(details).then(r => {
                    if (post.replyCommentId) {
                        let comment = this.post.comments.find(p => p.id === this.replyCommentId);
                        comment.replies.push(r.data);
                    } else {
                        this.post.comments.unshift(r.data);
                    }

                    post.comment = '';
                    post.replyCommentId = false;
                });
            },
            likeComment(comment) {
                this.LIKE_UNLIKE_COMMENT(comment.id);
                comment.isLiked = !comment.isLiked;

                if (comment.isLiked) {
                    comment.likers.unshift(this.AUTH_USER);
                } else {
                    let index = comment.likers.map(x => {
                        return x.id;
                    }).indexOf(this.AUTH_USER.id);
                    comment.likers.splice(index, 1);
                }
            },
            likersModal(likers) {
                this.$buefy.modal.open({
                    props: {likers: likers},
                    parent: this,
                    hasModalCard: true,
                    component: LikersModalComponent,
                    customClass: 'overflow-none'
                })
            },
            openEditPostModal(post) {
                this.$buefy.modal.open({
                    props: {post: post},
                    parent: this,
                    width: 640,
                    scroll: 'keep',
                    trapFocus: true,
                    hasModalCard: true,
                    component: EditPostModalComponent,
                    customClass: 'overflow-none'
                })
            },
            deletePost(post_id) {
                this.$buefy.dialog.confirm({
                    title: 'Deleting post',
                    message: 'Are you sure you want to <b>delete</b> your post? This action cannot be undone.',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.DELETE_POST(post_id).then(r => {
                            this.$buefy.toast.open('Post deleted successfully!')
                        })
                    }
                })
            },
            openEmbedModal(post_id) {
                let url = window.location.protocol + '//' + window.location.host + '/embed/post/' + post_id;
                this.embed_post_code = `<iframe src="${url}" width="500" height="230" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>`;
                this.isEmbedModalActive = true;
            },
            copyEmbedCode() {
                let copyText = this.$refs.embedCode.$el.querySelector('input');
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
            },
            openReportModal(post) {
                this.$buefy.modal.open({
                    props: {post: post},
                    parent: this,
                    hasModalCard: true,
                    component: ReportModalComponent,
                    customClass: 'overflow-none'
                });
            },
        }
    }
</script>

<style scoped lang="scss">
    .overflow-none {
        .container {
            overflow: hidden !important;
        }
    }
</style>