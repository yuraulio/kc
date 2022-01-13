<template>

<div >
    <select v-model="value" class="form-select my-1 my-md-0">
        <option selected="">Select component</option>
        <option value="Component2">Component1</option>
        <option value="Component3">Component2</option>
        <option value="Component4">Component3</option>
    </select>
</div>

</template>

<script>
    export default {
        props: {
            propValue: null,
            required: false
        },
        data() {
            return {
                value: "Select component",
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