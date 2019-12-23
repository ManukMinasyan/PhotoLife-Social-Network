<template>
    <div class="card">
        <div class="card-header">
            <div class="card-header-title is-centered">
                <b-button type="is-text" v-if="activeReportType !== false" @click="activeReportType = false">
                    <b-icon icon="arrow-left-drop-circle-outline"></b-icon>
                </b-button>
                Report
            </div>
        </div>
        <div class="card-content p-0">
            <div class="content" v-if="activeReportType === false">
                <div class="m-5">
                    Choose a reason for reporting this post. We won't tell
                    <strong>{{ post.author.username }}</strong> who reported
                    them.
                </div>
                <div class="list is-hoverable">
                    <a class="list-item" @click.prevent="selectReportType('dontLike')">
                        I just don't like it
                    </a>
                    <a class="list-item" v-for="type in TYPES"
                       @click.prevent="selectReportType(type)">
                        {{ type.name }}
                    </a>
                </div>
            </div>
            <div class="content p-5" v-else>
                <template v-if="activeReportType === 'dontLike'">
                    <p>
                        <strong>Don't like this profile?</strong><br>
                        Unfollow {{ post.author.username }} so you won''t see any of their photos, videos or story in
                        your feed.
                    </p>
                    <b-button type="is-primary" expanded>Unfollow</b-button>
                </template>
                <template v-else>
                    Thanks for reporting this. <br>
                    Your feedback helps our system learn when something isn't right.
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import BButton from "buefy/src/components/button/Button";
    import BIcon from "buefy/src/components/icon/Icon";

    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "ReportModalComponent",
        components: {BIcon, BButton},
        props: {
            post: {
                required: true
            }
        },
        data() {
            return {
                activeReportType: false
            }
        },
        created() {
            this.GET_TYPES();
        },
        computed: {
            ...mapGetters('report', ['TYPES'])
        },
        methods: {
            ...mapActions('report', ['GET_TYPES', 'REPORT_POST']),
            selectReportType(report_type) {
                this.activeReportType = report_type;

                if (report_type !== 'dontLike') {
                    this.REPORT_POST({post_id: this.post.id, report_type_id: report_type.id});
                }
            }
        }
    }
</script>

<style scoped>

</style>