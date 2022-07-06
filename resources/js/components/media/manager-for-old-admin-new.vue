<style scoped>

</style>

<template>
<div>

    <multiput
        key="image"
        keyput="image"
        label="Image"
        type="image"
        @inputed="imageSelected($event)"
        :uuid="$uuid.v4()"
        mode="single"
        :imageEdit="imageedit"
    />

</div>
</template>

<script>
export default {
    props: {
        imageedit: false
    },
    data() {
        return {

        }
    },
    mounted()  {
        console.log('has mounted media model')
    },
    methods: {
        imageSelected($event) {
            $('#'+current_icon_input).val("/uploads" + $event.data.path);
            $('#'+current_icon_input_alt_text).val($event.data.alt_text);
        },
    }
}
</script>
