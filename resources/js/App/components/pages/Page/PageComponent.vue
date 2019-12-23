<template>
    <AppLayout>
        <header class="mb-5">
            <div class='container profile-heading pt-30 pb-30'>
                <div class='columns is-mobile is-multiline'>
                    <div class='column'>
                        <h1 class='title is-bold has-text-light'>
                            {{ PAGE.title }}
                        </h1>
                        <h2 class="title is-5 has-text-light">
                            {{ PAGE.description }}
                        </h2>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="card">
                        <div class="card-content" v-html="PAGE.content"></div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
    import AppLayout from "../../layouts/AppLayout";

    import {mapGetters} from 'vuex';

    export default {
        name: "PageComponent",
        components: {AppLayout},
        computed: {
            ...mapGetters('page', ['PAGE'])
        }
    }
</script>

<style scoped lang="scss">
    header {
        position: relative;
        min-height: 100px;
        background-image: url("/images/backgrounds/Endless-Constellation.svg");
        background-attachment: fixed;
        background-size: cover;
        margin-top: -30px;
        color: #ffffff;
    }
</style>