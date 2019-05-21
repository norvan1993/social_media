export default {
    state: { counter: 5 },
    getters: {
        doubleCounter: state => {
            return state.counter * 2;
        }
    },

    mutations: {
        increcement: state => {
            return state.counter++;
        }
    },
    actions: {
        increcement: ({ commit }) => {
            commit("increcement");
        }
    }
};
