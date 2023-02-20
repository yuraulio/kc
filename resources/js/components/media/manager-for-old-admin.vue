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
        :value="this.getStartingImage"
        :default_image="this.getDefaultImage"
        :hideAltText="true"
        :imageVersion="'header-image'"
    />

</div>
</template>

<script>
export default {
    props: {
        startingImage: null,
        defaultImage: null
    },
    data() {
        return {

        }
    },
    methods: {
        imageSelected($event) {
            if($event.data != null){
                $('#image_upload').val("/uploads/" + $event.data.path);
            }else{
                $('#image_upload').val('');
            }

            $("#upload_form").submit();
        },
    },
    computed: {
        getStartingImage() {
            return this.startingImage ? JSON.parse(this.startingImage) : null;
        },
        getDefaultImage() {
            return this.defaultImage ? JSON.parse(this.defaultImage) : null;
        }
    },
}
</script>
