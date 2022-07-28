<style scoped>

</style>

<template>
    <div>
        <div class="page-title-box mt-3 mb-3">
            <h4 class="page-title">Templates management</h4>
        </div>

        <paginationtable :config="config"></paginationtable>

    </div>
</template>

<script>
    import templatesConfig from "../table_configs/templatesTable";
    import Paginationtable from './table/paginationtable.vue';

    export default {
        components: {
            Paginationtable,
        },
        props: {
        },
        data() {
            return {
                config: templatesConfig,
            }
        },
        methods: {
        },
        mounted() {
        }
    }
</script>
