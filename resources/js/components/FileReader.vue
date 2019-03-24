<template>
  <div></div>
</template>

<script>
export default {
  props: ["file"],
  watch: {
    file: function(value) {
      convertToData(value);
    }
  },
  methods: {
    cenvetToData(value) {
      const reader = new FileReader();
      reader.onload = function(e) {
        this.$emit("test", e.target.result);
      };
      reader.readAsDataURL(value);
    }
  }
};
</script>

<style scoped></style>
