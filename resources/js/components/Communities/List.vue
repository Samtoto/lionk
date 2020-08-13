<template>
    <b-row  align-h="center">

        <b-col cols="1" md="9" class="py-3" 
            v-for="community in communities" :key="community.id"
        >
            <div>
                <b-card>
                    <b-card-title>{{ community.name }}</b-card-title>
                    <!-- <b-button @click="join(card.id)"  :variant="  card.user.length>0  ? 'success': 'primary' ">{{ card.user.length>0 ? 'Joined': 'Join'}}</b-button> -->
                    <JoinButton :joinStatus="community.joined" :communityId="community.id"></JoinButton>
                </b-card>
            </div>
            
        </b-col>

    </b-row>
</template>

<script>
import store from '../../store'
import { mapActions, mapState } from 'vuex'
import JoinButton from './JoinStatusButton'

export default {
    store,
    computed: mapState({
        communities: state => state.communities.all
    }),
    methods: {
        // ...mapActions([
        //     'INIT'
        // ])
    },
    components: { JoinButton },
    created() {
        this.$store.dispatch('communities/INIT')
    }

}
</script>