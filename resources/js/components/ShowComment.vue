<template>
    <b-container fluid>
        <b-row class="d-flex justify-content-center">
            <b-col cols="6" md="6" class="px-1">
                <b-card style="width:100%">
                    <!-- prevent error alike https://stackoverflow.com/questions/52751705/vue-warning-when-accessing-nested-object -->
                    <b-card-title>{{ card.title }} <small style="font-size:60%">• Posted by {{ card.user ? card.user.name : '' }} • {{timeFormatter(card.created_at)}}</small></b-card-title>
                    <b-card-text v-html="card.content?card.content:''"></b-card-text>
                    <b-card-img-lazy v-show="card.img_path?card.img_path:''" :src="card.img_path?card.img_path:''"></b-card-img-lazy>
                    <!-- <b-button>reply</b-button> -->
                    <!-- <textarea></textarea> -->
                    <div v-show="user_id">
                        <b-button size="sm" @click="editBlog" v-show="card.user.id == user_id">Edit</b-button>
                        <b-button size="sm" @click="deleteBlog(form.blog_id)" v-show="card.user.id == user_id">Delete</b-button>
                        <hr />
                        <p>Comment as ???</p>
                        <b-card no-body>
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
                        <b-button block variant="outline-primary" @click="reply">Comment</b-button>
                    </div>
                    <div v-show="!user_id">
                        <b-card-text><p style="text-align:left;color: gray"><br />Wanna reply a comment?<br />
                            <b-link to="/login">Login</b-link> or <b-link to="/register">Register</b-link></p>
                        </b-card-text>
                        <!-- <p>login & register</p> -->
                    </div>
                </b-card>

                <ul style="list-style: none; background:#fff">
                    <tree-item v-for="(comment, index) in treeData" :key="index" :comment="comment" :user_id="user_id"></tree-item>
                </ul>
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
import marked from 'marked';
// import hljs from 'highlight.js';
// import "highlight.js/styles/tomorrow-night.css";


// init marked
marked.setOptions({
    renderer: new marked.Renderer(),
    highlight: function(code, language) {
        // console.log(code, language)
        // const hljs = require('highlight.js');
        // const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
        // console.log(hljs.highlight(validLanguage, code).value);
        // return hljs.highlight(validLanguage, code).value;
    },
    pedantic: false,
    gfm: true,
    breaks: true,
    sanitize: false,
    smartLists: true,
    smartypants: false,
    xhtml: true
})

const bus = new Vue({});
var Reply = Vue.component('Reply', {
    template: `<b-form>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="Edit" active>
                    <b-form-textarea
                    v-model.trim="content"
                    placeholder="Enter something..."
                    rows="3"
                    max-rows="6"
                    ></b-form-textarea>
                </b-tab>
                <b-tab title="Preview">
                    <b-card-text v-html="marked(content)"></b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <b-button variant="outline-info" @click="submit" size="sm">Comment</b-button>
        </b-form>`,
    methods: {
        submit: function () {
            // console.log('submit Reply', this.content, this.parent_id)
            let subComment = {
                comment_id: this.parent_id,
                blog_id: document.querySelector('#blog_id').value,
                comment: this.content
            }
            let newComment = {}
            axios.post('/comment/add_sub', subComment).then(response => {
                // console.log(response.data);
                newComment = response.data
                bus.$emit("comment-bus", newComment)
                this.content = ''
                this.$emit('toggle')
            })

            
        }
    },
    mounted () {},
    data () {
        return {
            content: '',
            marked: marked
        }
    },
    props: {
        parent_id: Number
    }
})

Vue.component('EditComment', {
    template: `<b-form>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="Edit" active>
                    <b-form-textarea
                    v-model.trim="editComment.content"
                    placeholder="Enter something..."
                    rows="3"
                    max-rows="6"
                    ></b-form-textarea>
                </b-tab>
                <b-tab title="Preview">
                    <b-card-text v-html="marked(editComment.content)"></b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <b-button variant="outline-info" @click="submit" size="sm">Update Comment</b-button>
    </b-form>`,
    props: {
        comment: Object
    },
    activated() {
        axios.get('/comment/'+ this.comment.id +'/edit').then(response => {
            this.editComment.content = response.data.content
            this.editComment.comment_id = response.data.id
            // console.log(response.data)
        })
    },
    data() {
        return {
            editComment: {
                comment_id: '',
                content: '',
            },
            marked: marked
        }
    },
    methods: {
        submit: function() {
            let formData = this.editComment;
            formData._method = 'put';
            axios.put('/comment/'+this.editComment.comment_id, formData).then(response => {
                let updatedComment = response.data
                bus.$emit("comment-update-bus", updatedComment)
                this.content = ''
                this.$emit('toggleEdit')
            })
        }
    }
})

