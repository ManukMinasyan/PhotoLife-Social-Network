<template>
    <form action="#" class="edit-post-modal">
        <div class="modal-card">
            <header class="modal-card-head">
                <div class="modal-card-title">
                    <div class="is-inline-block is-pulled-left mr-5">
                        <figure class="image is-48x48">
                            <img :src="post.media[0].thumb_url">
                        </figure>
                    </div>
                    <div class="is-inline-block">
                        Edit Post
                    </div>
                </div>
            </header>
            <form action="#" method="POST" ref="form">
                <section class="modal-card-body">
                    <b-field label="Add post caption, #hashtag.. @mention?"
                             label-position="on-border">
                        <b-input maxlength="500" type="textarea" rows="2" v-model="caption"></b-input>
                    </b-field>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="$parent.close()">Close</button>
                    <b-button type="is-primary" :loading="loading" @click.prevent="updatePost">Save changes</b-button>
                </footer>
            </form>
        </div>
    </form>
</template>

<script>
    import {mapActions} from 'vuex';
    import BButton from "buefy/src/components/button/Button";

    export default {
        name: "EditPostModalComponent",
        components: {BButton},
        props: ['post'],
        data() {
            return {
                loading: false,
                caption: this.post.caption
            }
        },
        methods: {
            ...mapActions('post', ['UPDATE_POST']),
            updatePost() {
                let self = this;
                self.loading = true;
                self.UPDATE_POST({id: self.post.id, caption: self.caption}).then(r => {
                    self.$buefy.notification.open({
                        message: 'Post updated successfully!',
                        type: 'is-success'
                    });
                    self.loading = false;
                }).catch(e => {
                    self.loading = false;
                })
            }
        }
    }
</script>

<style scoped lang="scss">

</style>