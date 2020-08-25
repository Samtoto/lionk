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
                    <b-form @submit.prevent="onSubmit" >
                        <b-form-group 
                            label="Name" 
                            label-for="input-horizontal"
                            label-cols-sm="2"
                            label-cols-lg="2"
                        >
                            <b-form-input required type="text" v-model.trim="form.name"/>
                            <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                        </b-form-group>
                        <b-form-group 
                            label="Description" 
                            label-for="input-horizontal"
                            label-cols-sm="2"
                            label-cols-lg="2"
                        >
                            <b-form-textarea required type="text" v-model.trim="form.description">
                            </b-form-textarea>
                            <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>
                        </b-form-group>
                        <b-form-group 
                            label="Theme Color" 
                            label-for="input-horizontal"
                            label-cols-sm="2"
                            label-cols-lg="2"
                        >
                            <b-form-input type="color" v-model.trim="form.theme_color"/>
                            <!-- <div id="theme_color_preview"></div> -->
                            <div v-if="errors && errors.theme_color" class="text-danger">{{ errors.theme_color[0] }}</div>
                        </b-form-group>
                        <b-form-group
                            label="Avatar"
                            label-for="input-horizontal"
                            label-cols-sm="2"
                            label-cols-lg="2"
                        >
                            <b-form-file
                                v-model="form.avatar"
                                :state="Boolean(form.avatar)"
                                placeholder="Choose a file or drop it here..."
                                drop-placeholder="Drop file here..."
                            >
                            </b-form-file>
                            <div v-if="errors && errors.avatar" class="text-danger">{{ errors.avatar[0] }}</div>
                        </b-form-group>
                        <b-form-group
                            label="Banner"
                            label-for="input-horizontal"
                            label-cols-sm="2"
                            label-cols-lg="2"
                        >
                            <b-form-file
                                v-model="form.banner"
                                :state="Boolean(form.banner)"
                                placeholder="Choose a file or drop it here..."
                                drop-placeholder="Drop file here..."
                            >
                            </b-form-file>
                            <div v-if="errors && errors.banner" class="text-danger">{{ errors.banner[0] }}</div>
                        </b-form-group>
                        <b-form-group>
                            <b-button block variant="outline-primary" type="submit">submit</b-button>
                        </b-form-group>
                        
                    </b-form>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>

import {createCommunity} from '../../server/api'

export default {
    data() {
        return {
            form: {
                name: '',
                description: '',
                theme_color: '#3490dc',
                avatar: null,
                banner: null
            },
            dismissSecs: 5,
            dismissCountDown: 0,
            errors: {},
        }
    },
    methods: {
        onSubmit() {
            let formData = new FormData()
            formData.append('name', this.form.name)
            formData.append('description', this.form.description)
            formData.append('theme_color', this.form.theme_color.replace(/#/g, ''))
            this.form.avatar ? formData.append('avatar', this.form.avatar) : false
            this.form.banner ? formData.append('banner', this.form.banner) : false
            // console.log(this.form.theme_color)
            createCommunity(formData).then( res => {
                // console.log(res.data)
                this.resetFormAttr()
                this.showAlert()
            }).catch( error => {
                if (error.response.status === 422) {
                    // console.error('error');
                    this.errors = error.response.data.errors || {};
                    console.error(this.errors);
                }
            })
        },

        resetFormAttr() {
            this.form = {
                name: '',
                description: '',
                theme_color: '#3490dc',
                avatar: null,
                banner: null
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

<style scoped>
</style>