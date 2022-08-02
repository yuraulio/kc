<template>
<div :class="'col-lg-6  mt-2'">

    <div class="text-editor-input">
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
                font_css: '/theme/assets/css/editor.css'
            }"
        ></editor>
    </div>



</div>
</template>

<script>


import Editor from '@tinymce/tinymce-vue'

export default {
    components: {

        Editor,
    },
    props: {
        inputname: String,
        type: {
            required: false,
            type: String
        },
        keyput: {
            required: false
        },
        label: {},
        value: {},
        size: {
            type: String,
            default: ''
        },
        width: {
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
        imageVersion: null,
        hideAltText: false,
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

            consol.log('gdsfg');

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
            $('input[name='+ this.inputname +']').val(this.editorData);
            this.$emit('inputed', { 'data': this.editorData, 'key': this.keyput })
        },
        "value": function() {
            this.editorData = this.editorData;
        }
    },
    mounted() {
        console.log('has mounted')
        if (this.value) {
            this.editorData = this.editorData;
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
