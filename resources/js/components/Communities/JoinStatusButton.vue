<template>
    <b-button
        :variant="buttonVariant"
        @click="joinCommunityToggle()"
        size="sm"
    > {{ buttonText }} </b-button>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import store from '../../store'

const defaultJoinStatusStyle = {
    text: {
        joined: 'Joined',
        unjoined: 'Join'
    },
    variant: {
        joined: 'success',
        unjoined: 'primary'
    }
}

export default {
    props: {
        communityId: Number,
        // joinStatus: Boolean,
    },
    store,
    data() {
        return {
            // buttonText: this.joinStatus ? defaultJoinStatusStyle.text.joined : defaultJoinStatusStyle.text.unjoined,
            // buttonVariant: this.joinStatus ? defaultJoinStatusStyle.variant.joined : defaultJoinStatusStyle.variant.unjoined,
            joinStatus: this.getStatus
        }
    },
    methods: {
        joinCommunityToggle() {
            this.$store.dispatch('communities/JOIN_TOGGLE', this.communityId)
        },
    },
    computed: {
        // getStatus: function() {
        //     // console.log(this.communityId)
        //     // console.log(store.getters['communities/getStatus'](this.communityId))
        //     return store.getters['communities/getStatus'](this.communityId)
            
        // },
        buttonText: function() {
            // console.log(this.communityId);
            const joined = store.getters['communities/getStatus'](this.communityId);
            return joined ? defaultJoinStatusStyle.text.joined : defaultJoinStatusStyle.text.unjoined;
        },
        buttonVariant: function() {
            const joined = store.getters['communities/getStatus'](this.communityId);
            return joined ? defaultJoinStatusStyle.variant.joined : defaultJoinStatusStyle.variant.unjoined;
            
        }
        
    },
    created() {
        // console.log(store)
        // this.$store.dispatch('communities/INIT')
        // this.$store.dispatch('communities/getStatus')
        // console.log(this.getStatus)
        // this.buttonText = this.joinStatus ? defaultJoinStatusStyle.text.joined : defaultJoinStatusStyle.text.unjoined,
        // this.buttonVariant = this.joinStatus ? defaultJoinStatusStyle.variant.joined : defaultJoinStatusStyle.variant.unjoined
    }
}
</script>