<template>
<div>
    <b-row class="py-2" 
        v-for="card in cards" :key="card.id"
    >
        <b-card style="width:100%" no-body>
            <b-card-header
                header-bg-variant="transparent"
            >
                <small style="font-size:60%">
                    {{ card.community.name ? card.community.name : '' }} • Posted by {{card.user.name}} • {{timeFormatter(card.created_at)}} 
                    <JoinButton :communityId="card.community.id"></JoinButton>
                </small>
            </b-card-header>
            <b-card-body>
                <b-card-title>{{ card.title}} </b-card-title>
                <b-card-img-lazy v-show="card.img_path && !card.deleted_at" :src="card.img_path?card.img_path:''"></b-card-img-lazy>
                <b-card-text v-html="card.markdown_content?card.markdown_content:''"></b-card-text>
            </b-card-body>
            <!-- <b-link :to="{ path: 'reply', query: { blog_id: card.id } }" class="btn btn-primary">reply</b-link> -->
            <b-card-footer
                
            >
                <b-button @click="reply(card.id)" size="sm" variant="primary"> {{ card.comment_count }} comment{{(card.comment_count<=1)?'': 's'}}</b-button>
            </b-card-footer>
        </b-card>
    </b-row>
</div>
</template>

<script>

import { mapState, mapActions, mapGetters } from 'vuex'

import JoinButton from '../Communities/JoinStatusButton';

import { timeFormatter } from '../../utils/helpers'
import store from '../../store'
export default {
    data() {
        return {
            timeFormatter: timeFormatter
        }
    },
    store,
    computed: mapState({
        cards: state => state.blogs.all
    }),
    components: { JoinButton },
    methods: {
        // ...mapActions([
        //     'init',
        //     'joinCommunity'
        // ]),
        // ...mapGetters([
        //     'getByCommunityId'
        // ]),
        reply: function(id) {
            window.location.href = "/blog/" + id
        }
        
    },
    created() {
        this.$store.dispatch('blogs/INIT')
        this.$store.dispatch('communities/INIT')
    }
}

</script>