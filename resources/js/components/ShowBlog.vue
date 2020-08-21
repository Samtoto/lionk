<template>
    <b-container fluid>
        <b-row class="d-flex justify-content-center">
            
            <b-col cols="6" md="6" class="px-1">
                <b-row class="pb-3 pt-1">
                    <b-input-group size="lg">
                        <b-form-input @click="add"></b-form-input>
                        <b-input-group-append>
                            <b-button  variant="outline-secondary" to="/blog/create#image"><b-icon icon="card-image" ></b-icon></b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-row>
                <BlogList></BlogList>
            </b-col>

            <b-col md="3" class="px-5">
                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>Top communities</b-card-title>
                        <b-card-text><p>This is a list of communities</p></b-card-text>
                    </b-card>
                    
                </b-row>
                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>Treading communities</b-card-title>
                        <b-card-text><p>This is a list of communities</p></b-card-text>
                    </b-card>
                    
                </b-row>

                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>Home</b-card-title>
                        <b-card-text><p>Post & Create community</p></b-card-text>
                    </b-card>
                    
                </b-row>

                <b-row class="py-1">
                    <b-card style="width:100%">
                        <b-card-title>Top or recent blogs</b-card-title>
                        <b-card-text><p>List of blogs</p></b-card-text>
                    </b-card>
                    
                </b-row>
            </b-col>

        </b-row>
        
    </b-container>
</template>

<script>


import { BIconCardImage } from 'bootstrap-vue'

import { joinCommunityToggle } from '../server/api'

import store from '../store'
import BlogList from './Blogs/List'

export default {
    store,
    data() {
        return {}
    },
    mounted() {
    },
    components: { BlogList },
    methods: {
        add: function() {
            window.location.href = "/blog/create";
        }
    }
}
</script>