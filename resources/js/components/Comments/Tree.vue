<template>
    <ul>
        <!-- <li v-for="comment in comments" :key="comment.id">
            id:{{comment.id}} -- {{ comment.user ? comment.user.name : '' }} {{ comment.content }}
            <CommentsTree :comments="comment.all_children"></CommentsTree>
        </li> -->

        <li v-for="comment in comments" :key="comment.id">
            <b-card border-variant="light">
            <keep-alive>
            <b-card-text v-html="comment.content">
            </b-card-text>
            <!-- <EditComment @toggleEdit="toggleEdit" v-if="isEdit" :comment="comment" :user_id="user_id"></EditComment> -->
            </keep-alive>
            <b-card-text>
                <small>
                    <b-button size="sm">id: {{ comment.id }}</b-button>
                    <b-button size="sm">{{ comment.user ? comment.user.name : '' }}</b-button>
                    <b-button size="sm">parent_id: {{ comment.parent_id ? comment.parent_id : 'null' }}</b-button>
                    <b-button size="sm" @click="toggle" variant="primary" v-show="user_id">reply</b-button>
                    <!-- <b-button size="sm" @click="toggleEdit" variant="warning" v-show="user_id==comment.user.id">Edit</b-button> -->
                    <!-- <b-button size="sm" @click="deleteComment(comment.id)" variant="danger" v-show="user_id==comment.user.id">Delete</b-button>
                    <b-button size="sm" variant="primary" v-show="!user_id" to="/login">Login</b-button>
                    <b-button size="sm" variant="primary" v-show="!user_id" to="/register">Register</b-button> -->

                    <!-- <Reply @toggle="toggle" v-show="isOpen" :parent_id="comment.id"> </Reply> -->
                </small>
            </b-card-text>
            </b-card>
            <!-- <ul style="list-style: none;" v-if="comment.all_children"> -->
                <CommentsTree :comments="comment.all_children"></CommentsTree>
            <!-- </ul> -->
        </li>
    </ul>
</template>

<script>
export default {
    name: 'CommentsTree',
    props: {
        comments: Array
    },
    data() {
        return {
            user_id: 1
        }
    },
    computed: {
    },
    methods: {
        toggle(e) {
            console.log('reply toggle')
        }
    }
}
</script>

<style scoped>
ul {
    list-style: none;
    background:#fff
}
</style>