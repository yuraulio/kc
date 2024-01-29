<style scoped></style>

<template>
    <div>
        <div class="page-title-box mt-3 mb-3">
            <h4 class="page-title">Pages</h4>
        </div>

        <paginationtable :config="config"></paginationtable>
    </div>
</template>
<script>
import Paginationtable from './table/paginationtable.vue';
import pagesConfig from '../table_configs/pagesTable';

export default {
    components: {
        pagesConfig,
        Paginationtable,
    },
    props: {},
    data() {
        return {
            config: pagesConfig,
        };
    },
    watch: {
        filter: function () {
            this.getData();
        },
        category_value: function () {},
    },
    methods: {},
    mounted() {},
};
</script>

<style scoped></style>
