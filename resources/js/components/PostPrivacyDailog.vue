<template>
    <v-dialog max-width="300px" full-width>
        <v-icon flat slot="activator" small>{{ic}}</v-icon>

        <v-card>
            <v-card-title primary-title>
                <h3 class="headline mb-0">who can see your post</h3>
            </v-card-title>
            <hr />
            <v-card-text>
                <v-form class="px-3">
                    <v-text-field label="Title" color="success"></v-text-field>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["ic", "postPrivacy"],
    data() {
        return {};
    },

    methods: {}
};
</script>
<style lang="scss" scoped>
</style>

