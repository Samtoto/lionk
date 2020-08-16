<template>
    <ul>
        <!-- <li v-for="comment in comments" :key="comment.id">
            id:{{comment.id}} -- {{ comment.user ? comment.user.name : '' }} {{ comment.content }}
            <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li> -->

        <li v-for="comment in comments" :key="comment.id">
            <b-card border-variant="light">
            <keep-alive>
                <b-card-text v-html="comment.content"></b-card-text>
                <!-- <EditComment @toggleEdit="toggleEdit" v-if="isEdit" :comment="comment" :user_id="user_id"></EditComment> -->
            </keep-alive>
            <b-card-text>
                <small>
                    <b-button size="sm">id: {{ comment.id }}</b-button>
                    <b-button size="sm">{{ comment.user ? comment.user.name : '' }}</b-button>
                    <b-button size="sm">parent_id: {{ comment.parent_id ? comment.parent_id : 'null' }}</b-button>
                    <b-button size="sm" @click="toggle" variant="primary" v-show="logined()">reply</b-button>
                    <b-button size="sm" @click="toggleEdit" variant="warning" v-show="logined() &&logined_user.id==comment.user.id">Edit</b-button>
                    <b-button size="sm" variant="danger" v-show="logined() &&logined_user.id==comment.user.id">Delete</b-button>
                    <b-button size="sm" variant="primary" v-show="!logined()" to="/login">Login</b-button>
                    <b-button size="sm" variant="primary" v-show="!logined()" to="/register">Register</b-button>

                    <!-- <Reply @toggle="toggle" v-show="isOpen" :parent_id="comment.id"> </Reply> -->
                </small>
            </b-card-text>
            </b-card>
                <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li>
    </ul>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
    name: 'CommentsTree',
    props: {
        comments: Array
    },
    data() {
        return {}
    },
    computed: {
        ...mapState({
            logined_user: state => state.user.profile
        })
    },
    methods: {
        toggle(e) {
            console.log('reply toggle')
        },
        logined() {
            return Object.keys(this.logined_user).length > 0;
        },
        toggleEdit() {
            console.log('editor toggle')
        }
    },
    created() {
    }
}
</script>

<style scoped>
ul {
    list-style: none;
    background:#fff
}
</style>