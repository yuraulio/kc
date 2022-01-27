<template>
    <div class="mt-3">
        <h5 class="mb-2">{{title}}</h5>
        <div class="row mx-n1 g-0">
            <div v-for="folder in mediaFolders" :key="folder.id" class="col-lg-6" >
            <div class="card m-1 shadow-none border" :style="(selectable ? ((selectedFolder != null && selectedFolder.id == folder.id ? ' border: 1px solid #6658dd !important; cursor: pointer' : ' cursor: pointer ')) : '')" @click="selectFolder(folder)">
                <div class="p-2">
                    <div class="row align-items-center">
                        <div class="col-auto pe-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-secondary rounded">
                            <i class="mdi mdi-folder font-18"></i>
                            </span>
                        </div>
                        </div>
                        <div class="col">
                        <a href="javascript:void(0);" class="text-muted fw-normal">{{folder.name}}</a>
                        <p class="mb-0 font-13">{{ folder.size }} MB</p>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end .p-2-->
            </div>
            <!-- end col -->
            </div>

        </div>
        <!-- end row-->
    </div>
</template>

<script>
export default {
    props: {
        mediaFolders: {},
        title: {},
        selectable: {
            default: false
        }
    },
    data() {
        return {
            selectedFolder: null
        }
    },
    methods: {
        selectFolder(folder) {
            if (!this.selectable) {
                return;
            }
            this.selectedFolder = folder;
            this.$emit('selected', this.selectedFolder.id);
            this.$emit('folder-selected', this.selectedFolder);
            console.log(this.selectedFolder)
        }
    }
}
</script>
