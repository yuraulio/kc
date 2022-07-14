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
        :value="JSON.parse(startingImage)"
        :hideAltText="true"
    />

</div>
</template>

<script>
export default {
    props: {
        startingImage: null,
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
