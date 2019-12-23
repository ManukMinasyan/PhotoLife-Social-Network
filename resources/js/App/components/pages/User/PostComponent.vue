<template>
    <div class="column is-4" @click="postModal(post.id)">
        <div class='card'>
            <div class='card-image'>
                <figure class='image is-4by3'>
                    <img :src='post.media[0].thumb_url' class="post-image">
                </figure>
                <div class="hover-area">
                    <div class="mr-5">
                        <b-icon icon="heart" type="is-light"></b-icon>
                        <span class="has-text-light">{{ post.likers.length }}</span>
                    </div>
                    <div class="ml-5">
                        <b-icon icon="comment" type="is-light"></b-icon>
                        <span class="has-text-light">{{ post.comments_count }}</span>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
</template>

<script>
    import PostComponentModal from "../Post/PostComponentModal";

    export default {
        name: "PostComponent",
        props: {
            post: {
                required: true
            }
        },
        methods: {
            postModal(id) {
                this.$buefy.modal.open({
                    props: {id: id},
                    parent: this,
                    component: PostComponentModal,
                    customClass: 'overflow-none'
                })
            },
            isImage(mime_type) {
                let mime_types = ['image/jpeg', 'image/png'];
                return mime_types.includes(mime_type);
            },
            isVideo(mime_type) {
                let mime_types = ['video/mp4'];
                return mime_types.includes(mime_type);
            }
        }
    }
</script>

<style scoped lang="scss">
    .card-image {
        cursor: pointer;
        position: relative;
        .hover-area {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #000000;
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        &:hover .hover-area {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.3);
        }
    }
</style>