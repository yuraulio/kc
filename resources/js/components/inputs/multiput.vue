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
                <button @click="$set(loadstart, (keyput + 'media'),  false)" :ref="(keyput + 'mediabtn')" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->

            <div class="offcanvas-body" style="padding: 0px !important">
                <media-manager v-if="loadstart[(keyput + 'media')]" mode="single" :startingImage="value" :loadstart="loadstart[(keyput + 'media')]" @updatedimg="updatedmedia($event,(keyput + 'media'))" :key="keyput"></media-manager>
            </div> <!-- end offcanvas-body-->
        </div>
        <div class="text-center">
            <div class="d-grid text-center" v-if="value">

                <img @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" :src="value.url + '?i=' + (Math.random() * 100000)" alt="image" class="img-fluid rounded" >

                <i @click="removeImage()" class="mdi mdi-delete text-muted vertical-middle d-block fs-4 mt-1"></i>

                <div class="mt-2">
                    <label class="form-label float-start">Alt Text</label>
                    <input type="text" v-model="value.alt_text" class="form-control">
                </div>
            </div>
            <div v-else>
                <i @click="$set(loadstart, (keyput + 'media'),  true)" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" class="text-muted dripicons-photo d-none image-input-icon" style="font-size: 100px;"></i>
                <button @click="$set(loadstart, (keyput + 'media'),  true)" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#mediaCanvas' + keyput" aria-controls="offcanvasScrolling"  class="btn btn-soft-primary image-input-button">
                    <template v-if="imageEdit">
                        Edit Media
                    </template>
                    <template v-else>
                        Add Media
                    </template>
                </button>
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

    <div v-if="type == 'text_editor'" class="text-editor-input">
        <label v-if="label" :for="keyput" class="form-label">{{ label }}</label>

        <editor 
            :height="300" 
            :id="keyput" 
            v-model="editorData"
            :api-key="tinymce"
            :init="{
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                toolbar: 'fullscreen styles | undo redo | h1 h2 h3 h4 h5 h6 | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview print | insertfile image media link anchor codesample | ltr rtl',
                toolbar_sticky: true,
                toolbar_mode: 'wrap',
                height: 300,
            }"
        ></editor>
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
import contentComponent from './content-components.vue'
import multidropdown from './multidropdown.vue'
import MediaManager from '../media/media-manager.vue';
import Editor from '@tinymce/tinymce-vue'

export default {
    components: {
        uploadImage,
        contentComponent,
        multidropdown,
        MediaManager,
        Editor,
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
        imageEdit:false,
    },
    data() {
        return {
            loadstart: {},
            editorData: this.value,
            tinymce: process.env.MIX_PUSHER_TINYMCE,
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
    },
    beforeDestroy() {
    },
}
</script>

<style>
    .tox-statusbar__branding {
        display: none;
    }

    .tox.tox-tinymce, .tox.tox-tinymce div {
        border: 0px!important;
    }
    .tox-toolbar, .tox-menubar {
        background: none!important;
        background-color: #fff!important;
    }
    /*
    .page-edit-simple .tox.tox-tinymce:not(:hover) .tox-editor-header {
        visibility: hidden;
    }
    */
    .tox-tinymce--toolbar-sticky-on .tox-editor-header {
        top: 75px!important;
    }
    .tox-fullscreen .navbar-custom {
        display: none;
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
