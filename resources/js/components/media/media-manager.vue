<template>
<div class="row">
    <modal name="create-folder-modal" :resizable="true" height="auto" :adaptive="true">
        <div class="row">
            <div class="col-lg-12 p-4">
                <label :for="'foldername'" class="form-label">FolderName</label>
                <input v-model="folderName" type="text" :id="'folderName'" class="form-control">
                <ul v-if="errors && errors['name']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false"><li class="parsley-required">{{errors['name'][0]}}</li></ul>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-12 text-center">
                <button @click="addFolder()" type="button" class="btn btn-success waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                <button @click="$modal.hide('create-folder-modal')" type="button" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div>
    </modal>
    <modal name="upload-media-modal" :resizable="true" height="auto" :adaptive="true">
        <div class="row p-4">
            <div class="col-lg-12">
                <folders :mediaFolders="mediaFolders" :selectable="true" @folder-selected=selectedFolders($event) title="Select Folder"></folders>
                <h5 class="mb-2 mt-3">Upload Media</h5>
                <div class="card">
                    <upload-image
                        :keyput="'mediaManager'"
                        :direct="false"
                        :prevalue="currentImage ? currentImage.url : null"
                        @inputed="imageAdded"
                    >
                    </upload-image>
                </div>

                <div v-if="currentImage" class="form-group">

                <h5 class="mb-2 mt-3">Sizes to upload</h5>

                <div>
                    <div v-for="size in sizes" :key="size.key"  class="form-check form-switch mb-1" style="display: block; cursor: pointer">
                        <input :key="size.key + 'on'" @click="size.enabled = !size.enabled" :id="size.key + 'input'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="size.key + 'input'" :checked="size.enabled">
                        <label class="form-check-label" for="light-mode-check">{{ size.title }}</label>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </modal>
   <!-- Right Sidebar -->
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <!-- Left sidebar -->
            <div class="inbox-leftbar">
               <div class="btn-group d-block mb-2">
                  <button type="button" class="btn btn-success w-100 waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-plus"></i> Create New</button>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="#" @click.prevent="$modal.show('create-folder-modal')"><i class="mdi mdi-folder-plus-outline me-1"></i> Folder</a>
                     <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-file-plus-outline me-1"></i> File</a>
                     <a class="dropdown-item" href="#"><i class="mdi mdi-file-document me-1"></i> Document</a> -->
                     <a class="dropdown-item" href="#" @click.prevent="$modal.show('upload-media-modal')"><i class="mdi mdi-upload me-1"></i> Upload</a>
                  </div>
               </div>
               <div class="mail-list mt-3">
                  <a href="#" @click.prevent="getFolders()" class="list-group-item border-0 font-14"><i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>My Folders</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-file-outline font-18 align-middle me-2"></i>My Files</a>



                  <!-- <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-google-drive font-18 align-middle me-2"></i>Google Drive</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-dropbox font-18 align-middle me-2"></i>Dropbox</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-share-variant font-18 align-middle me-2"></i>Share with me</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Recent</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-star-outline font-18 align-middle me-2"></i>Starred</a> -->
                <vue-nestable v-model="mediaFolders"
                    :maxDepth="0"
                    class="dd-list"
                  >

                    <vue-nestable-handle
                    slot-scope="{ item, isChild }"
                    :item="item">

                    <li :key="item.id + uncolapsed.length" v-show="!isChild || uncolapsed.includes(item.id)" class="dd-item" :data-id="item.id">
                        <button @click="collapse(item); $forceUpdate()" v-if="item.children.length && !uncolapsed.includes(item.children[0].id)" ><i class="mdi mdi-plus font-18"></i></button>
                        <button @click="uncollapse(item)" v-if="item.children.length && uncolapsed.includes(item.children[0].id)"><i class="mdi mdi-minus font-18"></i></button>
                        <div @click="getFolders(item.id)" class="dd-handle">
                            <i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>{{item.name}}
                        </div>
                    </li>
                    </vue-nestable-handle>
                </vue-nestable>
               </div>

               <div class="mt-5">
                  <h4><span class="badge rounded-pill p-1 px-2 badge-soft-secondary">FREE</span></h4>
                  <h6 class="text-uppercase mt-3">Storage</h6>
                  <div class="progress my-2 progress-sm">
                     <div class="progress-bar progress-lg bg-success" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <p class="text-muted font-12 mb-0">7.02 GB (46%) of 15 GB used</p>
               </div>
            </div>
            <!-- End Left sidebar -->
            <div class="inbox-rightbar">

               <div class="d-md-flex justify-content-between align-items-center">
                  <form class="search-bar">
                     <div class="position-relative">
                        <input type="text" class="form-control form-control-light" placeholder="Search files...">
                        <span class="mdi mdi-magnify"></span>
                     </div>
                  </form>
                  <div class="mt-2 mt-md-0">
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-format-list-bulleted"></i></button>
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-view-grid"></i></button>
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-information-outline"></i></button>
                  </div>
               </div>

               <div v-if="loading">
                    <div style="margin-top: 150px" class="text-center">
                        <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
                    </div>
                </div>
                <div v-else>
                    <folders :selectable="true" @selected="getFolders($event)" v-if="inMediaFolders && inMediaFolders.length && !loading" :mediaFolders="inMediaFolders" title="Quick Access"></folders>
                    <!-- end .mt-3-->
                    <files v-if="!loading" :mediaFiles="mediaFiles"></files>
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
import uploadImage from '../inputs/upload-image.vue';
import folders from './folders.vue'
import files from './files.vue'

