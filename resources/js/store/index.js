export default {
    state: { counter: 0 },
    getters: {
        doubleCounter: state => {
            return state.counter * 2;
        }
    },
    actions: {},
    mutations: {}
};
