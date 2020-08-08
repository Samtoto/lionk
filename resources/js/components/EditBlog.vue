<template>
    <b-container fluid>
        <b-row  align-h="center">
            <b-col cols="1" md="9" class="py-3">
                <b-alert
                :show="dismissCountDown"
                dismissible
                variant="success"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
                >Post successed!</b-alert>
                <b-row cols="3" class="pl-4">
                    <b-form-group
                    label="Community" 
                    label-cols-sm="3"
                    label-cols-lg="3"
                    >
                        <b-input v-model="form.community" readonly></b-input>
                    </b-form-group>
                </b-row>
                
                <b-card no-body>
                    
                    <b-tabs pills card>
                        <b-tab title="Post" v-show="postType('common')">
                            <template v-slot:title>
                                <b-icon icon="card-text"></b-icon> Post
                            </template>
                            <b-form @submit.prevent="onSubmit" >
                                <b-form-group 
                                    label="Title" 
                                    label-for="input-horizontal"
                                    label-cols-sm="2"
                                    label-cols-lg="2"
                                >
                                    <b-form-input id="title" type="text" v-model.trim="form.title"/>
                                    <div v-if="errors && errors.title" class="text-danger">{{ errors.title[0] }}</div>
                                </b-form-group>
                                <b-form-group
                                    label="Content" 
                                    label-for="input-horizontal"
                                    label-cols-sm="2"
                                    label-cols-lg="2"
                                >
                                <b-card no-body>
                                    <b-tabs card>
                                    <b-tab title="Edit" active>
                                        <b-form-textarea
                                        id="content"
                                        v-model.trim="form.content"
                                        placeholder="Enter something..."
                                        rows="3"
                                        max-rows="6"
                                        ></b-form-textarea>
                                    </b-tab>
                                    <b-tab title="Preview">
                                        <b-card-text v-html="marked(form.content?form.content:'')"></b-card-text>
                                    </b-tab>
                                    </b-tabs>
                                </b-card>
                                    
                                    <div v-if="errors && errors.content" class="text-danger">{{ errors.content[0] }}</div>
                                    <!-- <BaseRichText /> -->
                                </b-form-group>
                                <b-form-group>
                                    <b-button block variant="outline-primary" type="submit">Update</b-button>
                                </b-form-group>
                            </b-form>
                        </b-tab>
                        <b-tab title="Image" v-if="postType('image')">
                            <template v-slot:title>
                                <b-icon icon="card-image" ></b-icon> Image
                            </template>
                            <b-form @submit.prevent="onImageSubmit" >
                                <b-form-group 
                                    label="Title" 
                                    label-for="input-horizontal"
                                    label-cols-sm="2"
                                    label-cols-lg="2"
                                >
                                    <b-form-input type="text" v-model.trim="form.title_img"/>
                                    <div v-if="errors && errors.title_img" class="text-danger">{{ errors.title_img[0] }}</div>
                                </b-form-group>
                                <b-form-group
                                    label="Image"
                                    label-for="input-horizontal"
                                    label-cols-sm="2"
                                    label-cols-lg="2"
                                >
                                    <b-form-file
                                        v-model="form.image"
                                        :state="Boolean(form.image)"
                                        placeholder="Choose a file or drop it here..."
                                        drop-placeholder="Drop file here..."
                                    >
                                    </b-form-file>
                                    <div v-if="errors && errors.image" class="text-danger">{{ errors.image[0] }}</div>
                                </b-form-group>

                                <b-form-group>
                                    <b-button block variant="outline-primary" type="submit">Update</b-button>
                                </b-form-group>
                            </b-form>
                        </b-tab>
                    </b-tabs>
                </b-card>
                
            </b-col>
        </b-row>
        
    </b-container>
</template>
<script>
import marked from 'marked';
// import hljs from 'highlight.js';
// import "highlight.js/styles/tomorrow-night.css";

import { BIconCardImage, BIconCardText } from 'bootstrap-vue'

// init marked
marked.setOptions({
    renderer: new marked.Renderer(),
    highlight: function(code, language) {
        // const hljs = require('highlight.js');
        // const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
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


export default {
    
    mounted() {
        console.log(this.blog);
        this.marked = marked
    },
    props:{
        blog: Object
    },
    data() {
        return {
            options: [],
            form: {
                id: this.blog.id,
                title: this.blog.img_path?'':this.blog.title,
                content: this.blog.content,
                community_id: this.blog.community_id,
                community: this.blog.community.name,
                title_img: this.blog.img_path?this.blog.title: '',
                image: this.blog.img_path,
            },
            // blog: blog,
            dismissSecs: 5,
            dismissCountDown: 0,
            errors: {},
            marked: marked
        }
    },

    methods: {
        onSubmit(evt) {
            // console.log(this.form)
            this.form._method = 'put';
            axios.put('/blog/'+this.form.id, this.form).then(response => {
                console.log(response.data);
                this.form = {};
                this.showAlert();
                // this.success();
            }).catch(error => {
                if (error.response.status === 422) {
                    // console.error('error');
                    this.errors = error.response.data.errors || {};
                    console.error(this.errors);
                }
            })
        },
        /**
         * submit at image tab
         */
        onImageSubmit(evt) {
            this.form.title = '';
            this.form.content = '';
            let headers = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }
            let formData = new FormData();
            formData.append('title_img', this.form.title_img);
            formData.append('community_id', this.form.community_id);
            formData.append('image', this.form.image);
            formData.append('_method', 'put');


            console.log(formData)
            axios.post('/blog/addImg', formData, headers).then(response => {
                console.log(response.data);
                this.form = {};
                this.showAlert();
                this.errors = {}
                // this.success();
            }).catch(error => {
                if (error.response.status === 422) {
                    // console.error('error');
                    this.errors = error.response.data.errors || {};
                    console.error(this.errors);
                }
            })
        },
        postType(t) {
            if (t === 'common') {
                if (this.img_path === undefined) {
                    return true;
                } else {
                    if (this.img_path.length == 0) {
                        return true;
                    }
                }
                return false;
            } else if (t === 'image') {
                return this.img_path !== undefined && this.img_path.length > 0;
            }
        },

        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
    }

}
</script>
