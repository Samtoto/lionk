import { getAllBlogs, joinCommunityToggle } from '../../server/api';

const state = () => ({
    all: []
});

const getters = {
    getByCommunityId: (state) => (community_id) => {
        console.log(community_id);
        return state.all.find(item => item.community_id === community_id)
    }
}

const actions = {
    INIT({ commit }) {
        getAllBlogs().then(response => {
            const blogs = response.data
            commit('setItems', blogs)
        })
    },
    JOIN_COMMUNITY({ commit }, community_id) {
        joinCommunityToggle(community_id).then(response => {
            commit('changeCommunityStatus', community_id)
        })
    }
}

const mutations = {
    setItems(state, blogs) {
        state.all = blogs
    },
    changeCommunityStatus(state, community_id) {
        console.log(getters.getByCommunityId);
        // console.log(state);
        let changeCommunityStatusItems = getters.getByCommunityId(community_id);
        console.log(changeCommunityStatusItems(community_id));
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}