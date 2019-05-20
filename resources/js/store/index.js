export default {
    state: { counter: 5 },
    getters: {
        doubleCounter: state => {
            return state.counter * 2;
        }
    },
    actions: {},
    mutations: {}
};
