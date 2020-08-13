import Vue from 'vue'
import Vuex from 'vuex'
import blogs from './modules/blogs'
import communities from './modules/communities'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        blogs,
        communities
        // products
    },
    strict: debug,
    // plugins: debug ? [createLogger()] : []
})