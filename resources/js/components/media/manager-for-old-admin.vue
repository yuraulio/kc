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
        imageedit: false,
    },
    data() {
        return {
            
        }
    },
    methods: {
        imageSelected($event) {
            $('#image_upload').val("/uploads/" + $event.data.path);
            $("#upload_form").submit();
        },
    }
}
</script>
