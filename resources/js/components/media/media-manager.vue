<template>
<div class="row">
    <modal name="create-folder-modal" :resizable="true" height="auto" :adaptive="true">
        <div class="row">
            <div class="col-lg-12 p-4">
                <label :for="'foldername'" class="form-label">FolderName</label>
                <input v-model="folderName" type="text" :id="'folderName'" class="form-control" />
                <ul v-if="errors && errors['name']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                    <li class="parsley-required">{{ errors["name"][0] }}</li>
                </ul>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-12 text-center">
                <button @click="addFolder()" type="button" class="btn btn-success waves-effect waves-light m-1" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create
                </button>
                <button @click="$modal.hide('create-folder-modal')" type="button" class="btn btn-light waves-effect waves-light m-1">
                    <i class="fe-x me-1"></i> Cancel
                </button>
            </div>
        </div>
    </modal>

    <modal name="upload-media-modal" :adaptive="true" :resizable="true" height="auto" :scrollable="true">
        <div class="row p-4">
            <div class="d-grid col-lg-12">
                <div class="mb-3">
                    <label for="example-fileinput" class="form-label">Pick a file</label>
                    <input @change="registerFile" type="file" id="example-fileinput" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="alt_text" class="form-label">Set alt text</label>
                    <input v-model="alt_text" type="text" id="alt_text" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">Set link</label>
                    <input v-model="link" type="text" id="link" class="form-control">
                </div>

                <button @click="uploadImgFile()" type="button" class="btn btn-success waves-effect waves-light" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Upload
                </button>
            </div>
        </div>
    </modal>

    <modal name="edit-image-modal" :adaptive="true" :resizable="true" width="92%" height="auto" :scrollable="true" class="mb-0">
        <div class="row p-4">
            <cropperer @edit="imageEdit" @upload="imageAdded" ref="crpr" :prevalue="selectedFile"></cropperer>
        </div>
    </modal>

    <modal name="upload-file-modal" ref="fmodal" :resizable="true" height="auto" :adaptive="true" :scrollable="true">
        <div class="row p-4">
            <div class="d-grid col-lg-12">
                <div class="mb-3">
                    <label for="example-fileinput" class="form-label">Pick a file</label>
                    <input @change="registerFile" type="file" id="example-fileinput" class="form-control">
                </div>

                <button @click="uploadRegFile()" type="button" class="btn btn-success waves-effect waves-light" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Upload
                </button>
            </div>
        </div>
    </modal>

    <modal name="gallery-modal" ref="gmodal" :resizable="true" height="auto" :adaptive="true" :minWidth="1000" :scrollable="true">
        <gallery ref="gals" :images="mediaFiles" :opImage="opImage"></gallery>
    </modal>
    <!-- Right Sidebar -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Left sidebar -->
                <div class="inbox-leftbar">
                    <div class="btn-group d-block mb-2">
                        <button type="button" class="
                  btn btn-success
                  w-100
                  waves-effect waves-light
                  dropdown-toggle
                " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus"></i> Create New
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" @click.prevent="$modal.show('create-folder-modal')"><i class="mdi mdi-folder-plus-outline me-1"></i> Folder</a>
                            <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-file-plus-outline me-1"></i> File</a>
                     <a class="dropdown-item" href="#"><i class="mdi mdi-file-document me-1"></i> Document</a> -->
                            <a class="dropdown-item" href="#" @click.prevent="$modal.show('upload-media-modal')"><i class="mdi mdi-image me-1"></i> Image
                            </a>

                            <a class="dropdown-item" href="#" @click.prevent="$modal.show('upload-file-modal')"><i class="mdi mdi-upload me-1"></i> File</a>
                        </div>
                    </div>
                    <div class="mail-list mt-3">
                        <a href="#" @click.prevent="getFolders(); filesView = false" class="list-group-item border-0 font-14"><i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>Recents</a>
                        <a href="#" @click.prevent="getFiles();filesView = true;" class="list-group-item border-0 font-14"><i class="mdi mdi-file-outline font-18 align-middle me-2"></i>My Files</a>

                        <!-- <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-google-drive font-18 align-middle me-2"></i>Google Drive</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-dropbox font-18 align-middle me-2"></i>Dropbox</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-share-variant font-18 align-middle me-2"></i>Share with me</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Recent</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-star-outline font-18 align-middle me-2"></i>Starred</a> -->
                        <vue-nestable v-model="mediaFolders" :maxDepth="0" class="dd-list">
                            <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                                <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                                    <button @click="
                                        collapse(item);
                                        $forceUpdate();
                                        " v-if="
                                        item.children &&
                                        item.children.length &&
                                        !uncolapsed.includes(item.children[0].id)
                                    ">
                                        <button class="dd-collapse" data-action="collapse" type="button">Collapse</button>
                                    </button>
                                    <button @click="uncollapse(item)" v-if="
                                        item.children &&
                                        item.children.length &&
                                        uncolapsed.includes(item.children[0].id)
                                    ">
                                        <i class="mdi mdi-minus font-18"></i>
                                    </button>
                                    <div @click="selectedFolder = item; getFolders(item.id)" class="dd-handle">
                                        <i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>
                                        {{ item.name }}
                                        <i @click.stop="deleteFolder(item)" class="fa fa-times ms-2 float-end mt-1" aria-hidden="true"></i>
                                    </div>
                                </li>
                            </vue-nestable-handle>
                        </vue-nestable>
                    </div>

                    <!-- <div class="mt-5">
              <h4>
                <span class="badge rounded-pill p-1 px-2 badge-soft-secondary"
                  >FREE</span
                >
              </h4>
              <h6 class="text-uppercase mt-3">Storage</h6>
              <div class="progress my-2 progress-sm">
                <div
                  class="progress-bar progress-lg bg-success"
                  role="progressbar"
                  style="width: 46%"
                  aria-valuenow="46"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
              <p class="text-muted font-12 mb-0">7.02 GB (46%) of 15 GB used</p>
            </div> -->
                </div>
                <!-- End Left sidebar -->
                <div class="inbox-rightbar">
                    <div class="d-md-flex justify-content-between align-items-center">
                        <div class="search-bar">
                            <div class="position-relative">
                                <input v-model="searchFilter" @keyup.enter.prevent="getFolders(folderId)" type="text" class="form-control form-control-light" placeholder="Search files..." />
                                <span class="mdi mdi-magnify"></span>
                            </div>
                        </div>
                        <div @click.prevent="onlyParent = !onlyParent; getFiles()" :key="onlyParent + 'parent'" class="form-check form-switch mb-1" style="display: inline-block; cursor: pointer">
                            <input :id="'toginput'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="'toginput'" :checked="onlyParent">
                            <label class="form-check-label" for="light-mode-check">Show Only Parent Images</label>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <button @click.prevent="view = 'list'" class="btn btn-sm btn-white">
                                <i class="mdi mdi-format-list-bulleted"></i>
                            </button>
                            <!-- <button
                  @click.prevent="view = 'cards'"
                  class="btn btn-sm btn-white"
                >
                  <i class="mdi mdi-view-grid"></i>
                </button> -->
                        </div>
                    </div>

                    <div v-if="loading">
                        <div style="margin-top: 150px" class="text-center">
                            <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat>
                        </div>
                    </div>
                    <div v-if="!loading && loadstart">
                        <div v-if="!filesView">
                            <!--
                            <folders :selectable="true" @selected="getFolders($event)" v-if="inMediaFolders && inMediaFolders.length && !loading" :mediaFolders="inMediaFolders" title="Quick Access"></folders>
                            -->
                            <!-- end .mt-3-->

                            <files :key="view" :view="view" v-if="!loading" :mediaFiles="mediaFiles" @selected="userSelectedFiles" @delete="deleteFile" @open="openFile"></files>
                        </div>
                        <div v-else>
                            <files :key="view" :view="view" v-if="!loading" :mediaFiles="mediaFiles" @selected="userSelectedFiles" @delete="deleteFile" @open="openFile"></files>
                        </div>
                        <!-- end .mt-3-->
                    </div>
                </div>
                <!-- end inbox-rightbar-->
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end card -->
    </div>
    <!-- end Col -->
