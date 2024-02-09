<template>
  <div>
    <div v-if="view == 'list'" class="mt-3" style="">
      <h5 class="mb-3 d-inline-block">Files</h5>
      <div v-if="selectedFiles.length > 0" class="btn-group dropleft multiselect-actions float-end ms-2" role="group">
        <button
          class="btn btn-soft-secondary dropdown-toggle"
          type="button"
          id="dropdownMenuButton1"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fa fa-cog" aria-hidden="true"></i>
        </button>
        <div class="dropdown-menu settings-dropdown" aria-labelledby="dropdownMenuButton1">
          <a class="dropdown-item" href="#" @click="deleteFiles()"> <i class="fas fa-trash"></i> Delete selected </a>
          <a class="dropdown-item" href="#" @click="moveFiles()"> <i class="fa fa-arrow-right"></i> Move selected </a>
        </div>
      </div>
      <div class="d-flex" style="">
        <div class="d-flex" style="align-items: end; width: 100%">
          <table class="table table-centered mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    value=""
                    :checked="selectAll == true"
                    @click="toggleSelectAll()"
                  />
                </th>
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
                <td>
                  <input
                    class="form-check-input"
                    type="checkbox"
                    value=""
                    :checked="file.selected == true"
                    @click="toggleSelected(file)"
                  />
                </td>
                <td
                  style="
                    max-width: 150px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    cursor: pointer;
                  "
                >
                  <span
                    v-if="file.extension.toLowerCase() == 'pdf'"
                    @click="goTo(file.url)"
                    class="bg-light text-secondary rounded"
                  >
                    <i class="mdi mdi-file-pdf-outline font-28" style="font-size: 28px !important"></i>
                  </span>
                  <img
                    v-else-if="imageExtensions.includes(file.extension.toLowerCase())"
                    @click.prevent="editFile(file)"
                    :src="file.url"
                    alt="image"
                    class="img-fluid avatar-sm rounded mt-2"
                    style="width: 100px; height: auto"
                  />

                  <span v-else @click="goTo(file.url)" class="bg-light text-secondary rounded">
                    <i class="mdi mdi-file font-28" style="font-size: 28px !important"></i>
                  </span>
                </td>
                <td class="text-truncate">
                  <span class="ms-2 fw-normal" :title="file.name">{{ file.name }}</span>
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
                  {{ size(file.size) }}
                </td>
                <td>
                  <div class="btn-group dropstart">
                    <a
                      href="javascript: void(0);"
                      class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <i class="mdi mdi-dots-horizontal"></i>
                    </a>
                    <div class="dropdown-menu" style="">
                      <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a> -->
                      <a
                        v-if="imageExtensions.includes(file.extension.toLowerCase())"
                        class="dropdown-item"
                        href="#"
                        @click.prevent="editFile(file)"
                        ><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Edit</a
                      >
                      <a class="dropdown-item" href="#" @click.prevent="moveFile(file)">
                        <i class="mdi mdi-file-move me-2 text-muted vertical-middle"></i>
                        Move
                      </a>
                      <a class="dropdown-item" target="_blank" :href="file.url" :download="file.name"
                        ><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a
                      >
                      <a class="dropdown-item" href="#" @click.prevent="deleteFile(file)"
                        ><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a
                      >
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
        <div v-for="file in mediaFiles" :key="file.id + '-card'" class="col-md-3 col-sm-6">
          <div class="card product-box">
            <div class="card-body" style="padding: 0.5rem; height: 200px">
              <div class="product-action">
                <div class="btn-group dropdown">
                  <a
                    href="javascript: void(0);"
                    class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    ><i class="mdi mdi-dots-horizontal"></i
                  ></a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#" @click.prevent="editFile(file)"
                      ><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Edit</a
                    >
                    <a class="dropdown-item" href="#" @click.prevent="moveFile(file)">
                      <i class="mdi mdi-file-move me-2 text-muted vertical-middle"></i>Move
                    </a>
                    <a class="dropdown-item" target="_blank" :href="file.url" :download="file.name"
                      ><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a
                    >
                    <a class="dropdown-item" href="#" @click.prevent="deleteFile(file)"
                      ><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a
                    >
                  </div>
                </div>
              </div>

              <div class="bg-light view-catds-item" style="text-align: center; min-width: 100px">
                <span v-if="file.extension.toLowerCase() == 'pdf'" class="bg-light text-secondary rounded">
                  <i class="mdi mdi-file-pdf-outline font-28" style="font-size: 28px !important"></i>
                </span>
                <img
                  v-else-if="imageExtensions.includes(file.extension.toLowerCase())"
                  :src="file.url + '?key=' + Math.random().toString().substr(2, 8)"
                  alt="product-pic"
                  class="img-fluid rounded"
                  style="width:100px, object-fit: dover"
                />

                <span v-else class="bg-light text-secondary rounded">
                  <i class="mdi mdi-file font-28" style="font-size: 28px !important"></i>
                </span>
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
import InfiniteLoading from 'vue-infinite-loading';

export default {
  components: {
    InfiniteLoading,
  },
  props: {
    mediaFiles: {},
    view: {
      default: 'list',
    },
    imageExtensions: [],
    folderId: Number,
  },
  data() {
    return {
      page: 2,
      selectAll: false,
      multiselectShow: false,
      selectedFiles: [],
    };
  },
  methods: {
    editFile(file) {
      this.$emit('selected', file);
    },
    moveFile(file) {
      this.$emit('move', file);
    },
    deleteFile(file) {
      this.$emit('delete', file);
    },
    deleteFiles() {
      this.$emit('deleteMulti', this.selectedFiles);
    },
    moveFiles() {
      this.$emit('moveMulti', this.selectedFiles);
    },
    openFile(file) {
      this.$emit('open', file);
    },
    infiniteHandler($state) {
      axios
        .get('/api/media_manager/files', {
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
          if (error.response) {
            this.$parent.errors = error.response.data.errors;
          }
        });
    },
    size(size) {
      if (size < 1000000) {
        return parseFloat(size * 0.001).toFixed(1) + ' kB';
      } else {
        return parseFloat(size * 0.000001).toFixed(1) + ' MB';
      }
    },
    goTo(url) {
      window.open(url, '_blank');
    },
    toggleSelectAll() {
      this.selectAll = !this.selectAll;
      var data = this.selectAll;
      var selectedFiles = [];
      this.mediaFiles.forEach(function (file, index) {
        file.selected = data;
        if (data) {
          selectedFiles.push(file.id);
        }
      });

      this.selectedFiles = selectedFiles;
    },
    toggleSelected(file) {
      file.selected = !file.selected;
      if (file.selected == true) {
        this.selectedFiles.push(file.id);
      } else {
        this.selectedFiles = this.selectedFiles.filter(function (e) {
          return e !== file.id;
        });
        this.selectAll = false;
      }
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
td,
th {
  word-wrap: break-word;
}

.view-catds-item {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

.product-box:hover .product-action {
  transform: translateX(25px);
}

.product-box:hover .product-action .btn-group.dropdown > a {
  transform: translateX(-11px);
}
</style>
