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
                        label="Community" 
                        label-cols-sm="2"
                        label-cols-lg="2">
                        <b-form-select v-model="form.community" :options="options">
                            <template v-slot:first>
                            <b-form-select-option :value="null" disabled>Please select an option</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
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
                        <b-form-textarea
                            id="content"
                            v-model.trim="form.content"
                            placeholder="Enter something..."
                            rows="3"
                            max-rows="6"
                            ></b-form-textarea>
                        <div v-if="errors && errors.content" class="text-danger">{{ errors.content[0] }}</div>
                        <!-- <BaseRichText /> -->
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
            })
        },

        data() {
            return {
                options: [],
                form: {
                    title: '',
                    content: '',
                    community: '',
                },
                dismissSecs: 5,
                dismissCountDown: 0,
                errors: {}
            }
        },

        methods: {
            // success() {
            //     console.log('test when successed');
            // },
            onSubmit(evt) {
                console.log(this.form)
                axios.post('/add', this.form).then(response => {
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
            
            showAlert() {
                this.dismissCountDown = this.dismissSecs
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
        }

    }
</script>
