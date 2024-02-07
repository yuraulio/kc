<template>
  <div class="card">
    <div class="card-body">
      <h4 class="mb-2">{{ pageTitle }} : {{ title }}</h4>

      <div class="row mt-3">
        <span class="text-danger">{{ error }}</span>
      </div>

      <div class="row mt-3">
        <div class="col-12 text-center">
          <button @click="remove()" type="button" class="btn btn-danger waves-effect waves-light m-1">
            <i class="mdi mdi-delete me-1"></i> Delete
          </button>
          <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-light waves-effect waves-light m-1">
            <i class="fe-x me-1"></i> Cancel
          </button>
        </div>
      </div>
    </div>
    <!-- end card-body -->
  </div>
  <!-- end card-->
</template>

<script>
export default {
  props: {
    pageTitle: String,
    route: String,
    id: Number,
    title: String,
  },
  data() {
    return {
      error: null,
    };
  },
  methods: {
    remove() {
      this.errors = null;
      axios
        .post('/api/' + this.route + '/delete/' + this.id, {
          title: this.title_value,
          description: this.description_value,
        })
        .then((response) => {
          if (response.status == 200) {
            this.$emit('refreshcategories');
            this.$emit('updatemode', 'list');
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = 'Delete failed.';
        });
    },
  },
  mounted() {},
};
</script>
