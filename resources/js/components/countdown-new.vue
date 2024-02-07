<style scoped></style>

<template>
  <div>
    <template v-if="simple">
      <countdowneditable
        title="true"
        type="new"
        route="countdown"
        page-title="New Countdown"
        :config="config"
      ></countdowneditable>
    </template>
    <!-- <template v-else>

            <countdowneditable
                title="true"
                type="edit"
                route="templates"
                :page-title="template.title"
                :predata="content"
            ></countdowneditable>


    </template> -->
  </div>
</template>

<script>
import countdowneditable from './countdowneditable.vue';
import countdownConfig from '../table_configs/countdownTable';

export default {
  components: {
    countdowneditable,
    countdownConfig,
  },
  props: {},
  data() {
    return {
      pageId: null,
      page: null,
      content: null,
      simple: true,
      config: countdownConfig,
    };
  },
  methods: {
    getPage() {
      if (this.pageId) {
        axios
          .get('/api/pages/' + this.pageId)
          .then((response) => {
            if (response.status == 200) {
              this.page = response.data.data;
              this.content = JSON.parse(this.page.content);
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    changeMode(page, content) {
      this.page = page;
      this.content = this.page.content;
      this.simple = !this.simple;
    },
    setPage(page) {
      this.page = page;
      this.content = JSON.parse(this.page.content);
    },
  },
  mounted() {},
};
</script>
