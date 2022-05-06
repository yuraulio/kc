<template>
<div :class="(type == 'text' && !size ? 'col-lg-6' : size) + ' mt-2'">
    <div v-if="type == 'text'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input @input="update(editorData)" v-model="editorData" type="text" :id="keyput" class="form-control">
        <slot></slot>
    </div>

    <div v-if="type == 'image'" :key="keyput + 'media'" class="image-input">
        <div
        :ref="keyput + 'media'"
        class="offcanvas offcanvas-start"
        data-bs-scroll="true"
        tabindex="-1"
        :id="'mediaCanvas' + keyput"
        aria-labelledby="mediaCanvasLabel"
        style="visibility: visible; width: 100%"
        aria-modal="true"
        role="dialog">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mediaCanvasLabel"></h5>
                <button :ref="(keyput + 'mediabtn')" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->

            <div class="offcanvas-body" style="padding: 0px !important">
                <media-manager v-if="loadstart[(keyput + 'media')]" mode="single" :loadstart="loadstart[(keyput + 'media')]" @updatedimg="updatedmedia($event,(keyput + 'media'))" :key="keyput"></media-manager>
            </div> <!-- end offcanvas-body-->
        </div>
        <div class="text-center">
            <div class="d-grid text-center" v-if="value">

                <img @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" :src="value.url" alt="image" class="img-fluid rounded" >

                <i @click="removeImage()" class="mdi mdi-delete text-muted vertical-middle d-block fs-4 mt-1"></i>

                <div class="mt-2">
                    <label class="form-label float-start">Alt Text</label>
                    <input type="text" v-model="value.alt_text" class="form-control">
                </div>
            </div>
            <div v-else>
                <i @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" class="text-muted dripicons-photo d-none image-input-icon" style="font-size: 100px;"></i>
                <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary image-input-button">Add Media</button>
            </div>
        </div>
    </div>

    <div v-if="type == 'gallery'" :key="keyput + 'media'" class="image-input">
            <div :ref="keyput + 'media'" class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" :id="'mediaCanvas' + keyput" aria-labelledby="mediaCanvasLabel" style="visibility: visible; width: 100%" aria-modal="true" role="dialog">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mediaCanvasLabel"></h5>
                <button :ref="(keyput + 'mediabtn')" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->

            <div class="offcanvas-body" style="padding: 0px !important">
                <media-manager v-if="loadstart[(keyput + 'media')]" mode="gallery" :loadstart="loadstart[(keyput + 'media')]" @updatedimg="updatedgallery($event,(keyput + 'media'))" :key="keyput"></media-manager>
            </div> <!-- end offcanvas-body-->
        </div>
        <div class="text-center">
            <div class="d-grid text-center" v-if="value" style="display: block;">
                <div class="row">
                    <div v-for="(val, index) in value" class="col-sm-3 mb-2">
                        <img @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" :src="val.url" alt="image" class="img-fluid img-thumbnail" width="200" height="200">
                        <p class="mb-0">
                            <code>
                                <template v-if="val.name.length < 50">
                                    {{ val.name }}
                                </template>
                                <template v-else>
                                    {{ limit(val.name, 50) }}...
                                </template>
                                
                            </code>
                            <i @click="removeGalleryImage(index)" class="mdi mdi-delete text-muted vertical-middle"></i>
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <i @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" class="text-muted dripicons-photo-group d-none image-input-icon" style="font-size: 100px;"></i>
                        <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary image-input-button">Add Media</button>
                    </div>
                </div>
            </div>
            <div v-else>
                <i @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" class="text-muted dripicons-photo-group d-none image-input-icon" style="font-size: 100px;"></i>
                <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary image-input-button">Add Media</button>
            </div>
        </div>
    </div>

    <div v-if="type == 'textarea'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <textarea :id="keyput" v-model="editorData" class="form-control" maxlength="10000" rows="3" placeholder=""></textarea>
    </div>

    <!-- <div v-if="type == 'image'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <uploadImage @updatedimage="updatedimage" :key="keyput" :prevalue="value"  :keyput="keyput"></uploadImage>
    </div> -->

    <div v-if="type == 'text_editor'" class="text-editor-input">
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
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <multidropdown
            :title="title"
            @updatevalue="updated"
            :prop-value="existingValue"
            :route="route"
            :fetch="fetch"
            :multi="multi"
            :taggable="taggable"
        ></multidropdown>
    </div>

    <div v-if="type == 'tabs'" class="">
        <tcedit
            :mode="mode"
            ref="tc"
            :pseudo="pseudo"
            :predata="existingValue"
            name="tabs"
            :tabsProp="tabsProp"
            @updatetabscomponent="updated"
            @updatetabs="updatedTabs"
        ></tcedit>
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
import MediaManager from '../media/media-manager.vue';


