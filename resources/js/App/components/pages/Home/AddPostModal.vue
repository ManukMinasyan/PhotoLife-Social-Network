<template>
    <form action="#" class="add-post-modal" ref="form">
        <div class="modal-card" style="width: 100%">
            <header class="modal-card-head">
                <div class="modal-card-title">
                    <div class="is-inline-block">
                        <figure class="image is-24x24">
                            <img class="avatar is-rounded" :src="AUTH_USER.avatar">
                        </figure>
                    </div>
                    <div class="is-inline-block">
                        {{ AUTH_USER.username }}
                    </div>
                </div>
            </header>
            <form action="#" method="POST" @submit="storePost" ref="form">
                <section class="modal-card-body">
                    <b-field label="Add post caption, #hashtag.. @mention?"
                             label-position="on-border">
                        <b-input maxlength="500" type="textarea" rows="2" v-model="post.caption"></b-input>
                    </b-field>

                    <b-field>
                        <b-upload class="post-upload-section" required="true"
                                  multiple
                                  drag-drop accept="image/*,video/*" @input="handleFilesUpload" :loading="loading"
                                  ref="upload">
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon
                                                icon="upload"
                                                size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>Drop your files here or click to upload</p>
                                </div>
                            </section>
                        </b-upload>
                    </b-field>
                    <div class="columns preview-images">
                        <div class="column is-one-fifth card" v-for="(uploadItem, key) in previewUploads" :key="key">
                            <b-icon icon="close-circle" type="is-danger" @click.native="deletePostItem(key)"></b-icon>
                            <template v-if="uploadItem.src.split('/')[0] === 'data:image'">
                                <figure class="image is-square">
                                    <img :src="uploadItem.src">
                                </figure>
                            </template>
                            <template v-else>
                                <video width="320" height="240" controls>
                                    <source :src="uploadItem.src" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </template>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="$parent.close()">Close</button>
                    <button class="button is-primary" @click.prevent="storePost">Publish</button>
                </footer>
            </form>
        </div>
    </form>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "AddPhotoModal",
        data() {
            return {
                previewUploads: [],
                post: {
                    caption: '',
                    uploads: []
                },
                loading: false
            }
        },
        computed: {
            ...mapGetters('user', ['AUTH_USER']),
        },
        methods: {
            ...mapActions('post', ['UPLOAD_POST']),
            deletePostItem(index) {
                this.previewUploads.splice(index, 1);
                this.post.uploads.splice(index, 1)
            },
            handleFilesUpload: function (images) {
                if (images.length > 5) {
                    this.$buefy.dialog.alert({
                        title: 'Error',
                        message: 'You cannot upload more than 5 photos at a time!',
                        type: 'is-danger',
                        ariaRole: 'alertdialog'
                    });
                    return;
                }

                this.post.uploads = images;

                let self = this;
                for (let i = 0; i < images.length; i++) {
                    let file = images[i];
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        self.$set(self.previewUploads, i, {src: e.target.result});
                    };
                    reader.readAsDataURL(file);
                }
            },
            storePost() {
                this.loading = true;
                let formData = new FormData();
                formData.append('caption', this.post.caption);

                for (var i = 0; i < this.post.uploads.length; i++) {
                    let file = this.post.uploads[i];
                    formData.append('uploads[' + i + ']', file);
                }

                this.UPLOAD_POST(formData).then(r => {
                    this.loading = false;
                    this.$refs.form.reset();
                    this.post = {
                        caption: '',
                        uploads: []
                    };
                    this.previewUploads = [];
                    this.$parent.close();
                }).catch(e => {
                    this.loading = false;
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .preview-images {
        .column {
            .icon {
                position: absolute;
                z-index: 1;
                top: 0;
                right: 0
            }
        }
    }
</style>

