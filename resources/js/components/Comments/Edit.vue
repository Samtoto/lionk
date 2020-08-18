<template>
    <div>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="Edit" active>
                    <b-form-textarea
                        id="content"
                        v-model.trim="updateComment.content"
                        placeholder="Enter something..."
                        rows="3"
                        max-rows="6"
                        v-html="comment.content"
                    ></b-form-textarea>
                </b-tab>
                <b-tab title="Preview">
                    <b-card-text v-html="marked(comment.content ? updateComment.content : '')"></b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <b-button block variant="outline-primary" @click="update()">Update</b-button>
    </div>
</template>

<script>
import { marked } from '../../utils/markedHelper'
import { mapActions, mapState, mapMutations } from 'vuex'

export default {
    name: 'Editor',
    props: {
        comment: Object
    },
    data() {
        return {
            updateComment: {
                id: this.comment.id,
                content: this.comment.content
            }
        }
    },
    methods: {
        update() {
            this.$store.dispatch('comments/update', this.updateComment)
            this.toggleEdit()
        },
        ...mapMutations({
            toggleEdit: 'comments/toggleEdit'
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