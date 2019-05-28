<template>
    <div>
        <!--header-->
        <vue-header></vue-header>
        <div>you are successfully logged in</div>
    </div>
</template>

<script>
import Header from "./Header.vue";
export default {
    components: {
        "vue-header": Header
    }
};
</script>

<style scoped>
</style>
