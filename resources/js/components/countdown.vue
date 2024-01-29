<style scoped></style>

<template>
    <div>
        <paginationtable :config="config"></paginationtable>
    </div>
</template>

<script>
import countdownConfig from '../table_configs/countdownTable';
import Paginationtable from './table/paginationtable.vue';

export default {
    components: {
        countdownConfig,
        Paginationtable,
    },
    props: {},
    data() {
        return {
            config: countdownConfig,
        };
    },
    methods: {},
    mounted() {},
};
</script>
