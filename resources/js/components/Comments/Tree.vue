<template>
    <ul>
        <!-- <li v-for="comment in comments" :key="comment.id">
            id:{{comment.id}} -- {{ comment.user ? comment.user.name : '' }} {{ comment.content }}
            <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li> -->

        <li v-for="comment in getCommentsByParentId(parent_id)" :key="comment.id">
            <b-card border-variant="light">
                <keep-alive>
                    <!-- content -->
                    <b-card-text v-html="comment.markdown_content"></b-card-text>
                    <!-- edit editor -->
                    <EditComment v-if="editing === comment.id" :comment="comment"></EditComment>
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
                        <b-button size="sm" @click="toggleEdit(comment.id)" variant="warning" v-show="canEdit(comment)">Edit</b-button>
                        <!-- Delete button -->
                        <b-button size="sm" variant="danger" @click="deleteComment(comment.id)" v-show="canDelete(comment)">Delete</b-button>
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
                <CommentsTree :parent_id="comment.id"></CommentsTree>
        </li>
    </ul>
</template>

<script>
import { mapActions, mapState, mapMutations, mapGetters } from 'vuex'
import Reply from './Reply'
import EditComment from './Edit'

export default {
    name: 'CommentsTree',
    props: {
        // comments: Array,
        parent_id: Number
    },
    data() {
        return {}
    },
    components: { Reply, EditComment },
    computed: {
        ...mapState({
            logined_user: state => state.user.profile,
            editing: state => state.comments.editing,
            replying: state => state.comments.replying
        }),
        ...mapGetters({
            getCommentsByParentId: 'comments/getByParentId'
        })
    },
    methods: {
        logined() {
            return Object.keys(this.logined_user).length > 0;
        },
        canEdit(comment) {
            // logined and
            // logined user is the owner and
            // not deleted
            return this.logined() && comment.user && this.logined_user.id==comment.user.id && !comment.deleted_at
        },
        canDelete(comment) {
            // same as canEdit
            return this.logined() && comment.user && this.logined_user.id==comment.user.id && !comment.deleted_at
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
            toggleReply: 'comments/toggleReply',
            toggleEdit: 'comments/toggleEdit',
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