<template>
<div>
    <div v-if="type == 'text'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input type="text" :id="keyput" class="form-control">
    </div>

    <div v-if="type == 'textarea'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <textarea :id="keyput" class="form-control" maxlength="225" rows="3" placeholder=""></textarea>
    </div>

    <div v-if="type == 'image'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <uploadImage :key="keyput" :keyput="keyput"></uploadImage>
    </div>
</div>
</template>

<script>

import uploadImage from './upload-image.vue'

export default {
    components: {
        uploadImage
    },
    props: {
        type: {
            required: true,
            type: String
        },
        keyput: {
            required: true
        },
        label: {}
    }
}
</script>
