<style scoped>
</style>

<template>
  <div>
    <div class="page-title-box mt-3 mb-3">
      <h4 class="page-title">Admins</h4>
    </div>

    <paginationtable :config="config"></paginationtable>
  </div>

  <!-- <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div> -->
</template>

<script>

import adminsConfig from "../table_configs/adminsTable";
import Paginationtable from './table/paginationtable.vue';

export default {
  components: {
    Paginationtable
  },
  props: {},
  data() {
    return {
      config: adminsConfig,
    };
  },

  methods: {

  },
  mounted() {

  },
};
</script>
