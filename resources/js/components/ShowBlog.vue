<template>
    <b-container fluid>
        <b-row  align-h="center">

            <b-col cols="1" md="9" class="py-3" 
                v-for="card in cards" :key="card.id"
            >
                <div>
                    <b-card>
                        <b-card-title>{{ card.title}} <small style="font-size:60%">Posted by {{card.user.name}} {{card.created_at}} | Community:</small></b-card-title>
                        <b-card-text>{{ card.content}}</b-card-text>
                        <!-- <b-link :to="{ path: 'reply', query: { blog_id: card.id } }" class="btn btn-primary">reply</b-link> -->
                        <b-button @click="reply(card.id)"  variant="primary"> {{ card.comment_count }} comments</b-button>
                    </b-card>
                </div>
                
            </b-col>

            
        </b-row>
        
    </b-container>
</template>

<script>
export default {
    data() {
        return {
            cards: {

            }
        }
    },
    mounted() {
        console.log('mounted');
        axios.get('/showBlogs').then(response => {
            console.log(response.data);
            this.cards = response.data;
        });

    },
    methods: {
        reply: function(id) {
            window.location.href="/reply/" + id
        }
    }
}
</script>