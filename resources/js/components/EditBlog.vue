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
                        <b-tab title="Post" v-if="postType('common')">
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
                                <b-card-img-lazy :src="form.image_url"></b-card-img-lazy>
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
import { marked } from '../utils/markedHelper'

import { BIconCardImage, BIconCardText } from 'bootstrap-vue'
import { updateCommonBlog, updateImageBlog } from '../server/api'

export default {
    
    mounted() {
        // console.log(this.blog);
        this.marked = marked
    },
    props:{
        blog: Object
    },
    data() {
        return {
            options: [],
            form: {
                blog_id: this.blog.id,
                title: this.blog.img_path?'':this.blog.title,
                content: this.blog.content,
                community_id: this.blog.community_id,
                community: this.blog.community.name,
                title_img: this.blog.img_path?this.blog.title: '',
                image_url: this.blog.img_path,
                image: null,
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
            updateCommonBlog(this.form.blog_id, this.form).then(response => {
                // console.log(response.data);
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
            formData.append('image', this.form.image);
            formData.append('_method', 'put');

            updateImageBlog(this.form.blog_id, formData).then(response => {
                console.log(response.data)
            })

            // // console.log(formData)
            // axios.post('/blog/'+this.form.blog_id, formData, headers).then(response => {
            //     console.log(response.data);
            //     this.form = {};
            //     this.showAlert();
            //     this.errors = {}
            //     // this.success();
            // }).catch(error => {
            //     if (error.response.status === 422) {
            //         // console.error('error');
            //         this.errors = error.response.data.errors || {};
            //         console.error(this.errors);
            //     }
            // })
        },
        postType(t) {
            if (t === 'common') {
                return ! Boolean(this.form.image_url);
            } else if (t === 'image') {
                return Boolean(this.form.image_url);
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
