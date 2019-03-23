/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import VueRouter from "vue-router";
Vue.use(VueRouter);

let routes = [
    {
        path: "/",
        component: require("./components/Guest.vue").default,
        children: [
            {
                path: "",
                component: require("./components/Welcome.vue").default
            },
            {
                path: "/login",
                component: require("./components/LogIn.vue").default
            },
            {
                path: "/register",
                component: require("./components/Register.vue").default
            }
        ]
    },
    {
        path: "/home",
        component: require("./components/Home.vue").default
    },
    {
        path: "/profile/:id/",
        component: require("./components/Profile.vue").default,
        children: [
            {
                path: "",
                component: require("./components/Timeline.vue").default
            },
            {
                path: "friends",
                component: require("./components/Friends.vue").default
            },
            {
                path: "photos",
                component: require("./components/Photos.vue").default
            },
            {
                path: "about",
                component: require("./components/About.vue").default
            }
        ]
    }
];
const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("auth-header", require("./components/AuthHeader.vue").default);
Vue.component(
    "passport-authorized",
    require("./components/passport/AuthorizedClients.vue").default
);
Vue.component(
    "passport-clients",
    require("./components/passport/Clients.vue").default
);
Vue.component(
    "passport-tokens",
    require("./components/passport/PersonalAccessTokens.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router
});
