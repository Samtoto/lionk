<template>
<b-container fluid>
        <b-row  align-h="center">

            <b-col cols="1" md="9" class="py-3" 
                v-for="card in cards" :key="card.id"
            >
                <div>
                    <b-card>
                        <b-card-title>{{ card.name}} </b-card-title>
                        <b-button @click="join(card.id)"  :variant="  card.user.length>0  ? 'success': 'primary' ">{{ card.user.length>0 ? 'Joined': 'Join'}}</b-button>
                        
                    </b-card>
                </div>
                
            </b-col>

            
        </b-row>
        
    </b-container>
</template>

<script>
export default {
    mounted: function() {
        axios.get('/community/all').then(response => {
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
            axios.get('/community/change_status/'+id).then(response => {
                this.cards = response.data;
            }).catch(error => {
                window.location.href = '/login';
            });
        }
    }
}
</script>