<script>
    import emojione from 'emojione';

    export default {
        name: "TextHelper",
        template: '<component v-bind:is="transformed"></component>',
        props: ['text'],
        methods: {
            convertHashTags: function (str) {
                if (typeof str === 'undefined' || str === null || str === '') {
                    return '<span></span>';
                }

                str = str.replace(/#([\w]+)/g, '<router-link to="/explore/tag/$1">#$1</router-link>');
                str = str.replace(/@([\w]+)/g, '<router-link to="/$1" class="has-text-dark">@$1</router-link>');
                str = emojione.toImage(str)
                return '<span>' + str + '</span>'

            }
        },
        computed: {
            transformed() {
                const template = this.convertHashTags(this.text);
                return {
                    template: template
                }
            }
        }
    }
</script>