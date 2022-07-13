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
    },
    methods: {
        imageSelected($event) {

            $('#'+current_icon_input).val("/uploads" + $event.data.path);
            $('#'+current_icon_input_alt_text).val($event.data.alt_text);

            let input_icon_wrapper = $('#'+current_icon_input).parent()[0]
            input_icon_wrapper = $(input_icon_wrapper).find('.input-icon')
            $(input_icon_wrapper).empty()

            $(input_icon_wrapper).append(`<img src="${"/uploads" + $event.data.path}">`)
        },
    }
}
</script>
