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
        type="datetime"
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
            propValue: {},
            required: false
        },
        data() {
            return {
                value: new Date(1981, 3, 27),
                now: new Date(),
                firstWorkDay2017: new Date(2017, 0, 3),
                min: new Date(1900, 0, 1),
                dateClear: new Date(2015, 11, 1, 6),
            }
        },

        methods: {

        },
        mounted() {
            console.log('from datetimepicker component')
        }
    }
</script>
