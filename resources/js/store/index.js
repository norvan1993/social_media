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
            state.guest = false;
        },
        vuexLogOut: state => {
            state.auth = false;
            state.guest = true;
        }
    },
    actions: {}
};
