import { getCommentsByBlogId } from '../../server/api';


const recursiveFind = function (comments, id) {
    // console.log(comments)
    for (let comment of comments) {
        if (comment.id === id) {
            return comment
        }
        if (comment.hasOwnProperty('all_children')) {
            let search = recursiveFind(comment.all_children, id);
            if (search) {
                return search;
            }
        }
    }
    return null;
}

const list_to_tree = function (list) {
    var map = {}, node, roots = [], i;

    for (i = 0; i < list.length; i += 1) {
        map[list[i].id] = i; // initialize the map
        list[i].all_children = []; // initialize the children
    }
    // console.log(map);
    for (i = 0; i < list.length; i += 1) {
        node = list[i];
        // console.log('id' + node.id + '| i= ' + i);
        if (node.parent_id) {
            
            list[map[node.parent_id]].all_children.push(node);
            
        } else {
            roots.push(node);
        }
    }
    return roots;
}

const state = () => ({
    all: [],
    editing: 0,
    replying: 0
});

const getters = {

}

const actions = {
    INIT({ commit }, blog_id) {
        getCommentsByBlogId(blog_id).then(response => {
            commit('init', response.data)
        })
    }
}

const mutations = {

    init(state, comments) {
        state.all = list_to_tree(comments)
    },
    toggleReply(state, comment_id) {
        if (comment_id === state.replying) {
            state.replying = 0
        } else {
            state.replying = comment_id
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