export default {
  components: { uploadImage, folders, files },
    data() {
        return {
            uncolapsed: [],
            loading: false,
            folderName: '',
            errors: {},
            mediaFolders: [],
            inMediaFolders: [],
            mediaFiles: [],
            selectedFolder: null,
            currentImage: null,
            sizes: [
                /* {
                    key: 'full_size',
                    enabled: true,
                    title: 'Full size (no resizing)'
                }, */
                {
                    key: 'large_size',
                    enabled: true,
                    title: 'Large - 3840 x 2160px'
                },
                {
                    key: 'medium_size',
                    enabled: true,
                    title: 'Medium - 680 x 320px'
                },
                {
                    key: 'thumbnail_size',
                    enabled: true,
                    title: 'Thumbnail - 150 x 150px'
                }
            ]
        }
    },
    methods: {
        collapse(item) {
            item.children.forEach((el) => {
                this.uncolapsed.push(el.id);
            })
        },
        uncollapse(item) {
            item.children.forEach((el) => {
                this.uncolapsed.splice(this.uncolapsed.indexOf(el.id), 1);
            })
        },
        selectedFolders($event) {
            this.selectedFolder = $event;
        },
        imageAdded($event) {
            console.log($event)
            this.currentImage = $event;
            var formData = new FormData();
            var imagefile = $event;
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            console.log('imgfile', imagefile)
            if (imagefile.file) {
                formData.append("file", imagefile.file);
                axios.post('/api/media_manager/upload_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    console.log(response.data)
                    this.selectedFolder = null;
                    console.log(response)
                })
                .catch((error) => {
                    console.log(error)
                })
            }
        },
        addFolder() {
            this.errors = null;
            this.loading = true;
            axios
            .post('/api/media_manager',
                {
                    name: this.folderName,
                }
            )
            .then((response) => {
                if (response.status == 201 || response.status == 200){
                    this.$toast.success('Created Successfully!')
                }
                this.mediaFolders.push(response.data.data)
                this.folderName = '';
                this.loading = false;
                this.$modal.hide('create-folder-modal')
            })
            .catch((error) => {
                console.log(error)
                this.errors = error.response.data.errors;
                this.loading = false;
            });
        },
        getFolders(folderId) {
            this.errors = null;
            this.loading = true;
            axios
            .get('/api/media_manager', {
                params: {
                    folder_id: folderId
                }
            })
            .then((response) => {
                console.log(response.data);
                if (!folderId) {
                    this.mediaFolders = response.data.data;
                }

                this.inMediaFolders = response.data.data;

            })
            .catch((error) => {
                console.log(error)
                this.errors = error.response.data.errors;
                this.loading = false;
            });

            this.getFiles(folderId);
        },
        getFiles(folderId) {
            this.errors = null;
            this.loading = true;
            axios
            .get('/api/media_manager/files', {
                params: {
                    folder_id: folderId
                }
            })
            .then((response) => {
                console.log(response.data);
                this.mediaFiles = response.data.data;
                this.loading = false;
            })
            .catch((error) => {
                console.log(error)
                this.errors = error.response.data.errors;
                this.loading = false;
            });


        }
    },
    mounted() {
        this.getFolders();

    }
}
</script>

