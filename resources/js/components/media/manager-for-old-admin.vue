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
            alert('11')
            $('#image_upload').val("/uploads/" + $event.data.path);
            $("#upload_form").submit();
        },
    },
    computed: {
        getStartingImage() {
            alert('22')
            return this.startingImage ? JSON.parse(this.startingImage) : null;
        },
        getDefaultImage() {
            alert('223333')
            console.log(this.defaultImage)
            return this.defaultImage ? JSON.parse(this.defaultImage) : null;
        }
    },
}
</script>
