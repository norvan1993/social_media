<template>
    <v-toolbar flat>
        <brand></brand>
        <v-spacer></v-spacer>
        <guest-buttons></guest-buttons>
    </v-toolbar>
</template>

<script>
import Brand from "./Brand.vue";
import GuestButtons from "./GuestButtons.vue";

export default {
    components: {
        brand: Brand,
        "guest-buttons": GuestButtons
    }
};
</script>

<style scoped>
</style>
