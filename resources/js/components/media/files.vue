<template>
<div>
    <div v-if="view == 'list'" class="mt-3" style="">
        <h5 class="mb-3">Files</h5>
        <div class="d-flex" style="">
            <div class="d-flex" style="align-items: end; width: 100%;">
                <table class="table table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Image</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Pages</th>
                            <th class="border-0">Created</th>
                            <th class="border-0">Size</th>
                            <th class="border-0" style="width: 80px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in mediaFiles" :key="file.id">
                            <td @click="openFile(file)" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer;">
                                <span v-if="file.extension.toLowerCase() == 'pdf'" class="bg-light text-secondary rounded">
                                    <i class="mdi mdi-file-pdf-outline font-28"></i>
                                </span>
                                <img v-else-if="imageExtensions.includes(file.extension.toLowerCase())" :src="file.url + '?key=' + Math.random().toString().substr(2, 8)" alt="image" class="img-fluid avatar-sm rounded mt-2" style="width: 100px; height: auto;" />

                                <span v-else class="bg-light text-secondary rounded">
                                    <i class="mdi mdi-file font-28"></i>
                                </span>

                            </td>
                            <td class="text-truncate">
                                <span class="ms-2 fw-normal" :title="file.name">{{file.name}}</span>
                            </td>
                            <td>
                                {{ file.pages_count }}
                            </td>
                            <td>
                                <p class="mb-0">
                                    {{ file.created_at }}
                                </p>
                            </td>
                            <td>
                                {{ parseFloat(file.size * 0.000001).toFixed(1) }} MB
                            </td>
                            <td>
                                <div class="btn-group dropstart">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu" style="">
                                        <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a> -->
                                        <a class="dropdown-item" href="#" @click.prevent="editFile(file)"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Edit</a>
                                        <a class="dropdown-item" href="#" @click.prevent="moveFile(file)">
                                            <i class="mdi mdi-file-move me-2 text-muted vertical-middle"></i>
                                            Move
                                        </a>
                                        <a class="dropdown-item" target="_blank" :href="file.url" :download="file.name"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                        <a class="dropdown-item" href="#" @click.prevent="deleteFile(file)"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <infinite-loading @infinite="infiniteHandler">
            <div slot="spinner">Loading...</div>
            <div slot="no-more"></div>
            <div slot="no-results"></div>
        </infinite-loading>
    </div>
    <div v-if="view == 'cards'" class="mt-3">
        <h5 class="mb-3">Files</h5>
        <div class="row">
            <div v-for="file in mediaFiles" :key="file.id + '-card'" class="col-3">
                <div class="card product-box">
                    <div class="card-body" style="padding: 0.5rem; height:200px">
                        <div class="product-action">
                            <div class="btn-group dropdown">
                                <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" @click.prevent="editFile(file)"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Edit</a>
                                    <a class="dropdown-item" href="#" @click.prevent="moveFile(file)">
                                        <i class="mdi mdi-file-move me-2 text-muted vertical-middle"></i>Move
                                    </a>
                                    <a class="dropdown-item" target="_blank" :href="file.url" :download="file.name"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                    <a class="dropdown-item" href="#" @click.prevent="deleteFile(file)"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light" style="text-align: center; min-width: 100px">
                            <img :src="file.url" alt="product-pic" class="img-fluid rounded" style="width:100px, object-fit: dover" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <infinite-loading @infinite="infiniteHandler">
            <div slot="spinner">Loading...</div>
            <div slot="no-more"></div>
            <div slot="no-results"></div>
        </infinite-loading>
    </div>
</div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";

export default {
    components: {
        InfiniteLoading
    },
    props: {
        mediaFiles: {},
        view: {
            default: "list",
        },
        imageExtensions: [],
    },
    data() {
        return {
            page: 2,
        };
    },
    methods: {
        editFile(file) {
            this.$emit("selected", file);
        },
        moveFile(file) {
            this.$emit("move", file);
        },
        deleteFile(file) {
            this.$emit("delete", file);
        },
        openFile(file) {
            this.$emit("open", file);
        },
        infiniteHandler($state) {
            axios
                .get("/api/media_manager/files", {
                    params: {
                        folder_id: this.$parent.folderId,
                        filter: this.$parent.searchFilter,
                        parent: this.$parent.onlyParent,
                        page: this.page,
                    },
                })
                .then((response) => {
                    if (response.data.data.length) {
                        this.page += 1;
                        this.$parent.mediaFiles.push(...response.data.data);
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.$parent.errors = error.response.data.errors;
                });
        },
    },
};
</script>

<style scoped>
.card {
    transition: box-shadow 0.5s;
    box-shadow: 0 0 11px rgb(33 33 33 / 25%);
}

.card:hover {
    transition: box-shadow 0.5s;
    scale: 1.1;
}

table {
    table-layout: fixed;
    width: 100%;
}
td, th {

    word-wrap: break-word;
}
</style>
