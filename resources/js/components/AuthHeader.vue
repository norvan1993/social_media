<template>
    <v-toolbar flat>
        <brand></brand>
        <v-spacer></v-spacer>
        <logout-button></logout-button>
    </v-toolbar>
</template>

<script>
import Brand from "./Brand.vue";
import AuthNavButtons from "./AuthNavButtons.vue";
export default {
    components: {
        brand: Brand,
        "logout-button": AuthNavButtons
    }
};
</script>

<style scoped>
.brand {
    font-size: 32px;
    font-family: "Monoton";
    color: rgb(29, 29, 127);
}
.signin {
    color: white;
    background-color: rgb(29, 29, 127);
}
.signup {
    color: rgb(29, 29, 127);
    background-color: white;
}
</style>
