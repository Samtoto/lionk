<template>

    <CommentsTree :comments="comments"></CommentsTree>

</template>

<script>

import store from '../../store'
import { mapState } from 'vuex'
import CommentsTree from './Tree'

export default {
    name: 'CommentsList',
    store,
    props: {
        blogId: Number
    },
    computed: mapState({
        comments: state => state.comments.all
    }),
    components: { CommentsTree },
    created() {
        this.$store.dispatch('comments/INIT', this.blogId)
        this.$store.dispatch('user/INIT')
    },
    methods: {
        showComments () {
            console.log(this.$store.state)
        }
    }
}
</script>

<style>

</style>