<template>

    <b-container fluid>
        <b-row  align-h="center">

            <b-col cols="1" md="9" class="py-3">
                <div>
                    <b-card>
                        <!-- prevent error alike https://stackoverflow.com/questions/52751705/vue-warning-when-accessing-nested-object -->
                        <b-card-title>{{ card.title }} <small>Posted by {{ card.user ? card.user.name : '' }} {{card.created_at}}</small></b-card-title>
                        <b-card-text>{{ card.content}}</b-card-text>
                        <!-- <b-button>reply</b-button> -->
                        <!-- <textarea></textarea> -->

                        <p>Comment as sam</p>
                        <b-form-textarea
                            id="content"
                            placeholder="Enter ur comment..."
                            rows="3"
                            max-rows="6"
                            v-model = "form.comment"
                        ></b-form-textarea>
                        
                    <b-button block variant="outline-primary" @click="reply">reply</b-button>
                    </b-card>
                    
                    <ul style="list-style: none;">
                        <tree-item v-for="(comment, index) in treeData" :key="index" :comment="comment"></tree-item>
                    </ul>
                </div>
                
            </b-col>

            
        </b-row>
        
    </b-container>

</template>

<script>
Vue.component('tree-item', {
    template: `
    <li style="border-left:5px solid gray">
        <b-card>
        <b-card-text>
            <small>user: {{ comment.user ? comment.user.name : '' }}</small> <br />
            id: {{ comment.id }} <br/>
            content: {{ comment.content }} <br />
            
            parent_id:{{ comment.parent_id ? comment.parent_id : 'null' }} <br />
            reply button: <b-button>reply</b-button> <br />
        </b-card-text>
        </b-card>
        <ul style="list-style: none;" v-if="comment.all_children.length">
            <tree-item v-for="(comment, index) in comment.all_children" :key="index" :comment="comment">
            </tree-item>
        </ul>
    </li>`,
    props: {
        comment: Object
    },
})

export default {
    data() {
        return {
            card: {},
            treeData: {},
            form: {
                comment: null,
                blog_id: null,
                commet_id: null,
            }
        }
    },
    mounted() {
        let blog_id = document.querySelector('#blog_id').value;
        this.form.blog_id = blog_id;
        console.log(this.form)
        axios.get('/comment/show/' + blog_id).then(response => {
            console.log(response.data);
            this.card = response.data;
            this.treeData = this.card.comment
            this.blogOwner = this.card.user
            console.log(this.treeData)
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
        }
    }
}

</script>