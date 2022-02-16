<style scoped>
</style>

<template>
  <div>
    <modal name="edit-modal" :resizable="true" height="auto" :adaptive="true" @before-close="beforeClose">
        <div v-if="item" class="card">
            <div class="card-header">
                <h5>{{ type == 'edit' ? 'Edit' : 'Create'}}</h5>
            </div>
            <div class="card-body">
                <multiput
                    v-for="input in (type == 'edit' ? config.editInputs : config.addInputs)"
                    :key="input.key"
                    :keyput="input.key"
                    :label="input.label"
                    :type="input.type"
                    :size="input.size"
                    :value="item[input.key]"
                    @inputed="inputed($event, input)"
                >
                <ul
                    v-if="errors && errors[input.key]"
                    class="parsley-errors-list filled"
                    id="parsley-id-7"
                    aria-hidden="false"
                >
                    <li class="parsley-required">{{ errors[input.key][0] }}</li>
                </ul>
                </multiput>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success waves-effect waves-light m-1" :disabled="loadingModal"><i v-if="!loadingModal" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                <button v-if="type == 'edit'" :disabled="loadingModal" @click="add()" type="button" class="btn btn-soft-success waves-effect waves-light m-1"><i v-if="!loadingModal" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>
                <button @click="$modal.hide('edit-modal')" type="button" class="btn btn-soft-secondary waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div>
        </div>
    </modal>

    <div class="card mb-2">
      <div class="card-body">
        <div class="row justify-content-between">
          <div class="col-auto">
            <label for="inputPassword2" class="visually-hidden">Search</label>
            <div class="me-3">
              <input
                v-model="filter"
                type="search"
                class="form-control my-1 my-md-0"
                id="inputPassword2"
                placeholder="Search..."
              />
            </div>
          </div>
          <div class="col-md-4">
            <div class="text-md-end mt-3 mt-md-0">
              <button
                @click="addNew"
                type="button"
                class="btn btn-soft-info waves-effect waves-light"
              >
                <i class="mdi mdi-plus-circle me-1"></i> Add New
              </button>
            </div>
          </div>
          <!-- end col-->
        </div>
        <!-- end row -->
      </div>
      <!-- end card-body-->
    </div>
    <!-- end card-->
    <div class="card mb-2">
      <div class="card-body">
        <div v-show="loading" class="col-lg-12 text-center">
          <vue-loaders-ball-grid-beat
            color="#6658dd"
            scale="1"
            class="mt-4 text-center"
          >
          </vue-loaders-ball-grid-beat>
        </div>
        <vuetable
          v-show="!loading"
          ref="vuetable"
          :fields="config.fields"
          :api-url="config.apiUrl + '?filter=' + this.filter"
          :api-mode="true"
          pagination-path="meta"
          @vuetable:pagination-data="onPaginationData"
          @vuetable:initialized="onInitialized"
          @vuetable:loading="showLoader"
          @vuetable:loaded="hideLoader"
        >
          <template slot="actions" slot-scope="props">
            <div class="text-sm-end">
              <a
                @click="edit(props.rowData)"
                href="javascript:void(0);"
                class="action-icon"
              >
                <i class="mdi mdi-square-edit-outline"></i
              ></a>
              <a
                @click="remove(props.rowData.id, props.rowData.title)"
                href="javascript:void(0);"
                class="action-icon"
              >
                <i class="mdi mdi-delete"></i
              ></a>
            </div>
          </template>
        </vuetable>

        <ul class="pagination mt-3 justify-content-center">
          <vuetable-pagination-info
            v-show="false"
            ref="paginationInfo"
          ></vuetable-pagination-info>
          <pagination
            v-if="paginationData"
            class="mt-3"
            :data="paginationData"
            @pagination-change-page="onChangePage"
            :limit="5"
            align="center"
            :show-disabled="true"
          ></pagination>
        </ul>
      </div>
    </div>
  </div>

  <!-- <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div> -->
</template>

<script>
import Vuetable from "vuetable-2";
import VuetablePaginationMixin from "vuetable-2/src/components/VuetablePaginationMixin";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetablePaginationDropdown from "vuetable-2/src/components/VuetablePaginationDropdown.vue";
import VuetablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo.vue";
export default {
  mixins: [VuetablePaginationMixin],
  components: {
    Vuetable,
    VuetablePagination,
    VuetablePaginationDropdown,
    VuetablePaginationInfo,
  },
  props: {
      config: {}
  },
  data() {
    return {
      paginationComponent: "vuetable-pagination",
      id: null,
      title: null,
      filter: "",
      loading: true,
      lodash: _,
      paginationData: null,
      item: {},
      type: '',
      loadingModal: false,
      errors: {}
    };
  },
  watch: {
    paginationComponent(newVal, oldVal) {
      this.$nextTick(() => {
        this.$refs.pagination.setPaginationData(
          this.$refs.vuetable.tablePagination
        );
      });
    },
  },
  methods: {
    onPaginationData(tablePagination) {
      console.log(tablePagination);
      this.paginationData = tablePagination;
      this.$refs.paginationInfo.setPaginationData(tablePagination);
      //this.$refs.pagination.setPaginationData(tablePagination);
    },

    onChangePage(page) {
      this.$refs.vuetable.changePage(page);
    },

    onInitialized(fields) {
      //this.vuetableFields = fields.filter(field => field.togglable);
    },

    showLoader() {
      this.loading = true;
    },

    hideLoader() {
      this.loading = false;
    },
    created($event) {
      this.templates["data"].unshift($event);
    },
    updatemode(variable) {
      if (variable == "delete") {

      } else if (variable == "edit") {
        this.mode = variable;
      } else {
        this.mode = variable;
      }
    },
    updateid(variable) {
      this.id = variable;
    },
    updatetitle(variable) {
      this.title = variable;
    },
    addNew() {
      this.type = 'new';
      this.$modal.show("edit-modal");
    },
    edit(item) {
      this.item = item;
      this.type = 'edit';
      this.$modal.show("edit-modal");
    },
    inputed($event, key) {
        console.log($event, key)
        this.$set(this.item, $event.key, $event.data);
    },
    beforeClose() {
        this.item = {};
    },
    add(){
        this.errors = null;
        this.loadingModal = true;
        axios[this.type == 'edit' ? 'put' : 'post'](this.config.apiUrl + (this.type == 'edit' ? '/' + this.item.id : ''),
            this.item
        )
        .then((response) => {
            if (response.status == 201){
                this.$toast.success('Created Successfully!')
            }
            if (response.status == 200){
                this.$toast.success('Updated Successfully!')
            }
            this.$refs.vuetable.reload();
            this.loadingModal = false;
            this.$modal.hide("edit-modal");
        })
        .catch((error) => {
            console.log(error)
            this.errors = error.response.data.errors;
            this.loadingModal = false;
        });
    },
    remove(id, title) {
      Swal.fire({
          title: "Are you sure?",
          text:
            "You won't be able to revert this! Delete item?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          showLoaderOnConfirm: true,
          buttonsStyling: false,
          customClass: {
            cancelButton: "btn btn-soft-secondary",
            confirmButton: "btn btn-soft-danger",
          },
          preConfirm: () => {
            return axios
              .delete(this.config.apiUrl + '/' + id)
              .then((response) => {
                if (response.status == 200) {
                  this.$refs.vuetable.reload();
                }
              })
              .catch((error) => {
                Swal.showValidationMessage(`Request failed: ${error}`);
              });
          },
          allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire("Deleted!", "Item has been deleted.", "success");
          }
        });
    },
  },
  mounted() {

  },
};
</script>
