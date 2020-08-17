<template>
    <div>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="Edit" active>
                    <b-form-textarea
                        id="content"
                        v-model.trim="comment.content"
                        placeholder="Enter something..."
                        rows="3"
                        max-rows="6"
                    ></b-form-textarea>
                </b-tab>
                <b-tab title="Preview">
                    <b-card-text v-html="marked(comment.content ? comment.content : '')"></b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <b-button block variant="outline-primary" @click="reply()">Comment</b-button>
    </div>
</template>

<script>
import { marked } from '../../utils/markedHelper'
import { mapActions, mapState, mapMutations } from 'vuex'

export default {
    name: 'Editor',
    props: {
        comment_id: Number
    },
    data() {
        return {
            comment: {
                blog_id: 0,
                content: '',
                parent_id: this.comment_id,
            },
        }
    },
    methods: {
        reply() {
            // this.comment.parent_id = this.comment_id
            this.comment.blog_id = this.blog_id
            console.log(this.comment)
            // this.$store.dispatch('comments/toggleReply')
            this.toggleReply()
            this.$store.dispatch('comments/reply', this.comment)
            // this.comment.content = ''
        },
        ...mapMutations({
            toggleReply: 'comments/toggleReply'
        }),
        marked: marked
    },
    computed: {
        ...mapState({
            blog_id: state => state.comments.blog_id
        })
    },
    created() {}
}
</script>

<style>

</style>