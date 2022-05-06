<template>
<div class="row media-manager">
    <modal name="create-folder-modal" :resizable="true" height="auto" :adaptive="true">
        <div class="row">
            <div class="col-lg-12 p-4">
                <label :for="'foldername'" class="form-label">Folder Name</label>
                <input v-model="folderName" type="text" :id="'folderName'" class="form-control" />
                <ul v-if="errors && errors['name']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                    <li class="parsley-required">{{ errors["name"][0] }}</li>
                </ul>

                <label class="form-label mt-3">Pick a parent folder</label>

                <vue-nestable v-model="mediaFolders" class="dd-list mb-3">
                    <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                        <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                            <button
                                @click="collapse(item); $forceUpdate();"
                                v-if="item.children && item.children.length && !uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-plus font-18"></i>
                            </button>
                            <button
                                @click="uncollapse(item)"
                                v-if="item.children && item.children.length && uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-minus font-18"></i>
                            </button>
                            <div @click="move_file_to = item;" :class="'dd-handle ' + (item == move_file_to ? 'selected-folder ' : ' ')">
                                <i class="mdi mdi-folder-outline font-18 align-middle me-"></i>
                                {{ item.name }}
                            </div>
                        </li>
                    </vue-nestable-handle>
                </vue-nestable>

                <template v-if="folder_error">
                    <p class="text-danger">{{folder_error}}</p>
                </template>

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

                <label class="form-label">Pick a folder</label>

                <vue-nestable v-model="mediaFolders" class="dd-list mb-3">
                    <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                        <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                            <button
                                @click="collapse(item); $forceUpdate();"
                                v-if="item.children && item.children.length && !uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-plus font-18"></i>
                            </button>
                            <button
                                @click="uncollapse(item)"
                                v-if="item.children && item.children.length && uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-minus font-18"></i>
                            </button>
                            <div @click="move_file_to = item;" :class="'dd-handle ' + (item == move_file_to ? 'selected-folder ' : ' ')">
                                <i class="mdi mdi-folder-outline font-18 align-middle me-"></i>
                                {{ item.name }}
                            </div>
                        </li>
                    </vue-nestable-handle>
                </vue-nestable>

                <template v-if="upload_error">
                    <p class="text-danger">{{upload_error}}</p>
                </template>

                <button @click="uploadImgFile()" type="button" class="btn btn-success waves-effect waves-light" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Upload
                </button>
            </div>
        </div>
    </modal>

    <modal name="edit-image-modal" :adaptive="true" :resizable="true" width="70%" height="70%" :scrollable="true" class="mb-0">
        <div class="row p-4">
            <cropperer @edit="imageEdit" @upload="imageAdded" ref="crpr" :prevalue="selectedFile" :imageKey="imageKey"></cropperer>
        </div>
    </modal>

    <modal name="file_move_modal" :adaptive="true" :resizable="true" height="auto" :scrollable="true" class="mb-0">
        <div class="row p-4">
            <div class="col-12">
                <h5>Move file {{ file_to_move ? file_to_move.name : "" }}</h5>
                <p>Select new folder:</p>

                <vue-nestable v-model="mediaFolders" class="dd-list mb-2">
                    <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                        <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                            <button
                                @click="collapse(item); $forceUpdate();"
                                v-if="item.children && item.children.length && !uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-plus font-18"></i>
                            </button>
                            <button
                                @click="uncollapse(item)"
                                v-if="item.children && item.children.length && uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-minus font-18"></i>
                            </button>
                            <div @click="move_file_to = item;" :class="'dd-handle ' + (item == move_file_to ? 'selected-folder ' : ' ') + (item.id == file_to_move.folder_id ? 'original-floder' : '')">
                                <i class="mdi mdi-folder-outline font-18 align-middle me-"></i>
                                {{ item.name }}
                            </div>
                        </li>
                    </vue-nestable-handle>
                </vue-nestable>

                <p class="mb-2">
                    <span class="d-inline-block" style="width: 40px;">From: </span>{{file_to_move ? file_to_move.path.replace("/" + file_to_move.name, "") : "" }}
                    <br>
                    <span class="d-inline-block" style="width: 40px;">To: </span>{{ move_file_to ? move_file_to.path : '' }}
                </p>

                <button @click="moveFile()" type="button" class="btn btn-success waves-effect waves-light mb-3 w-100" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Move
                </button>

                <div class="alert alert-warning" role="alert">
                    Pages and files will update in the background in a few minutes.
                </div>
            </div>
        </div>
    </modal>

    <modal name="edit-folder-modal" :adaptive="true" :resizable="true" height="auto" :scrollable="true" class="mb-0">
        <div class="row p-4">
            <div class="d-grid col-lg-12">
                <div class="mb-3">
                    <label for="alt_text" class="form-label">Change folder name</label>
                    <input v-model="folder_edit_name" type="text" id="alt_text" class="form-control">
                </div>

                <button @click="renameFolder()" type="button" class="btn btn-success waves-effect waves-light mb-3" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Edit
                </button>

                <div class="alert alert-warning" role="alert">
                    Pages and files will update in the background in a few minutes.
                </div>
            </div>
        </div>
    </modal>

    <modal name="upload-file-modal" ref="fmodal" :resizable="true" height="auto" :adaptive="true" :scrollable="true">
        <div class="row p-4">
            <div class="d-grid col-lg-12">
                <div class="mb-3">
                    <label for="example-fileinput" class="form-label">Pick a file</label>
                    <input @change="registerFile" type="file" id="example-fileinput" class="form-control">
                </div>

                <label class="form-label">Pick a folder</label>

                <vue-nestable v-model="mediaFolders" class="dd-list mb-3">
                    <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                        <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                            <button
                                @click="collapse(item); $forceUpdate();"
                                v-if="item.children && item.children.length && !uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-plus font-18"></i>
                            </button>
                            <button
                                @click="uncollapse(item)"
                                v-if="item.children && item.children.length && uncolapsed.includes(item.children[0].id)"
                                class="collapse-button"
                            >
                                <i class="mdi mdi-minus font-18"></i>
                            </button>
                            <div @click="move_file_to = item;" :class="'dd-handle ' + (item == move_file_to ? 'selected-folder ' : ' ')">
                                <i class="mdi mdi-folder-outline font-18 align-middle me-"></i>
                                {{ item.name }}
                            </div>
                        </li>
                    </vue-nestable-handle>
                </vue-nestable>

                <template v-if="upload_error">
                    <p class="text-danger">{{upload_error}}</p>
                </template>

                <button @click="uploadRegFile()" type="button" class="btn btn-success waves-effect waves-light" :disabled="loading">
                    <i v-if="!loading" class="fe-check-circle me-1"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                    Upload
                </button>
            </div>
        </div>
    </modal>

    <modal name="gallery-modal" ref="gmodal" :resizable="true" height="auto" :adaptive="true" :minWidth="1000" :scrollable="true">
        <gallery ref="gals" :images="mediaFiles" :opImage="opImage" :imageExtensions="imageExtensions"></gallery>
    </modal>
    <!-- Right Sidebar -->
    <div class="col-12">
        <div class="card">
            <div class="card-body p-2">
                <!-- Left sidebar -->
                <div class="inbox-leftbar p-0">
                    <div class="btn-group d-block mb-2 text-center">
                        <button class="btn btn-sm btn-info" @click.prevent="$modal.show('create-folder-modal'); move_file_to = null;">
                            <i class="mdi mdi-plus"></i> <i class="mdi mdi-folder-plus-outline me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" @click.prevent="$modal.show('upload-media-modal'); move_file_to = null;">
                            <i class="mdi mdi-plus"></i> <i class="mdi mdi-image me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-primary" @click.prevent="$modal.show('upload-file-modal'); move_file_to = null;">
                            <i class="mdi mdi-plus"></i> <i class="mdi mdi-upload me-1"></i>
                        </button>
                    </div>
                    <div class="mail-list mt-3">
                        <a href="#" @click.prevent="getFolders(); filesView = false; folderId=null;" class="list-group-item border-0 font-14"><i class="mdi mdi-folder-outline font-18 align-middle me-1"></i>Recent Updated</a>
                        <a href="#" @click.prevent="getFiles(); filesView = true; folderId=null;" class="list-group-item border-0 font-14"><i class="mdi mdi-folder-outline font-18 align-middle me-1"></i> All Files</a>

                        <vue-nestable ref="folders" v-model="mediaFolders" :maxDepth="0" class="dd-list">
                            <vue-nestable-handle slot-scope="{ item, isChild }" :item="item">
                                <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                                    <button
                                        @click="collapse(item)"
                                        v-if="item.children && item.children.length && !uncolapsed.includes(item.children[0].id)"
                                        class="collapse-button"
                                    >
                                        <i class="mdi mdi-plus font-18"></i>
                                    </button>
                                    <button
                                        @click="uncollapse(item)"
                                        v-if="item.children && item.children.length && uncolapsed.includes(item.children[0].id)"
                                        class="collapse-button"
                                    >
                                        <i class="mdi mdi-minus font-18"></i>
                                    </button>
                                    <div @click="selectedFolder = item; getFolders(item.id)" :class="'dd-handle ' + (item.id == folderId ? 'selected-folder' : '')">

                                        <div class="row g-0 ps-1">
                                            <div class="col-auto p-0">
                                                <i class="mdi mdi-folder-outline font-18 align-middle" style="margin-right: 5px;"></i>
                                            </div>
                                            <div class="col p-0" style="width: 10px">
                                                <div>
                                                    <div class="text-truncate">
                                                        <span :title="item.name">{{ item.name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto p-0">
                                                <i @click.stop="deleteFolder(item)" class="fa fa-times ms-1  mt-1" aria-hidden="true"></i>
                                                <i @click.stop="renameFolderModal(item)" class="fas fa-edit ms-1  mt-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </vue-nestable-handle>
                        </vue-nestable>
                    </div>
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
                        <div class="mt-2 mt-md-0">
                            <div @click.prevent="onlyParent = !onlyParent; getFiles(); folderId = null;" :key="onlyParent + 'parent'" class="form-check form-switch mb-1" style="display: inline-block; cursor: pointer">
                                <button @click.prevent="view = 'list'" class="btn btn-sm btn-white">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            </div>
                            <button @click.prevent="view = 'list'" class="btn btn-sm btn-white">
                                <i class="mdi mdi-format-list-bulleted"></i>
                            </button>
                            <button @click.prevent="view = 'cards'" class="btn btn-sm btn-white">
                                <i class="mdi mdi-view-grid"></i>
                            </button>
                        </div>
                    </div>

                    <div v-if="loading">
                        <div style="margin-top: 150px" class="text-center">
                            <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat>
                        </div>
                    </div>
                    <div v-if="!loading && loadstart">
                        <files :key="view" :view="view" v-if="!loading" :mediaFiles="mediaFiles" @selected="userSelectedFiles" @delete="deleteFile" @open="openFile" @move="openMoveModal" :imageExtensions="imageExtensions"></files>
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
            default: null
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
            folder_edit_name: null,
            folder_edit_id: null,
            file_to_move: null,
            move_file_to: null,
            sizes: [],
            upload_error: null,
            folder_error: null,
            imageKey: Math.random().toString().substr(2, 8),
            imageExtensions: [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "webp",
                "svg",
            ]
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
.media-manager .dd-handle {
    padding: 0px 5px!important;
    border: 1px solid transparent!important;
}
.media-manager .dd-handle:hover i {
    color: #6c757d;
}
.media-manager .dd-item {
    min-height: 30px!important;
}
.media-manager .collapse-button {
    text-indent: 0px!important;
    transform: translateY(-4px);
    width: 23px!important;
}
.media-manager .selected-folder {
    border: 1px solid #6658dd!important;
}
.media-manager .original-floder {
    border: 1px solid #1abc9c!important;
}
.media-manager ol.nestable-list {
    padding-left: 10px;
}
</style>

<style scoped>
@media (max-width: 992px) {
    .inbox-leftbar {
        width: 100%;
        float: none;
        padding: 0 20px;
    }
    .inbox-rightbar {
        padding-top: 40px;
        margin: 0;
        border: 0;
        padding-left: 0;
    }
    .message-list li .col-mail-1 .checkbox-wrapper-mail {
        margin-left: 0;
    }
}

.dd-handle {
    padding-left: 22px!important;
}
</style>
