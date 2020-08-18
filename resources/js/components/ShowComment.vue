<template>
    <b-container fluid>
        <b-row class="d-flex justify-content-center">
            <b-col cols="6" md="6" class="px-1">
                <b-card style="width:100%">
                    <b-card-title>{{ card.title }} <small style="font-size:60%">• Posted by {{ card.user ? card.user.name : '' }} • {{timeFormatter(card.created_at)}}</small></b-card-title>
                    <b-card-text v-html="card.markdown_content?card.markdown_content:''"></b-card-text>
                    <b-card-img-lazy v-show="card.img_path && !card.deleted_at" :src="card.img_path?card.img_path:''"></b-card-img-lazy>
                    <div v-show="logined()">
                        <b-button size="sm" @click="editBlog" v-show="canEdit()">Edit</b-button>
                        <b-button size="sm" @click="deleteBlog(form.blog_id)" v-show="canDelete()">Delete</b-button>
                        <hr />
                        <p>Comment as {{ logined_user.name }}</p>
                        <Reply :comment_id="null"></Reply>
                        <!-- <b-card no-body>
                            <b-tabs card>
                                <b-tab title="Edit" active>
                                    <b-form-textarea
                                        id="content"
                                        v-model.trim="form.comment"
                                        placeholder="Enter something..."
                                        rows="3"
                                        max-rows="6"
                                    ></b-form-textarea>
                                </b-tab>
                                <b-tab title="Preview">
                                    <b-card-text v-html="marked(form.comment?form.comment:'')"></b-card-text>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                        <b-button block variant="outline-primary">Comment</b-button> -->
                    </div>
                    <div v-show="!logined()">
                        <b-card-text><p style="text-align:left;color: gray"><br />Wanna reply a comment?<br />
                            <b-link to="/login">Login</b-link> or <b-link to="/register">Register</b-link></p>
                        </b-card-text>
                        <!-- <p>login & register</p> -->
                    </div>
                </b-card>
                <CommentsList :blogId="Number(blog_id)"></CommentsList>
            </b-col>
            <b-col md="3" class="px-5">
                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>About community</b-card-title>
                        <b-card-text><p>Some text</p></b-card-text>
                    </b-card>
                </b-row>
                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>Community rules</b-card-title>
                        <b-card-text><p>Some text</p></b-card-text>
                    </b-card>
                </b-row>
            </b-col>
        </b-row>
        
    </b-container>

</template>

<script>
import { marked } from '../utils/markedHelper'
import { timeFormatter } from '../utils/helpers';
import { showComment, deleteBlog } from '../server/api';

import CommentsList from './Comments/List'
import Reply from './Comments/Reply'

import store from '../store'
import { mapState } from 'vuex';

export default {
    data() {
        return {
            user_id: document.getElementById('user').value,
            card: {
                user: {
                    id: '',
                    name: '',
                },
                title: '',
                content: '',
                img_path: '',
                created_at: '',
                deleted_at: '',
            },
            treeData: {},
            form: {
                comment: '',
                blog_id: null,
                commet_id: null,
            },
            deleteConfirm: '',
            marked: marked
        }
    },
    props: {
        blog_id: Number
    },
    store,
    mounted() {
        // console.log(this.user_id)
        this.form.blog_id = this.blog_id;
        showComment(this.blog_id).then(response => {
            // console.log(response.data);
            this.card = response.data;
            this.treeData = this.card.comment
            this.blogOwner = this.card.user
            // console.log(this.treeData)
            // console.error(this.card.user.name)
            // this.cards = response.data;
        });

    },
    computed: {
        ...mapState({
            logined_user: state => state.user.profile
        })
    },
    components: { CommentsList, Reply },
    methods: {
        timeFormatter: timeFormatter,
        editBlog: function() {
            window.location.href = '/blog/' + this.form.blog_id + '/edit';
        },
        logined: function() {
            return Object.keys(this.logined_user).length > 0
        },
        canEdit: function() {
            return this.canDelete(); // same as canDelete()
        },
        canDelete: function() {
            if (this.logined_user.id == this.card.user.id) {
                return true;
            }
            return false;
        }
    },
    created () {
        this.$store.dispatch('user/INIT')
    },
    destoryed () {
    },
}

</script>