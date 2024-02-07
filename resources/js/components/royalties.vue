<style scoped></style>

<template>
  <div>
    <paginationtable :config="config"></paginationtable>
  </div>
</template>

<script>
import royaltiesConfig from '../table_configs/royaltiesTable';
import Paginationtable from './table/paginationtable.vue';

export default {
  components: {
    royaltiesConfig,
    Paginationtable,
  },
  props: {},
  data() {
    return {
      config: royaltiesConfig,
    };
  },
  methods: {},
  mounted() {},
  created() {
    this.config.royaltyView = 'list';
  },
};
</script>
