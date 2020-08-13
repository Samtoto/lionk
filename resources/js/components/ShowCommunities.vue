<template>
<b-container fluid>
        <CommunityList></CommunityList>
    </b-container>
</template>

<script>
// import {List as CommunityList } from './Communities/List';
import CommunityList from './Communities/List';
export default {
    mounted: function() {
    },
    data: function() {
        return {
        }
    },
    components: { CommunityList },
    methods: {
    }
}
</script>