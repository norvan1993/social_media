<template>
  <v-dialog max-width="300px" full-width>
    <v-btn flat slot="activator" class="success">respond</v-btn>

    <v-layout class="text-xs-center white" style="width:100%;" row wrap>
      <v-flex xs12 px-2>
        <v-btn flat class="info" @click="accept()" block>accept</v-btn>
      </v-flex>
      <v-flex xs12 px-2>
        <v-btn flat class="error" @click="reject()" block>reject</v-btn>
      </v-flex>
      <v-flex xs12 px-2>
        <v-btn flat class="warning" @click="ignore()" block>ignore</v-btn>
      </v-flex>
    </v-layout>
  </v-dialog>
</template>

<script>
export default {
  methods: {
    accept() {},
    reject() {},
    ignore() {}
  }
};
</script>

<style lang="scss" scoped>
</style>