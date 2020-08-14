import { joinCommunityToggle, getALlCommunity } from '../../server/api';

const state = () => ({
    all: []
});

const getters = {
    getById: (state) => (community_id) => {
        // return {}
        return state.all.find(community => community.id == community_id)
    },
    getStatus: (state, getters) => (community_id) => {
        return getters.getById(community_id).joined
    }
}

const actions = {
    JOIN_TOGGLE({commit, getters}, community_id) {
        joinCommunityToggle(community_id).then(response => {
            // let community = getters.getById(community_id);
            // let community = response.data;
            commit('changeStatus', response.data)
        })
    },
    INIT({commit}) {
        getALlCommunity().then(response => {
            commit('init', response.data)
            // state.communities = response.data
        })
    }
}

const mutations = {
    changeStatus(state, community) {
        const index = state.all.findIndex(communityItem => communityItem.id === community.id);
        console.log(index)
        console.log(community)
        if (index !== -1) {
            state.all.splice(index, 1, community)
        }
        // let changeCommunity = state.all.find(community => community.id == com.id)
        // changeCommunity.joined = !changeCommunity.joined;
        // change.name = '122211'
        // console.log(change.joined)

        // console.log(community)
        // let com = rootState.all
        // console.log(com)
        // console.log(getters.getById)
        // let community = getters.getById(community_id);
        // console.log(community);
    },
    init(state, communities) {
        state.all = communities;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}