<template>
  <div>
    <div class="btn-group border float-md-right mr-1 ml-1" role="group" aria-label="Basic example">
      <button @click="logOut" class="btn logout">log out</button>
    </div>
    <div style="visibilty:hidden;">
      <form method="POST" action="/logout" id="logOutForm">
        <slot></slot>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    logOut: function() {
      document.getElementById("logOutForm").submit();
    }
  }
};
</script>

<style scoped>
.logout {
  color: white;
  background-color: rgb(29, 29, 127);
}
</style>