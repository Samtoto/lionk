<template>
<b-container fluid>
        <b-row  align-h="center">

            <b-col cols="1" md="9" class="py-3" 
                v-for="card in cards" :key="card.id"
            >
                <div>
                    <b-card>
                        <b-card-title>{{ card.name}} </b-card-title>
                        
                        <b-button @click="join(card.id)"  :variant=" card.joined ? 'success': 'primary' ">{{ card.joined ? 'Joined': 'Join'}}</b-button>
                        
                    </b-card>
                </div>
                
            </b-col>

            
        </b-row>
        
    </b-container>
</template>

<script>
export default {
    mounted: function() {
        axios.get('/communities/all').then(response => {
            console.log(response.data);
            this.cards = response.data;
        })
    },
    data: function() {
        return {
            cards: Object,
        }
    },
    methods: {
        join: function(id) {
            console.log(this.success);
            console.log(this.primary)
            axios.get('/community/change_status/'+id).then(response => {
                console.log(response.data);
                this.cards = response.data;
            });
        }
    }
}
</script>