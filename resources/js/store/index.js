import Vue from 'vue'
import Vuex from 'vuex'
import blogs from './modules/blogs'
import communities from './modules/communities'
import comments from './modules/comments'
import user from './modules/user'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        blogs,
        communities,
        comments,
        user
        // products
    },
    strict: debug,
    // plugins: debug ? [createLogger()] : []
})