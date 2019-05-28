export default {
    state: {
        auth: false,
        guest: false
    },
    getters: {},
    mutations: {
        increcement: state => {
            return state.counter++;
        },
        vuexLogIn: state => {
            state.auth = true;
        },
        vuexLogOut: state => {
            state.auth = false;
        }
    },
    actions: {}
};
