import { getCommentsByBlogId, createSubComment, deleteComment } from '../../server/api';

const state = () => ({
    all: [],
    blog_id: 0,
    editing: 0,
    replying: 0
});

const getters = {
    getByParentId: (state) => (parent_id) => {
        return state.all.filter(comment => comment.parent_id === parent_id)
    }
}

const actions = {
    INIT({ commit }, blog_id) {
        commit('setBlogId', blog_id)
        getCommentsByBlogId(blog_id).then(response => {
            commit('init', response.data)
        })
    },
    reply({commit}, comment) {
        createSubComment(comment).then(response => {
            commit('replyComment', response.data)
        })
    },
    delete({ commit }, comment_id) {
        deleteComment(comment_id).then(response => {
            // console.log(response.data)
            commit('updateComment', response.data)
        })
    }
}

const mutations = {

    init(state, comments) {
        state.all = comments
    },
    setBlogId(state, blog_id) {
        state.blog_id = blog_id
    },
    toggleReply(state, comment_id) {
        if (comment_id === state.replying) {
            state.replying = 0
        } else {
            state.replying = comment_id
        }
    },
    replyComment(state, comment) {
            state.all.push(comment)
    },
    updateComment(state, comment) {
        const index = state.all.findIndex(item => item.id === comment.id)
        if (index !== -1) {
            state.all.splice(index, 1, comment)
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}