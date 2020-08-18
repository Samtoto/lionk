<template>
    <ul>
        <!-- <li v-for="comment in comments" :key="comment.id">
            id:{{comment.id}} -- {{ comment.user ? comment.user.name : '' }} {{ comment.content }}
            <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li> -->

        <li v-for="comment in comments" :key="comment.id">
            <b-card border-variant="light">
            <keep-alive>
                <!-- content -->
                <b-card-text v-html="comment.markdown_content"></b-card-text>
                <!-- edit editor -->
                <!-- <EditComment v-if="editing === comment.id" :comment="comment"></EditComment> -->
            </keep-alive>
            <b-card-text>
                <small>
                    <!-- Useful info -->
                    <b-button size="sm">id: {{ comment.id }}</b-button>
                    <b-button size="sm">{{ comment.user ? comment.user.name : '' }}</b-button>
                    <b-button size="sm">parent_id: {{ comment.parent_id ? comment.parent_id : 'null' }}</b-button>
                    <!-- Reply button -->
                    <b-button size="sm" @click="toggleReply(comment.id)" variant="primary" v-show="logined()">reply</b-button>
                    <!-- Edit button -->
                    <b-button size="sm" @click="toggleEdit" variant="warning" v-show="logined() && comment.user && logined_user.id==comment.user.id">Edit</b-button>
                    <!-- Delete button -->
                    <b-button size="sm" variant="danger" @click="deleteComment(comment.id)" v-show="logined() && comment.user && logined_user.id==comment.user.id">Delete</b-button>
                    <!-- Not login, login button -->
                    <b-button size="sm" variant="primary" v-show="!logined()" to="/login">Login</b-button>
                    <!-- Not login, register button -->
                    <b-button size="sm" variant="primary" v-show="!logined()" to="/register">Register</b-button>
                    <!-- reply editor -->
                    <Reply v-show="replying === comment.id" :comment_id="comment.id"> </Reply>
                </small>
            </b-card-text>
            </b-card>
                <!-- recursive show comments -->
                <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li>
    </ul>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
import Reply from './Reply'

export default {
    name: 'CommentsTree',
    props: {
        comments: Array
    },
    data() {
        return {}
    },
    components: { Reply },
    computed: {
        ...mapState({
            logined_user: state => state.user.profile,
            editing: state => state.comments.editing,
            replying: state => state.comments.replying
        })
    },
    methods: {
        logined() {
            return Object.keys(this.logined_user).length > 0;
        },
        toggleEdit(comment_id) {
            console.log('editor toggle')
        },
        deleteComment(comment_id) {
            console.log('delete comment id: '+ comment_id)
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete the comment.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if (value) {
                    this.$store.dispatch('comments/delete', comment_id)
                }
            })
            .catch(err => {
                // An error occurred
            })

        },
        ...mapActions({
            
        }),
        ...mapMutations({
            toggleReply: 'comments/toggleReply'
        })
    },
}
</script>

<style scoped>
ul {
    list-style: none;
    background:#fff
}
</style>