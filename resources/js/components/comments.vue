<style scoped></style>

<template>
    <div>
        <div class="page-title-box mt-3 mb-3">
            <h4 class="page-title">Comments management</h4>
        </div>

        <paginationtable :config="config"></paginationtable>
    </div>
</template>

<script>
import commentsConfig from '../table_configs/commentsTable';
import Paginationtable from './table/paginationtable.vue';

export default {
    components: {
        Paginationtable,
    },
    props: {},
    data() {
        return {
            config: commentsConfig,
        };
    },
    methods: {},
    mounted() {},
};
</script>
