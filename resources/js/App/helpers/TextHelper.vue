<script>
  import emojione from 'emojione'

  export default {
    name: 'TextHelper',
    template: '<component v-bind:is="transformed"></component>',
    props: {
      text: {
        required: true,
      },
      tag: {
        required: false,
        type: String,
        default: () => 'span',
      },
      size: {
        required: false,
        type: [Number, Boolean],
        default: () => false,
      },
    },
    methods: {
      convertHashTags: function (str, tag, size) {
        if (typeof str === 'undefined' || str === null || str === '') {
          return `<${tag}></${tag}>`
        }

        if (size) {
          str = str.substring(0, size) + '...'
        }
        str = str.replace(/<[^>]*>/g, '').
          replace(/#([\w]+)/g, '<router-link to="/explore/tag/$1">#$1</router-link>').
          replace(/@([\w]+)/g, '<router-link to="/$1" class="has-text-dark">@$1</router-link>')
        str = emojione.toImage(str)

        return `<${tag}>${str}</${tag}>`
      },
    },
    computed: {
      transformed () {
        const template = this.convertHashTags(this.text, this.tag, this.size)
        return {
          template: template,
        }
      },
    },
  }
</script>