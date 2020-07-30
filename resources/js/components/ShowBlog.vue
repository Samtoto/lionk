<template>
    <b-container fluid>
        <b-row  align-h="center">

            <b-col cols="1" md="9" class="py-3" 
                v-for="card in cards" :key="card.id"
            >
                <div>
                    <b-card>
                        <b-card-title>
                            <small style="font-size:60%">{{ card.community.name ? card.community.name : '' }} • Posted by {{card.user.name}} {{card.created_at}} 
                            <b-button
                                variant="primary"
                                @click="joinCommunity(card.community.id)"
                                v-if="card.community.user.length==0"
                            > Join </b-button>
                            </small></b-card-title>
                        <b-card-title>{{ card.title}} </b-card-title>
                        <b-card-text v-html="marked(card.content)"></b-card-text>
                        <!-- <b-link :to="{ path: 'reply', query: { blog_id: card.id } }" class="btn btn-primary">reply</b-link> -->
                        <b-button @click="reply(card.id)"  variant="primary"> {{ card.comment_count }} comments</b-button>
                    </b-card>
                </div>
                
            </b-col>

            
        </b-row>
        
    </b-container>
</template>

<script>

import marked from 'marked';
import hljs from 'highlight.js';
import "highlight.js/styles/tomorrow-night.css";


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
        }
    }
}
</script>