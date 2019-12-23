<template>
    <div class="video-wrapper">
        <video class="video" width="100%" ref="video">
            <source :src="video.full_url" type="video/mp4"/>
        </video>
        <div class="play-pause" @click="playVideo" v-if="isShowedPlayIcon">
            <img :src="video.thumb_url" class="img">
            <b-icon icon="play" custom-class="play-video-icon"></b-icon>
        </div>
    </div>
</template>

<script>
    import BIcon from "buefy/src/components/icon/Icon";

    export default {
        name: "VideoPreviewComponent",
        components: {BIcon},
        props: {
            video: {
                required: true
            }
        },
        data() {
            return {
                isShowedPlayIcon: true
            }
        },
        methods: {
            playVideo() {
                const videoEl = this.$refs.video;
                videoEl.play();
                this.isShowedPlayIcon = false;
            }
        }
    }
</script>

<style scoped lang="scss">
    .img {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
</style>