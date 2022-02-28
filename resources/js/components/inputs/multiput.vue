<template>
<div :class="(type == 'text' && !size ? 'col-lg-6' : size) + ' mt-2'">
    <div v-if="type == 'text'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <input @input="update(editorData)" v-model="editorData" type="text" :id="keyput" class="form-control">
        <slot></slot>
    </div>

    <div v-if="type == 'image'" :key="keyput + 'media'" >
            <div :ref="keyput + 'media'" class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" :id="'mediaCanvas' + keyput" aria-labelledby="mediaCanvasLabel" style="visibility: visible; width: 100%" aria-modal="true" role="dialog">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mediaCanvasLabel"></h5>
                <button :ref="(keyput + 'mediabtn')" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->

            <div class="offcanvas-body" style="padding: 0px !important">
                <media-manager v-if="loadstart[(keyput + 'media')]" :loadstart="loadstart[(keyput + 'media')]" @updatedimg="updatedmedia($event,(keyput + 'media'))" :key="keyput"></media-manager>
            </div> <!-- end offcanvas-body-->
        </div>
        <div class="text-center">
            <div class="d-grid text-center" v-if="value" style="display: block;">
                <img :src="value" alt="image" class="img-fluid rounded" >

            <button @click="$set(loadstart, (keyput + 'media'),  true)" style="margin-top: -60px" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput"  aria-controls="offcanvasScrolling"  class="btn btn-soft-primary">Change Media</button>
            </div>
            <div v-else>
                <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary">Add Media</button>
            </div>
        </div>
    </div>

    <div v-if="type == 'gallery'" :key="keyput + 'media'" >
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
                <div class="row" >
                    <div v-for="val in value" class="col-sm-3" style="height: 200px">
                            <img :src="val.url" alt="image" class="img-fluid img-thumbnail" width="200" height="200">
                            <p class="mb-0">
                                <code>{{val.name}}</code>
                            </p>
                        </div>
                </div>
            <button @click="$set(loadstart, (keyput + 'media'),  true)" style="margin-top: 00px" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput"  aria-controls="offcanvasScrolling"  class="btn btn-soft-primary">Change Media</button>
            </div>
            <div v-else>
                <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary">Add Media</button>
            </div>
        </div>
    </div>

    <div v-if="type == 'textarea'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <textarea :id="keyput" v-model="editorData" class="form-control" maxlength="500" rows="3" placeholder=""></textarea>
    </div>

    <!-- <div v-if="type == 'image'" class="">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <uploadImage @updatedimage="updatedimage" :key="keyput" :prevalue="value"  :keyput="keyput"></uploadImage>
    </div> -->

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
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>
        <multidropdown
            :title="title"
            @updatevalue="updated"
            :prop-value="existingValue"
            :route="route"
            :fetch="true"
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

    <div v-if="type == 'repetable'" class="">
        <template v-for="items in editorData">
            <template v-for="input in inputs">
                <multiput
                    :key="input.key"
                    :keyput="input.key"
                    :label="input.label"
                    :type="input.type"
                    :value="input.value"
                    :tabsProp="input.tabs ? input.tabs : []"
                    :size="input.size"
                    
                    :route="input.route"
                    :multi="false"
                    :existingValue="input.value"
                    :uuid="$uuid.v4()"
                    :mode="mode"
                />
            </template>
        </template>

        <div class="text-center mt-2">
            <button @click="addRepetableBlock()" class="btn btn-success add-column-button ms-1">
                <i class="dripicons-plus"></i>
            </button>
        </div>
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
// import Tcedit from '../tcdit.vue';

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
                }
                // The configuration of the editor.
            }
        };
    },
    methods: {
        updatedmedia($event, ref) {
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
            this.$refs[ref+'btn'].click()
            this.$set(this.loadstart, ref,  false);
            //this.$refs[ref].destroy();

            console.log(this.$refs[ref+'btn'])
        },
        updatedgallery($event, ref) {
            this.$emit('inputed', { 'data': $event, 'key': this.keyput})
            this.$refs[ref+'btn'].click()
            this.$set(this.loadstart, ref,  false);
            //this.$refs[ref].destroy();

            console.log(this.$refs[ref+'btn'])
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
            if (this.keyput == "meta_slug") {
                eventHub.$emit('updateslug', editorData);
            }
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
