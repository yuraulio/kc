<template>

<div class="mb-3">
    <label for="projectname" class="form-label">{{title}} <span v-if="required">*</span> </label>
    <input @input="$emit('updatevalue', value)" type="text" class="form-control" :placeholder="'Enter ' + title" v-model="value">
</div>

</template>

<script>
    export default {
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
            // console.log("prop", this.propValue)
        }
    }
</script>
