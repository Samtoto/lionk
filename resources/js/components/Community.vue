<template>
    <b-container fluid>
        <b-row class="d-flex justify-content-center">
            <b-col cols="6" md="6" class="px-1" >
                <b-row class="py-1"
                    v-for="card in cards" :key="card.id"
                >
                        <b-card style="width:100%">
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
                </b-row>
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

import { marked } from '../utils/markedHelper'
import { joinCommunityToggle, getAllBlogs } from '../server/api';

export default {
    data() {
        return {
            cards: {},
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
            joinCommunityToggle(community_id).then(response => {
                // TODO if joined show joined button
                // when hover on show leave button
                this.all();
            });
        },
        all: function() {
            getAllBlogs().then(response => {
                // console.log(response.data);
                this.cards = response.data;
            });
        }
    }
}
</script>