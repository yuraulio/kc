<template>
<div :class="(type == 'text' && !size ? 'col-lg-6' : size) + ' mt-2'">
    <div v-if="type == 'text'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input v-model="editorData" type="text" :id="keyput" class="form-control">
    </div>

    <div v-if="type == 'textarea'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <textarea :id="keyput" v-model="editorData" class="form-control" maxlength="500" rows="3" placeholder=""></textarea>
    </div>

    <div v-if="type == 'image'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <uploadImage @updatedimage="updatedimage" :key="keyput" :prevalue="value"  :keyput="keyput"></uploadImage>
    </div>

    <div v-if="type == 'text_editor'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <ckeditor v-if="texteditor == 'ck'" :height="300" :editor="editor" :id="keyput" v-model="editorData" :config="editorConfig"></ckeditor>

        <editor-content v-if="texteditor == 'tiny'" :editor="editorT" />
    </div>

    <div v-if="type == 'contentComponent'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <contentComponent @updatedimage="updatedimage" :key="keyput" :keyput="keyput"></contentComponent>
    </div>

    <div v-if="type == 'checkbox'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input v-model="editorData" type="checkbox" :id="keyput" class="form-check-input d-block">
    </div>

    <div v-if="type == 'number'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input v-model="editorData" type="number" :id="keyput" class="form-control">
    </div>

    <div v-if="type == 'multidropdown'" class="">
        <multidropdown
            :title="title"
            @updatevalue="updated"
            :prop-value="existingValue"
            :route="route"
            :fetch="false"
            :multi="multi"
            :taggable="taggable"
        ></multidropdown>
    </div>
</div>
</template>

<script>

import uploadImage from './upload-image.vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import contentComponent from './content-components.vue'
import { Editor, EditorContent } from '@tiptap/vue-2'
import StarterKit from '@tiptap/starter-kit'
import multidropdown from './multidropdown.vue'

export default {
    components: {
        uploadImage,
        ClassicEditor,
        contentComponent,
        EditorContent,
        multidropdown
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
        value: {},
        size: {
            type: String,
            default: ''
        },
        existingValue: {},
        title: {},
        route: {},
        taggable: {},
        multi: {}
    },
    data() {
        return {
            texteditor: 'ck',
            editor: ClassicEditor,
            editorData: this.value,
            editorT: null,
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
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
        },
        updated($event) {
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
        }
    },
    watch: {
        editorData() {
            this.$emit('inputed', { 'data': this.editorData, 'key': this.keyput })
        },
        "value": function() {
            this.editorData = this.value;
        }
    },
    mounted() {
        if (this.value) {
            this.editorData = this.value;
        }
        this.editorT = new Editor({
            content: this.editorData,
            extensions: [
                StarterKit,
            ],
        })
    },
    beforeDestroy() {
        this.editorT.destroy()
    },
}
</script>

<style>
.ck.ck-editor__main>.ck-editor__editable {
    min-height: 200px;
}
</style>