</div>
</template>

<script>
import uploadImage from "../inputs/upload-image.vue";
import folders from "./folders.vue";
import files from "./files.vue";
import cropperer from "./cropperer.vue";
import mediaMixin from "./mediamixin";
import "./mediastyle.css";
import Gallery from "./gallery.vue";
export default {
    props: {
        loadstart: {
            default: true
        },
        mode: {
            default: 'single'
        }
    },
    mixins: [mediaMixin],
    components: {
        uploadImage,
        folders,
        files,
        cropperer,
        Gallery
    },
    data() {
        return {
            regFile: null,
            alt_text: "",
            link: "",
            onlyParent: true,
            filesView: false,
            opImage: null,
            selectedFile: null,
            folderId: null,
            searchFilter: "",
            view: "list",
            uncolapsed: [],
            loading: false,
            folderName: "",
            errors: {},
            mediaFolders: [],
            inMediaFolders: [],
            mediaFiles: [],
            selectedFolder: null,
            currentImage: null,
            sizes: [

            ],
        };
    },
    methods: {
        registerFile($event) {
            this.regFile = $event.target.files[0];
        },

    },
    mounted() {
        if (this.loadstart) {
            // console.log('setted');
            this.getFolders();
        }
    },
    beforeDestroy() {
        // console.log('unsetted');
    },
    watch: {
        loadstart() {
            // console.log("watching load")
            if (this.loadstart == true) {
                this.getFolders();
            }
        }
    }
};
</script>

<style>
</style>
