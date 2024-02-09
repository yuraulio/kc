<template>
  <div class="mb-3">
    <label for="projectname" class="form-label">{{ title }} <span v-if="required">*</span> </label>
    <select @change="$emit('updatevalue', value)" v-model="value" class="form-select my-1 my-md-0">
      <option v-for="item in list" :value="item.id">{{ item.title }}</option>
    </select>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    propValue: '',
    required: false,
    route: String,
  },
  data() {
    return {
      value: 0,
      list: [],
    };
  },
  watch: {
    propValue: function () {
      this.value = this.propValue;
    },
  },
  methods: {
    get() {
      axios
        .get('/api/' + this.route)
        .then((response) => {
          if (response.status == 200) {
            var data = response.data.data;
            this.list = data;
            this.list.unshift({
              id: 0,
              title: 'Select',
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.get();
  },
};
</script>
