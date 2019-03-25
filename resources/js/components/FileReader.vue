<template>
  <div></div>
</template>

<script>
export default {
  props: ["file"],
  methods: {
    cenvetToData(value) {
      const reader = new FileReader();
      reader.onload = function(e) {
        alert(e.target.result);
      };
      reader.readAsDataURL(value);
    }
  },

  watch: {
    file: function() {
      convertToData(file);
    }
  }
};
</script>

<style scoped></style>
