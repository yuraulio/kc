<style scoped>

</style>

<template>
    <div>

        <paginationtable :config="config"></paginationtable>

    </div>
</template>

<script>
    import tickersConfig from "../table_configs/tickersTable";
    import Paginationtable from './table/paginationtable.vue';

    export default {
        components: {
            tickersConfig,
            Paginationtable,
        },
        props: {
        },
        data() {
            return {
                config: tickersConfig,
            }
        },
        methods: {
        },
        mounted() {
        }
    }
</script>

