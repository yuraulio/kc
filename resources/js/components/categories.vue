<style scoped></style>

<template>
    <div>
        <div class="page-title-box mt-3 mb-3">
            <h4 class="page-title">Content categories</h4>
        </div>
        <paginationtable :config="config"></paginationtable>
    </div>
</template>

<script>
import categoriesConfig from '../table_configs/categoriesTable';
import Paginationtable from './table/paginationtable.vue';

export default {
    components: {
        Paginationtable,
    },
    props: {},
    data() {
        return {
            config: categoriesConfig,
        };
    },
    methods: {},
    mounted() {},
};
</script>
