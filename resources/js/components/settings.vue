<template>
  <div>
    <div v-if="loading">
      <div style="margin-top: 150px" class="text-center">
        <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat>
      </div>
    </div>
    <div v-else class="card">
      <div class="card-body">
        <div v-if="settings.cms_mode" class="row">
          <div class="col">
            <h4>CMS mode</h4>
            <div @change="saveSetting(settings.cms_mode)" class="d-inline-block">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="exampleRadios"
                  id="exampleRadios1"
                  value="old"
                  v-model="settings.cms_mode.value"
                />
                <label class="form-check-label" for="exampleRadios1"> Old pages </label>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="exampleRadios"
                  id="exampleRadios2"
                  value="new"
                  v-model="settings.cms_mode.value"
                />
                <label class="form-check-label" for="exampleRadios2"> New pages + old pages as backup </label>
              </div>
            </div>
          </div>
        </div>
        <div v-if="settings.search_placeholder" class="row">
          <div class="col mt-3">
            <h4>Search placeholder</h4>
            <div class="input-group mb-3">
              <input
                type="text"
                class="form-control"
                aria-label="Default"
                v-model="settings.search_placeholder.value"
              />
              <div class="input-group-append">
                <button @click="saveSetting(settings.search_placeholder)" class="btn btn-soft-success" type="button">
                  Save
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {},
  data() {
    return {
      settings: null,
      loading: true,
    };
  },
  methods: {
    saveSetting(setting) {
      this.loading = true;
      axios
        .patch('/api/settings/' + setting.id, {
          id: setting.id,
          value: setting.value,
        })
        .then((response) => {
          if (response.status == 200) {
            this.$toast.success('Saved Successfully!');
            this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
          this.errors = error.response.data.errors;
        });
    },
    getSettings() {
      axios
        .get('/api/settings/')
        .then((response) => {
          if (response.status == 200) {
            this.settings = {};
            response.data.forEach((setting) => {
              this.$set(this.settings, setting.setting, setting);
            });
            this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getSettings();
  },
};
</script>
