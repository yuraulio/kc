<style>
    @import "https://cdn3.devexpress.com/jslib/22.1.6/css/dx.common.css";
    @import "https://cdn3.devexpress.com/jslib/22.1.6/css/dx.light.css";
   .wrapper {
        max-width: 250px;
        margin: 0 auto;
    }
</style>

<template>

<div class="mb-3">
    <label for="projectname" class="form-label">{{title}} <span v-if="required">*</span> </label>


    <!-- <DxDateBox
    :value="now"
    type="datetime"
    /> -->

    <DxDateBox
        v-model:value="value"
        type="datetime"
        :value="value"
        @value-changed="$emit('updatevalue', value)"
    />




</div>

</template>

<script>

    import DxDateBox from 'devextreme-vue/date-box';

    export default {
        components: {
            DxDateBox,
        },
        props: {
            title: String,
            value: null,
            required: false,
        },
        data() {
            return {
                now: new Date(),
                min: new Date(1900, 0, 1),
                dateClear: new Date(),
            }
        },
        mounted() {

        }
    }
</script>
