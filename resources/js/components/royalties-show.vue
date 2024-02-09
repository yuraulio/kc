<style scoped></style>

<template>
  <div>
    <royalty-item
      title="true"
      category="true"
      type="edit"
      route="royalties"
      page-title="Show Royalty"
      :data="item"
      :instructor="data.instructor"
      :view="data.view"
      :uuid="null"
      @changeMode="changeMode"
      :key="NaN"
      :config="config"
    ></royalty-item>
  </div>
</template>

<script>
import royaltyItem from './royalty-item.vue';
import royaltiesConfig from '../table_configs/royaltiesTable';

export default {
  components: {
    royaltyItem,
    royaltiesConfig,
  },
  props: {
    data: Object,
  },
  data() {
    return {
      item: {},
      content: null,
      simple: true,
      config: royaltiesConfig,
    };
  },
  methods: {
    getPage() {
      if (this.instructor.id) {
        axios
          .get('/api/royalties/' + this.instructor.id)
          .then((response) => {
            if (response.status == 200) {
              this.item = response.data.data;

              //this.content = JSON.parse(this.page.content);
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    changeMode(page) {
      this.page = page;
      this.content = this.page.content;
      this.simple = !this.simple;
    },
  },
  mounted() {
    //this.getPage();
  },
  created() {},
};
</script>
