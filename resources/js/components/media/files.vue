<template>
<div>
    <div v-if="view == 'list'" class="mt-3">
        <h5 class="mb-3">Files</h5>
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">Name</th>
                        <th class="border-0">Pages</th>
                        <th class="border-0">Last Modified</th>
                        <th class="border-0">Size</th>
                        <th class="border-0" style="width: 80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file in mediaFiles" :key="file.id">
                        <td @click="openFile(file)" style="
                  max-width: 150px;
                  overflow: hidden;
                  text-overflow: ellipsis;
                  white-space: nowrap;
                ">
                            <span v-if="file.extension == 'pdf'" class="bg-light text-secondary rounded">
                                <i class="mdi mdi-file-pdf-outline font-28"></i>
                            </span>

                            <img v-else :src="file.url" alt="image" class="img-fluid avatar-sm rounded mt-2" />
                            <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">{{
                    file.name
                  }}</a></span>
                        </td>
                        <td>{{ file.pages_count }}</td>
                        <td>
                            <p class="mb-0">
                                {{
                    new Date(file.created_at)
                      .toISOString()
                      .slice(0, 19)
                      .replace(/-/g, "/")
                      .replace("T", " ")
                  }}
                            </p>
                        </td>
                        <td>{{ parseFloat(file.size * 0.000001).toFixed(1) }} MB</td>
                        <td>
                            <div class="btn-group dropdown">
                                <a href="javascript: void(0);" class="
                      table-action-btn
                      dropdown-toggle
                      arrow-none
                      btn btn-light btn-xs
                    " data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a> -->
                                    <a class="dropdown-item" href="#" @click.prevent="editFile(file)"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Edit</a>
                                    <a class="dropdown-item" target="_blank" :href="file.url" :download="file.name"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                    <a class="dropdown-item" href="#" @click.prevent="deleteFile(file)"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </div>
    <div v-if="view == 'cards'" class="mt-3">
        <h5 class="mb-3">Files</h5>
        <div class="row">
            <div v-for="file in mediaFiles" :key="file.id + 'card'" class="col-md-3 col-xl-3">
                <div class="card product-box">
                    <div class="card-body" style="padding: 0.5rem">
                        <div class="product-action">
                            <a href="javascript: void(0);" class="btn btn-success btn-xs waves-effect waves-light"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript: void(0);" class="btn btn-danger btn-xs waves-effect waves-light"><i class="mdi mdi-close"></i></a>
                        </div>

                        <div class="bg-light" style="">
                            <img :src="file.url" alt="product-pic" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: dover" />
                        </div>

                        <div class="product-info">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="font-12 mt-0 sp-line-1">
                                        <a href="#" class="text-dark">{{ file.name }}</a>
                                    </h5>
                                    <h5 class="m-0">
                                        <span class="text-muted">
                                            {{
                          parseFloat(file.size * 0.000001).toFixed(1)
                        }}
                                            MB</span>
                                    </h5>
                                </div>
                                <div class="col-12">
                                    <div class="product-price-tag mt-1">
                                        <i class="mdi mdi-download me-2 text-muted vertical-middle"></i>
                                        <i class="mdi mdi-link me-2 text-muted vertical-middle"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end product info-->
                    </div>
                </div>
                <!-- end card-->
            </div>
            <!-- end col-->
        </div>
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
</style>
