<template>
    <b-container fluid>
        <b-row class="d-flex justify-content-center">
            
            <b-col cols="6" md="6" class="px-1">
                <b-row class="pb-3 pt-1">
                    <b-input-group size="lg">
                        <b-form-input @click="add"></b-form-input>
                        <b-input-group-append>
                            <b-button  variant="outline-secondary"><b-icon icon="card-image" ></b-icon></b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-row>
                <b-row class="py-2" 
                    v-for="card in cards" :key="card.id"
                >
                    <b-card style="width:100%" no-body>
                        <b-card-header
                            header-bg-variant="transparent"
                        >
                            <small style="font-size:60%">
                                {{ card.community.name ? card.community.name : '' }} • Posted by {{card.user.name}} • {{timeFormatter(card.created_at)}} 
                                <b-button
                                    variant="primary"
                                    @click="joinCommunity(card.community.id)"
                                    v-if="card.community.user.length==0"
                                    size="sm"
                                > Join </b-button>
                            </small>
                        </b-card-header>
                        <b-card-body>
                            <b-card-title>{{ card.title}} </b-card-title>
                            <b-card-img-lazy v-show="card.img_path" :src="card.img_path?card.img_path:''"></b-card-img-lazy>
                            <b-card-text v-html="marked(card.content?card.content:'')"></b-card-text>
                        </b-card-body>
                        <!-- <b-link :to="{ path: 'reply', query: { blog_id: card.id } }" class="btn btn-primary">reply</b-link> -->
                        <b-card-footer
                            
                        >
                            <b-button @click="reply(card.id)" size="sm" variant="primary"> {{ card.comment_count }} comment{{(card.comment_count<=1)?'': 's'}}</b-button>
                        </b-card-footer>
                    </b-card>
                </b-row>
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

import marked from 'marked';
import hljs from 'highlight.js';
import "highlight.js/styles/tomorrow-night.css";

import { BIconCardImage } from 'bootstrap-vue'

// init marked
marked.setOptions({
    renderer: new marked.Renderer(),
    highlight: function(code, language) {
        // console.log(code, language)
        // const hljs = require('highlight.js');
        const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
        // console.log(hljs.highlight(validLanguage, code).value);
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
    data() {
        return {
            cards: {

            },
            marked: marked
        }
    },
    mounted() {
        console.log('mounted');
        this.all();

    },
    methods: {
        reply: function(id) {
            window.location.href="/blog/show/" + id
        },
        joinCommunity: function(community_id) {
            axios.get('/community/change_status/'+community_id).then(response => {
                // TODO if joined show joined button
                // when hover on show leave button
                this.all();
            });
        },
        all: function() {
            axios.get('/blog/all').then(response => {
                console.log(response.data);
                this.cards = response.data;
            });
        },
        add: function() {
            window.location.href = "/blog/add";
        },
        timeFormatter(date) {
            let d = new Date(date)
            let now = new Date()
            // document.write(Math.floor((now - d) / 1000))
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

        }
    }
}
</script>