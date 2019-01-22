import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

// root state object
// each Vuex instance i just a single state tree
const state = {
    loader: false,
    user: {}
};

// mutations are operations that actually mutates the state.
// each mutation handler gets the entire state tree as the 
// first argument, followed by additional payload arguments.
// mutations must be synchronous and can be recorded by plugins
// for debugging purposes.
const mutations = {
    setLoaderStatus(state, status) {
        state.loader = status;
    },
    updateUser(state, user) {
        state.user = user;
    }
};

// actions are functions that cause side effects and can involve
// asynchronous operations
const actions = {
    setLoaderState: ({
        commit
    }, status) => commit('setLoaderStatus', status),
    updateUser: ({
        user
    }, status) => commit('updateUser', user),
};

// getters are functions
const getters = {
    loaderStatus: state => state.loader
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
