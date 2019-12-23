<template>
    <div class="container is-fullhd">
        <div class="columns is-multiline box p-0">
            <div class="column is-12 has-text-centered">
                <h3>Likes</h3>
            </div>
            <div class="column is-12" v-for="liker in likers">
                <article class="media">
                    <figure class="media-left">
                        <router-link :to="{name: 'user', params: {username: liker.username}}">
                            <p class="image is-32x32">
                                <img class="is-rounded" :src="liker.avatar">
                            </p>
                        </router-link>
                    </figure>
                    <div class="media-content">
                        <router-link :to="{name: 'user', params: {username: liker.username}}">
                            <strong>{{ liker.username }}</strong>
                        </router-link>
                        <div>{{ liker.name }}</div>
                    </div>
                    <div class="media-right">
                        <a class="button" v-if="!$isAuthUser(liker.id)"
                           @click.prevent="FOLLOW_USER(liker)"
                           :class="{'is-primary': !liker.isFollowed && !liker.isRequested}">
                            <template v-if="liker.isRequested">
                                Requested
                            </template>
                            <template v-else-if="liker.isFollowed">
                                Following
                            </template>
                            <template v-else>
                                Follow
                            </template>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        name: "LikersModalComponent",
        props: ['likers'],
        watch: {
            '$route': function () {
                this.$emit('close')
            }
        },
        methods: {
            ...mapActions('user', ['FOLLOW_USER']),
        }
    }
</script>

<style scoped lang="scss">

</style>