export default {
    components: {
        uploadImage,
        ClassicEditor,
        contentComponent,
        EditorContent,
        multidropdown,
        MediaManager
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
        multi: {},
        uuid: String,
        tabsProp: [],
        pseudo: Boolean,
        mode: String,
        inputs: {},
        fetch: {
            default: true
        },
    },
    data() {
        return {
            loadstart: {},
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
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        {
                            model: 'subtitle',
                            view: {
                                name: 'p',
                                classes: 'editor-subtitle'
                            },
                            title: 'Subtitle',
                            class: 'ck-heading_subtitle',
                            converterPriority: 'high'
                        }
                    ]
                }
                // The configuration of the editor.
            },
        };
    },
    methods: {
        updatedmedia($event, ref) {
            $event.siblings = null;
            $event.subfiles = null;
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
            this.$refs[ref+'btn'].click()
            this.$set(this.loadstart, ref,  false);
        },
        updatedgallery($event, ref) {
            $event.siblings = null;
            $event.subfiles = null;
            var data;
            if (!this.value) {
                data = [];
            } else {
                data = this.value;
            }

            var image_data = {
                url: $event.url,
                full_path: $event.full_path,
                alt_text: $event.alt_text,
                link: $event.link,
                name: $event.name,
            }
            data.push(image_data);

            this.$emit('inputed', { 'data': data, 'key': this.keyput})
            this.$refs[ref+'btn'].click()
            this.$set(this.loadstart, ref,  false);
        },
        updatedimage($event) {
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
        },
        updated($event) {
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
        },
        updatedTabs($event) {
            this.$emit('inputedTabs', { 'data': $event, 'key': this.keyput})
        },
        addRepetableBlock() {
            if (this.editorData) {
                this.editorData.push(this.inputs);
            } else {
                this.editorData = [];
                this.editorData[0] = this.inputs;
            }
        },
        update(editorData) {
            if (this.keyput.substring(0,9) == "meta_slug") {
                eventHub.$emit('updateslug', editorData);
            }
        },
        limit (string = '', limit = 0) {
            return string.substring(0, limit)
        },
        removeGalleryImage(index) {
            this.value.splice(index, 1);
        },
        removeImage() {
            this.value = null;
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

    .page-edit-simple .ck-editor:not(:hover) .ck-editor__top{
        visibility: hidden;
    }
    .page-edit-simple .ck-editor .ck-editor__top * {
        border: 0px;
    }
    .page-edit-simple .ck-editor .ck-toolbar {
        background-color: transparent;
    }
    .page-edit-simple .ck-editor:not(:hover) .ck-content {
        border: 0px;
        box-shadow: none;
    }
    .page-edit-simple .ck-editor .ck-content {
        border: 0px;
    }
    .page-edit-simple .ck.ck-editor__editable:not(.ck-editor__nested-editable).ck-focused {
        outline: none;
        border: 0px;
        box-shadow: none;
    }
</style>

<style scoped>
    .page-edit-simple .text-editor-input .form-label {
        display: none;
    }

    .page-edit-simple .form-label {
        color: #98a6ad !important;
        font-size: 12px;
    }

    .page-edit-simple .image-input img.img-fluid.rounded {
        max-height: 500px;
    }
    .page-edit-simple .image-input .change-media {
        display: block;
        width: 100%;
        margin-top: 0px!important;
    }

    .page-edit-simple .image-input .d-grid {
        display: block!important;
    }
    .page-edit-simple .image-input .image-input-icon {
        display: block!important;
    }
    .page-edit-simple .image-input .image-input-button {
        display: none!important;
    }
</style>
