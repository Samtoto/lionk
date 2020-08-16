import { getProfile } from '../../server/api';

const state = () => ({
    profile = {}
});

const getters = {

}

const actions = {
    INIT({commit}) {
        getProfile().then(response => {
            commit('init', response.data)
        })
    }
}

const mutations = {
    init(state, profile) {
        state.profile = profile
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}