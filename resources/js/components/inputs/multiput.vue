<template>
<div :class="type == 'text' ? 'col-lg-6' : ''">
    <div v-if="type == 'text'" class="mb-3 ">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input v-model="editorData" type="text" :id="keyput" class="form-control">
    </div>

    <div v-if="type == 'textarea'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <textarea :id="keyput" v-model="editorData" class="form-control" maxlength="225" rows="3" placeholder=""></textarea>
    </div>

    <div v-if="type == 'image'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <uploadImage @updatedimage="updatedimage" :key="keyput" :keyput="keyput"></uploadImage>
    </div>

    <div v-if="type == 'text_editor'" class="mb-3">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <ckeditor :height="300" :editor="editor" :id="keyput" v-model="editorData" :config="editorConfig"></ckeditor>
    </div>
</div>
</template>

<script>

import uploadImage from './upload-image.vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    components: {
        uploadImage,
        ClassicEditor
    },
    props: {
        type: {
            required: true,
            type: String
        },
        keyput: {
            required: true
        },
        label: {},
        value: {}
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: null,
            editorConfig: {
                height: 300,
                toolbar: {
                    items: [
                        'heading', '|',
                        'fontfamily', 'fontsize', '|',
                        'alignment', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
                        'link', '|',
                        'outdent', 'indent', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'code', 'codeBlock', '|',
                        'insertTable', '|',
                        'uploadImage', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                }
                // The configuration of the editor.
            }
        };
    },
    methods: {
        updatedimage($event) {
            this.$emit('inputed', { 'data': this.$event, 'key': this.keyput})
        }
    },
    watch: {
        editorData() {
            this.$emit('inputed', { 'data': this.editorData, 'key': this.keyput })
        }
    },
    mounted() {
        if (this.value) {
            this.editorData = this.value;
        }
    }
}
</script>

<style>
.ck.ck-editor__main>.ck-editor__editable {
    min-height: 200px;
}
</style>
