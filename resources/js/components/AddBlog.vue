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
                        <b-form-select v-model="form.community" :options="options">
                            <template v-slot:first>
                            <b-form-select-option :value="null" disabled>Please select an option</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-row>
                
                <b-card no-body>
                    
                    <b-tabs pills card>
                        <b-tab title="Post" active>
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
                                    <b-button block variant="outline-primary" type="submit">submit</b-button>
                                </b-form-group>
                            </b-form>
                        </b-tab>
                        <b-tab title="Image">
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
                                    <b-button block variant="outline-primary" type="submit">submit</b-button>
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
    import hljs from 'highlight.js';
    import "highlight.js/styles/tomorrow-night.css";

    import { BIconCardImage, BIconCardText } from 'bootstrap-vue'



    // init marked
    marked.setOptions({
        renderer: new marked.Renderer(),
        highlight: function(code, language) {
            console.log(code, language)
            // const hljs = require('highlight.js');
            const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
            console.log(hljs.highlight(validLanguage, code).value);
            return hljs.highlight(validLanguage, code).value;
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
            axios.get('/community/my').then(response => {
                // console.log(response.data)
                let options = [];
                for (const item of response.data) {
                    // console.log(item)
                    options.push({value: item.id, text: item.name});
                }
                // console.log(options)

                this.options = options;
            });
            
            this.marked = marked
        },

        data() {
            return {
                options: [],
                form: {
                    title: '',
                    content: '',
                    community: '',
                    title_img: '',
                    image: null,
                },
                dismissSecs: 5,
                dismissCountDown: 0,
                errors: {},
                marked: function(){}
            }
        },

        methods: {
            // success() {
            //     console.log('test when successed');
            // },
            /**
             * submit at post tab
             */
            onSubmit(evt) {
                console.log(this.form)
                axios.post('/blog/add', this.form).then(response => {
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
                formData.append('community', this.form.community);
                formData.append('image', this.form.image);

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
            
            showAlert() {
                this.dismissCountDown = this.dismissSecs
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
        }

    }
</script>
