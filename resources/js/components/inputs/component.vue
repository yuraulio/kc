<template>

<div >
    <select v-model="value" class="form-select my-1 my-md-0">
        <option selected="">Select component</option>
        <option value="text_editor">Text editor</option>
        <option value="image">Image</option>
        <option value="hero">Hero</option>
        <option value="content_box">Content box</option>
        <option value="references">References</option>
        <option value="timeline">Timeline</option>
        <option value="categories">Categories</option>
        <option value="posts">Posts</option>
    </select>
</div>

</template>

<script>
    export default {
        props: {
            propValue: null,
            required: false,
            order: Number,
        },
        data() {
            return {
                value: "Select component",
            }
        },
        watch: {
            "propValue": function() {
                this.value = this.propValue;
            },
            "value": function() {
                this.$emit('updatecomponent', [this.value, this.order]);
            }
        },
        methods: {

        },
        mounted() {
            if (this.propValue) {
                this.value = this.propValue;
            }
        }
    }
</script>