<style>
/*
* Style for nestable
*/
.nestable {
  position: relative;
}
.nestable-rtl {
  direction: rtl;
}
.nestable .nestable-list {
  margin: 0;
  padding: 0 0 0 40px;
  list-style-type: none;
}
.nestable-rtl .nestable-list {
  padding: 0 40px 0 0;
}
.nestable > .nestable-list {
  padding: 0;
}
.nestable-item,
.nestable-item-copy {
  margin: 10px 0 0;
}
.nestable-item:first-child,
.nestable-item-copy:first-child {
  margin-top: 0;
}
.nestable-item .nestable-list,
.nestable-item-copy .nestable-list {
  margin-top: 10px;
}
.nestable-item {
  position: relative;
}
.nestable-item.is-dragging .nestable-list {
  pointer-events: none;
}
.nestable-item.is-dragging * {
  opacity: 0;
  filter: alpha(opacity=0);
}
.nestable-item.is-dragging:before {
  content: ' ';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(106, 127, 233, 0.274);
  border: 1px dashed rgb(73, 100, 241);
  -webkit-border-radius: 5px;
  border-radius: 5px;
}
.nestable-drag-layer {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 100;
  pointer-events: none;
}
.nestable-rtl .nestable-drag-layer {
  left: auto;
  right: 0;
}
.nestable-drag-layer > .nestable-list {
  position: absolute;
  top: 0;
  left: 0;
  padding: 0;
  background-color: rgba(106, 127, 233, 0.274);
}
.nestable-rtl .nestable-drag-layer > .nestable-list {
  padding: 0;
}
.nestable [draggable="true"] {
  cursor: move;
}
.nestable-handle {
  display: inline;
}
.dd {
    position: relative;
    display: block;
    margin: 0;
    padding: 0;
    max-width: 600px;
    list-style: none;
    font-size: 13px;
    line-height: 20px;
}
.dd-list {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    list-style: none;
}

dl, ol, ul {
    margin-top: 0;
    margin-bottom: 1rem;
}
ol, ul {
    padding-left: 2rem;
}
*, ::after, ::before {
    box-sizing: border-box;
}
ol {
    display: block;
    list-style-type: decimal;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}
.dd-empty, .dd-item, .dd-placeholder {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    min-height: 20px;
    font-size: 13px;
    line-height: 20px;
}

*, ::after, ::before {
    box-sizing: border-box;
}
li {
    display: list-item;
    text-align: -webkit-match-parent;
}
.dd-list .dd-item button {
    height: 36px;
    font-size: 17px;
    margin: 0;
    color: #98a6ad;
    width: 36px;
}

[type=button]:not(:disabled), [type=reset]:not(:disabled), [type=submit]:not(:disabled), button:not(:disabled) {
    cursor: pointer;
}
.dd-item>button {
    position: relative;
    cursor: pointer;
    float: left;
    width: 25px;
    height: 20px;
    margin: 5px 0;
    padding: 0;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    background: 0 0;
    font-size: 12px;
    line-height: 1;
    text-align: center;
    font-weight: 700;
}
.dd-list .dd-item button {
    height: 36px;
    font-size: 17px;
    margin: 0;
    color: #98a6ad;
    width: 36px;
}

[type=button]:not(:disabled), [type=reset]:not(:disabled), [type=submit]:not(:disabled), button:not(:disabled) {
    cursor: pointer;
}
.dd-item>button {
    position: relative;
    cursor: pointer;
    float: left;
    width: 25px;
    height: 20px;
    margin: 5px 0;
    padding: 0;
    text-indent: 1%;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    background: 0 0;
    font-size: 12px;
    line-height: 1;
    text-align: center;
    font-weight: 700;
}
</style>
