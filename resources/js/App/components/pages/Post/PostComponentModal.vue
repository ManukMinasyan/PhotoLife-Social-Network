<template>
    <div class="container post-container is-fullhd">
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
        <div class="columns is-gapless p-0">
            <div class="column is-8">
                <article>
                    <b-carousel
                            animated="slide"
                            :arrow="POST.media.length > 1"
                            :arrow-hover="!POST.media.length"
                            :autoplay="false"
                            :pause-info="false"
                            :interval="3000">
                        <b-carousel-item v-for="(media, key) in POST.media" :key="key">
                            <template
                                    v-if="media.mime_type === 'image/jpeg' || media.mime_type === 'image/jpg' || media.mime_type === 'image/png' || media.mime_type === 'image/gif'">
                                <figure class="image is-1by1">
                                    <img :src="media.full_url" class="post-image" alt="Image">
                                </figure>
                            </template>
                            <template v-else>
                                <video-preview-component :video="media"></video-preview-component>
                            </template>
                        </b-carousel-item>
                    </b-carousel>
                </article>
            </div>
            <div class="column is-4">
                <article class="media user-info p-15">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            <img class="avatar is-rounded" :src="POST.author.avatar">
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <router-link :to="{name: 'user', params: {username: POST.author.username}}">
                                    <strong class="has-text-black">{{ POST.author.username }}</strong>
                                </router-link>
                                <template v-if="!$isAuthUser(POST.author.id)">
                                    <span class="has-text-weight-bold">·</span>
                                    <a href="#" @click.prevent="followAuthor">
                                        <template v-if="POST.author.isFollowed">
                                            <span class="has-text-weight-bold">Follow</span>
                                        </template>
                                        <template v-else>
                                            <span class="has-text-black">Following</span>
                                        </template>
                                    </a>
                                </template>
                            </p>
                        </div>
                    </div>
                    <div class="media-right">
                        <b-dropdown aria-role="list" position="is-bottom-left">
                            <a slot="trigger">
                                <b-icon icon="dots-horizontal"></b-icon>
                            </a>

                            <template v-if="$isAuthUser(POST.author.id)">
                                <b-dropdown-item aria-role="listitem" @click="openEditPostModal">
                                    <b-icon size="is-small" icon="account-lock"></b-icon>
                                    Edit
                                </b-dropdown-item>
                                <b-dropdown-item aria-role="listitem" class="has-text-danger"
                                                 @click="deletePost">
                                    <b-icon size="is-small" icon="trash-can"></b-icon>
                                    Delete
                                </b-dropdown-item>
                            </template>
                            <template v-else>
                                <b-dropdown-item aria-role="listitem" class="has-text-danger"
                                                 @click="openReportModal">
                                    <b-icon size="is-small" icon="alert-octagon"></b-icon>
                                    Report inappropriate
                                </b-dropdown-item>
                            </template>
                            <b-dropdown-item aria-role="listitem" @click="openEmbedModal"
                                             v-if="POST.author.privacy === 'public'">
                                <b-icon size="is-small" icon="code-braces-box"></b-icon>
                                Embed
                            </b-dropdown-item>
                        </b-dropdown>
                    </div>
                </article>
                <div class="comments-container has-custom-scrollbar">
                    <article class="media mb-10 p-15">
                        <figure class="media-left">
                            <p class="image is-32x32">
                                <img class="avatar is-rounded" :src="POST.author.avatar">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ POST.author.username }}</strong>
                                    <text-helper :text="POST.caption"></text-helper>
                                    <br>
                                    <small>{{ POST.created_at }}</small>
                                </p>
                            </div>
                        </div>
                    </article>
                    <div class="comments media-content mb-10">
                        <article class="media pl-15" v-for="(comment, key) in POST.comments" :key="key">
                            <figure class="media-left">
                                <p class="image is-32x32">
                                    <img class="avatar is-rounded" :src="comment.author.avatar">
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <strong>{{ comment.author.username }}</strong>
                                        {{ comment.body }}
                                        <br>
                                        <small>
                                            {{ comment.created_at }}
                                            <a href="#" @click.prevent="likersModal(comment.likers)"
                                               v-if="comment.likers.length" class="ml-5 mr-5">
                                                {{ comment.likers.length }} likes
                                            </a>
                                            <a href="#" @click.prevent="replyComment(comment)">
                                                Reply
                                            </a>
                                        </small>
                                    </p>
                                    <div class="replies" v-if="comment.replies.length">
                                        <a class="is-block is-size-7 has-text-weight-bold has-text-grey"
                                           @click.prevent="showReplies(comment)">
                                            <span>━</span>
                                            View replies ({{comment.replies.length}})
                                        </a>
                                        <article class="media m-0" v-for="(reply, reply_key) in comment.replies"
                                                 :key="reply_key" v-if="!!comment.isShowingReplies">
                                            <figure class="media-left ml-0 mr-5">
                                                <p class="image is-32x32">
                                                    <img class="avatar is-rounded" :src="reply.author.avatar">
                                                </p>
                                            </figure>
                                            <div class="media-content">
                                                <div class="content">
                                                    <p>
                                                        <strong>{{ reply.author.username }}</strong>
                                                        {{ reply.body }}
                                                        <br>
                                                        <small>
                                                            {{ reply.created_at }}
                                                            <a href="#" @click.prevent="likersModal(reply.likers)"
                                                               v-if="reply.likers.length" class="ml-5 mr-5">
                                                                {{ reply.likers.length }} likes
                                                            </a>
                                                            <a href="#" @click.prevent="replyComment(comment)">
                                                                Reply
                                                            </a>
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="media-right">
                                                <a href="#" @click.prevent="likeComment(reply)">
                                                    <b-icon icon="heart" size="is-small" type="is-danger"
                                                            v-if="reply.isLiked"></b-icon>
                                                    <b-icon icon="heart-outline" size="is-small" v-else></b-icon>
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                            </div>
                            <div class="media-right pr-15">
                                <a href="#" @click.prevent="likeComment(comment)">
                                    <b-icon icon="heart" size="is-small" type="is-danger"
                                            v-if="comment.isLiked"></b-icon>
                                    <b-icon icon="heart-outline" size="is-small" v-else></b-icon>
                                </a>
                            </div>
                        </article>
                    </div>
                </div>
                <div class="actions-level mt-5">
                    <nav class="level is-mobile m-0 mt-5 p-10 pb-0">
                        <div class="level-left">
                            <a class="level-item" @click.prevent="LIKE_UNLIKE_POST(POST.id)">
                                <b-icon icon="heart-outline" v-if="!POST.isLiked"></b-icon>
                                <b-icon icon="heart" type="is-danger" v-else></b-icon>
                            </a>
                            <label class="level-item has-text-primary" for="comment">
                                <b-icon icon="comment-outline"></b-icon>
                            </label>
                        </div>
                        <div class="level-right">
                            <a class="level-item" @click.prevent="BOOKMARK_UNBOOKMARK_POST(POST.id)">
                                <b-icon icon="bookmark-outline" v-if="!POST.isBookmarked"></b-icon>
                                <b-icon icon="bookmark" v-else></b-icon>
                            </a>
                        </div>
                    </nav>
                    <nav class="level is-mobile m-0 pl-10">
                        <div class="level-left">
                            <a class="level-item" @click.prevent="likersModal(POST.likers)">
                                {{ POST.likers.length }} likes
                            </a>
                        </div>
                    </nav>
                </div>
                <article class="write-comment-container media m-0">
                    <div class="media-content">
                        <b-field>
                            <b-input placeholder="Add a comment..." v-model="comment"
                                     @keyup.enter.native="storeComment" id="comment" ref="comment"></b-input>
                        </b-field>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'
    import TextHelper from "../../../helpers/TextHelper";
    import VideoPreviewComponent from "./VideoPreviewComponent";

    const LikersModalComponent = () => ({
        component: import("../Post/LikersModalComponent")
    });

    const ReportModalComponent = () => ({
        component: import("../Post/ReportModalComponent")
    });

    const EditPostModalComponent = () => ({
        component: import("../Post/EditPostModalComponent")
    });

    export default {
        name: "PostComponentModal",
        components: {
            VideoPreviewComponent,
            TextHelper
        },
        props: {
            id: {
                type: [Boolean, Number],
                default: false
            }
        },
        data() {
            return {
                comment: '',
                replyCommentId: false,
                embed_post_code: null,
                isEmbedModalActive: false,
            }
        },
        created() {
            if (this.id) {
                this.FIND_POST_BY_ID(this.id);
            }
        },
        computed: {
            ...mapGetters('post', ['POST']),
            ...mapGetters('user', ['AUTH_USER'])
        },
        methods: {
            ...mapActions('post', ['FIND_POST_BY_ID', 'DELETE_POST', 'LIKE_UNLIKE_POST',
                'BOOKMARK_UNBOOKMARK_POST', 'ADD_COMMENT', 'LIKE_UNLIKE_COMMENT']),
            ...mapActions('user', ['FOLLOW_USER']),
            followAuthor() {
                this.FOLLOW_USER(this.POST.author.username);
                this.POST.author.isFollowed = !this.POST.author.isFollowed;
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
            storeComment() {
                let details = {
                    post_id: this.POST.id,
                    comment: this.comment
                };

                if (this.replyCommentId) {
                    details.replyCommentId = this.replyCommentId;
                }

                this.ADD_COMMENT(details).then(r => {
                    if (this.replyCommentId) {
                        let comment = this.POST.comments.find(p => p.id === this.replyCommentId);
                        comment.replies.push(r.data);
                    } else {
                        this.POST.comments.unshift(r.data);
                    }

                    this.comment = '';
                    this.replyCommentId = false;
                });
            },
            replyComment(comment) {
                this.comment = '@' + comment.author.username + ' ';
                this.$nextTick(() => this.$refs.comment.focus());
                this.replyCommentId = comment.id;
            },
            showReplies(comment) {
                this.$set(comment, 'isShowingReplies', !comment.isShowingReplies);
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
            openEditPostModal() {
                this.$buefy.modal.open({
                    props: {post: this.POST},
                    parent: this,
                    width: 640,
                    scroll: 'keep',
                    trapFocus: true,
                    hasModalCard: true,
                    component: EditPostModalComponent,
                    customClass: 'overflow-none'
                })
            },
            deletePost() {
                this.$buefy.dialog.confirm({
                    title: 'Deleting post',
                    message: 'Are you sure you want to <b>delete</b> your post? This action cannot be undone.',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.DELETE_POST(this.POST.id).then(r => {
                            this.$parent.close();
                            this.$buefy.toast.open('Post deleted successfully!');
                            this.$router.push({name: 'user', params: {username: this.POST.author.username}});
                        })
                    }
                })
            },
            openEmbedModal() {
                let url = window.location.protocol + '//' + window.location.host + '/embed/post/' + this.POST.id;
                this.embed_post_code = `<iframe src="${url}" width="500" height="230" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>`;
                this.isEmbedModalActive = true;
            },
            copyEmbedCode() {
                let copyText = this.$refs.embedCode.$el.querySelector('input');
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
            },
            openReportModal() {
                this.$buefy.modal.open({
                    props: {post: this.POST},
                    parent: this,
                    hasModalCard: true,
                    component: ReportModalComponent,
                    customClass: 'overflow-none'
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .actions-level {
        border-top: 1px solid #efefef;
        border-bottom: 1px solid #efefef;
    }
</style>