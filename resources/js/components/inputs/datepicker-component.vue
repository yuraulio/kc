<style>
    .calendar-left {
        right: 0;
    }
    .vdp-datepicker__clear-button {
        position: absolute;
        top: 8px;
        right: 10px;
    }
</style>

<template>

<div class="mb-3">
    <label for="projectname" class="form-label">{{title}} <span v-if="required">*</span> </label>
    <datepicker 
        @input="$emit('updatevalue', value)" 
        v-model="value" 
        :monday-first="true"
        :clear-button="true"
        input-class="form-control"
        calendar-class="calendar-left"
        clear-button-icon="fa fa-times"
    ></datepicker>
    
</div>

</template>

<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        components: {
            Datepicker
        },
        props: {
            title: String,
            propValue: {},
            required: false
        },
        data() {
            return {
                value: this.propValue,
            }
        },
        watch: {
            "propValue": function() {
                this.value = this.propValue;
            }
        },
        methods: {

        },
        mounted() {
        }
    }
</script>