Vue.component('tree-item', {
    template: `
    <li style="">
        <b-card border-variant="light">
        <keep-alive>
        <b-card-text v-html="comment.content" v-if="!isEdit">
        </b-card-text>
        <EditComment @toggleEdit="toggleEdit" v-if="isEdit" :comment="comment" :user_id="user_id"></EditComment>
        </keep-alive>
        <b-card-text>
            <small>
                <b-button size="sm">id: {{ comment.id }}</b-button>
                <b-button size="sm">{{ comment.user ? comment.user.name : '' }}</b-button>
                <b-button size="sm">parent_id: {{ comment.parent_id ? comment.parent_id : 'null' }}</b-button>
                <b-button size="sm" @click="toggle" variant="primary" v-show="user_id">reply</b-button>
                <b-button size="sm" @click="toggleEdit" variant="warning" v-show="user_id==comment.user.id">Edit</b-button>
                <b-button size="sm" @click="deleteComment(comment.id)" variant="danger" v-show="user_id==comment.user.id">Delete</b-button>
                <b-button size="sm" variant="primary" v-show="!user_id" to="/login">Login</b-button>
                <b-button size="sm" variant="primary" v-show="!user_id" to="/register">Register</b-button>

                <Reply @toggle="toggle" v-show="isOpen" :parent_id="comment.id"> </Reply>
            </small>
        </b-card-text>
        </b-card>
        <ul style="list-style: none;" v-if="comment.all_children">
            <tree-item v-for="(comment, index) in comment.all_children" :key="index" :comment="comment" :user_id="user_id" @reply-comment="$emit('reply-comment', comment, content)">
            </tree-item>
        </ul>
    </li>`,
    props: {
        comment: Object,
        user_id: String,
    },
    data: function () {
        return {
            isOpen: false,
            content: '',
            marked: marked,
            isEdit: false
        }
    },
    methods: {
        toggle: function() {
            this.isOpen = !this.isOpen;
            
        },
        toggleEdit: function() {
            this.isEdit = !this.isEdit;
        },
        deleteComment: function(comment_id) {
            
            // this.deleteConfirm = ''
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
                // this.deleteConfirm = value
                if (value) {
                    let formData = new FormData()
                    formData.append('comment_id', comment_id)
                    formData.append('_method', 'delete')
                    axios.delete('/comment/'+comment_id, formData).then(response => {
                        // console.log(comment_id)
                        bus.$emit('comment-delete-bus', comment_id);
                    })
                }
            })
            .catch(err => {
                // An error occurred
            })
            
        }
    }
})

export default {
    data() {
        return {
            user_id: document.getElementById('user').value,
            card: {},
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
    mounted() {
        // console.log(this.user_id)
        let blog_id = document.querySelector('#blog_id').value;
        this.form.blog_id = blog_id;
        axios.get('/comment/show/' + blog_id).then(response => {
            // console.log(response.data);
            this.card = response.data;
            this.treeData = this.card.comment
            this.blogOwner = this.card.user
            // console.log(this.treeData)
            // console.error(this.card.user.name)
            // this.cards = response.data;
        });

    },
    methods: {
        reply: function(id) {
            // this.form.blog_id ;
            axios.post('/comment/add', this.form).then(response => {
                this.card = response.data;
                this.treeData = this.card.comment
                this.blogOwner = this.card.user
                this.form.comment = null
            });
        },
        addChild(newComment, comments) {
            if (comments.length == 0) {
                return;
            }
            // console.log(comments);
            // return;
            for (var comment of comments) {
                
                if (comment.id == newComment.parent_id) {
                    console.log('found it')
                    if (comment.hasOwnProperty('all_children')) {
                        comment.all_children.push(newComment)
                    } else {
                        comment.all_children = [newComment]
                    }
                    return;
                }
                if (comment.hasOwnProperty('all_children')) {
                    this.addChild(newComment, comment.all_children)
                } else {
                    return;
                }
            }
        },
        addComment: function (newComment) {
            // console.log(this.card.comment)
            this.addChild(newComment, this.card.comment)
            // console.log('submit with busEvent', newComment)
        },
        updateChild: function (updatedComment, comments) {
            if (comments.length == 0) {
                return false;
            }
            for (var comment of comments) {
                if (comment.id === updatedComment.id) {
                    console.log('found it & update it!');
                    // comment = updatedComment // not working
                    comment.content = updatedComment.content
                    return;
                }
                if (comment.hasOwnProperty('all_children')) {
                    this.updateChild(updatedComment, comment.all_children)
                } else {
                    return;
                }
            }
        },
        updateComment: function(updatedComment) {
            this.updateChild(updatedComment, this.card.comment);
        },
        timeFormatter(date) {
            let d = new Date(date)
            let now = new Date()
            let diff = now - d;
            if (diff < 60000) {
                return Math.floor(diff / 1000) + ' secs ago'
            } else if (diff < 3600000) {
                return Math.floor(diff / 60000) + ' mins ago'
            } else if (diff < 3600000 * 24) {
                return Math.floor(diff / 3600000) + ' hours ago'
            } else if (diff < 3600000 * 24 * 30) {
                return Math.floor(diff / 3600000 / 24) + ' days ago'
            } else {
                return d.toLocaleString()
            }

        },
        editBlog: function() {
            window.location.href = '/blog/' + this.form.blog_id + '/edit';
        },
        deleteBlog: function(blog_id) {
            this.deleteConfirm = ''
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete the blog.', {
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
                this.deleteConfirm = value
                if (value) {
                    let formData = new FormData();
                    formData.append('_method', 'delete');
                    formData.append('blog_id', this.form.blog_id);
                    axios.delete('/blog/'+this.form.blog_id, formData).then(response => {
                        location.href = '/';
                    })
                }
            })
            .catch(err => {
                // An error occurred
            })
        },
        deleteComment(comment_id, comments=this.card.comment) {
            // console.log('delete process')
            if ( !comments ) {
                return ;
            }
            for (var comment of comments) {
                if (comment_id === comment.id) {
                    console.log('found it & delete it')
                    // TODO
                    comment.content = 'removed'
                    // comment = [] // not working
                    return ;
                }
                if (comment.hasOwnProperty('all_children')) {
                    this.deleteComment(comment_id, comment.all_children)
                } else {
                    return;
                }
            }
            return ;
        }
    },
    created () {
        bus.$on('comment-bus', this.addComment)
        bus.$on('comment-update-bus', this.updateComment)
        bus.$on('comment-delete-bus', this.deleteComment)
    },
    destoryed () {
        bus.$off('comment-bus', this.addComment)
        bus.$off('comment-update-bus', this.updateComment)
        bus.$off('comment-delete-bus', this.deleteComment)
    },
}

</script>