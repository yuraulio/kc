<template>

<div class="mb-3">
    <label for="projectname" class="form-label">{{title}}</label>
    <input @input="$emit('updatevalue', value)" type="text" class="form-control" :placeholder="'Enter ' + title" v-model="value">
</div>

</template>

<script>
    export default {
        props: {
            title: String,
            propValue: null,
        },
        data() {
            return {
                value: "",
            }
        },
        // watch: {
        //     "propValue": function(val, oldVal) {
        //         this.value = this.val;
        //     }
        // },
        methods: {

        },
        mounted() {
            if (this.propValue){
                this.value = this.propValue;
            }
        }
    }
</script>