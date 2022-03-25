<style scoped>
</style>

<style>
    button.close.text-dark {
        border: 0px!important;
        background-color: transparent!important;
    }
    .vuetable-body tr:nth-of-type(odd) {
        background-color: #f3f7f9;
    }
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
                    :existing-value="item[input.key]" 
                    :value="item[input.key]" 
                    @inputed="inputed($event, input)" 
                    :multi="input.multi"
                    :taggable="input.taggable"
                    :fetch="input.fetch"
                    :route="input.route"
                >
                    <ul v-if="errors && errors[input.key]" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
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

    <b-sidebar v-if="config.filters" id="sidebar-right" title="" right shadow>
        <!--
        <div class="px-3 py-2">
            <input v-model="filter" type="search" class="form-control my-1 my-md-0 d-inline-block" id="inputPassword2" placeholder="Search..."/>
        </div>
        -->
         <div class="px-3 py-2">
            <p>No filters.</p>
        </div>
    </b-sidebar>

    <div class="row">

        <div class="col-md-3">
            <div class="card bg-pattern mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="avatar-md bg-blue rounded">
                                <i class="fe-layers avatar-title font-22 text-white"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="text-end">
                                <h3 class="text-dark my-1"><span>{{widgets[0]}}</span></h3>
                                <p class="text-muted mb-0 text-truncate">Categories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-pattern mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="avatar-md bg-success rounded">
                                <i class="fe-award avatar-title font-22 text-white"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="text-end">
                                <h3 class="text-dark my-1"><span>{{widgets[1]}}</span></h3>
                                <p class="text-muted mb-0 text-truncate">Subcategories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-pattern mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="avatar-md bg-danger rounded">
                                <i class="fe-delete avatar-title font-22 text-white"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="text-end">
                                <h3 class="text-dark my-1 text-truncate"><span>{{widgets[2]}}</span></h3>
                                <p class="text-muted mb-0 text-truncate">Popular category</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-pattern mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="avatar-md bg-warning rounded">
                                <i class="fe-dollar-sign avatar-title font-22 text-white"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="text-end">
                                <h3 class="text-dark my-1 text-truncate"><span>{{widgets[3]}}</span></h3>
                                <p class="text-muted mb-0 text-truncate">Popular subcategory</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- end card-->
    <div class="card mb-2">
        <div class="card-body pt-0" style="overflow: auto;">

            <div class="row mb-1">
                <div class="col-md-3 pt-3">
                    <input v-model="filter" type="search" class="form-control my-1 my-md-0 d-inline-block w-auto" id="inputPassword2" placeholder="Search..." style="transform: translateY(1px);"/>
                </div>
                <div class="col-md-9">

                    <div class="text-md-end mt-md-0 d-inline-block float-end ms-2 pt-3">
                        <button @click="addNew" type="button" class="btn btn-soft-info waves-effect waves-light">
                            <i class="mdi mdi-plus-circle me-1"></i> Add New
                        </button>
                    </div>

                    <div class="float-end ms-2 pt-3">
                        <b-button v-b-toggle.sidebar-right v-if="config.filters" class="btn-soft-secondary">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </b-button>
                    </div>

                    <div class="btn-group dropleft multiselect-actions float-end ms-2 pt-3">
                        <button class="btn btn-soft-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" @click="deleteMultipleItems()">
                                <i class="fas fa-trash"></i> Delete selected
                            </a>
                        </div>
                    </div>

                    <div class="btn-group dropleft multiselect-actions float-end pt-3">
                        <button class="btn btn-soft-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{perPage ? perPage : "All"}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" @click="changePerPage(10)">
                                10
                            </a>
                            <a class="dropdown-item" href="#" @click="changePerPage(50)">
                                50
                            </a>
                            <a class="dropdown-item" href="#" @click="changePerPage(100)">
                                100
                            </a>
                            <a class="dropdown-item" href="#" @click="changePerPage(0)">
                                All
                            </a>
                        </div>
                    </div>

                </div>
                <!-- end col-->
            </div>

            <div v-show="loading" class="col-lg-12 text-center">
                <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center">
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
                @vuetable:checkbox-toggled="showMultiselectActions"
                @vuetable:checkbox-toggled-all="selectAllItems"
                :per-page="config.perPage"
            >
                <template slot="actions" slot-scope="props">
                    <div class="text-sm-end">
                        <a @click="edit(props.rowData)" href="javascript:void(0);" class="action-icon">
                            <i class="mdi mdi-square-edit-outline"></i></a>
                        <a @click="remove(props.rowData.id, props.rowData.title)" href="javascript:void(0);" class="action-icon">
                            <i class="mdi mdi-delete"></i></a>
                    </div>
                </template>
            </vuetable>

            <ul class="pagination mt-3 justify-content-end">
                <vuetable-pagination-info v-show="false" ref="paginationInfo"></vuetable-pagination-info>
                <pagination v-if="paginationData" class="mt-3" :data="paginationData" @pagination-change-page="onChangePage" :limit="5" align="right" :show-disabled="true"></pagination>
            </ul>
        </div>
    </div>
</div>

<!-- <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div> -->
</template>

<script>
import Vuetable from "vuetable-2/src/components/Vuetable";
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
            errors: {},
            multiselectShow: false,
            selectAllCheckbox: false,
            perPage: 50,
            widgets: [],
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
        changePerPage(number) {
            var hash = window.location.hash;
            this.perPage = number;
            this.$set(this.config, "perPage", this.perPage);
            this.$refs.vuetable.changePage(1);
            this.$refs.vuetable.selectedTo = [];
            this.$nextTick( () =>
                this.$refs.vuetable.reload()
            );
        },

        onPaginationData(tablePagination) {
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
            this.$set(this.item, $event.key, $event.data);
        },
        beforeClose() {
            this.item = {};
        },
        add() {
            this.errors = null;
            this.loadingModal = true;
            axios[this.type == 'edit' ? 'put' : 'post'](this.config.apiUrl + (this.type == 'edit' ? '/' + this.item.id : ''),
                    this.item
                )
                .then((response) => {
                    if (response.status == 201) {
                        this.$toast.success('Created Successfully!')
                    }
                    if (response.status == 200) {
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
                text: "You won't be able to revert this! Delete item?",
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
                                var index = this.$refs.vuetable.tableData.findIndex(function(row) {
                                    return row.id == id;
                                });
                                this.$refs.vuetable.tableData.splice(index, 1);

                                if (this.$refs.vuetable.tableData.length == 0) {
                                    if (this.$refs.vuetable.tablePagination.current_page > 1) { 
                                        this.$refs.vuetable.changePage(this.$refs.vuetable.tablePagination.current_page -1);
                                    }
                                }
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
        showMultiselectActions(){
            let selected = this.$refs['vuetable'].selectedTo;
            if(selected.length > 0) {
                this.multiselectShow = true;
            } else {
                this.multiselectShow = false;
            }
        },
        selectAllItems() {
            var numberOfSelectedItems = this.$refs['vuetable'].selectedTo.length;
            var checkbox = document.getElementsByClassName("vuetable-th-component-checkbox")[0].getElementsByTagName("input")[0];
            if (!this.selectAllCheckbox) {
                // axios
                //     .get(this.config.apiUrl + "?per_page=0&filter=" + this.filter)
                //     .then((response) => {
                //         if (response.status == 200) {
                //             this.$refs['vuetable'].selectedTo = response.data;
                //             this.selectAllCheckbox = true;
                //             checkbox.checked = true;     
                //         }
                //     })
                //     .catch((error) => {
                //         Swal.showValidationMessage(`Request failed: ${error}`);
                //     });
            } else {
                this.$refs['vuetable'].selectedTo = [];
                this.selectAllCheckbox = false;
                checkbox.checked = false;
            }

            this.showMultiselectActions();
        },
        deleteMultipleItems() {
            // get selected items
            let selected = this.$refs['vuetable'].selectedTo;
            // turn into array
            selected = $.map(selected, function(value, index) {
                return [value];
            });
            // check if anything is selected
            if (selected.length > 0){
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this! Delete all " + this.$refs['vuetable'].selectedTo.length + " selected items?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete them!",
                    showLoaderOnConfirm: true,
                    buttonsStyling: false,
                    customClass: {
                        cancelButton: "btn btn-soft-secondary",
                        confirmButton: "btn btn-soft-danger",
                    },
                    preConfirm: () => {
                        console.log(selected);
                        return axios
                            .post(this.config.apiUrl + '/deleteMultiple', {
                                selected: selected,
                            })
                            .then((response) => {
                                if (response.status == 200) {
                                    this.$refs.vuetable.reload();
                                    this.$refs['vuetable'].selectedTo = [];
                                    this.multiselectShow = false;
                                }
                            })
                            .catch((error) => {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire("Delete started!", "Items are being deleted in the background. This will take a few minutes.", "success");
                    }
                });
            } else {
                // error message - nothing is selected
                Swal.showValidationMessage(`No items selected.`);
                // this.$toastr('error', transMan('submit_ad.nothing_selected'));
            }
        },
        getWidgets() {
            axios
            .get(this.config.apiUrl + '/widgets')
            .then((response) => {
                if (response.status == 200) {
                    this.widgets = response.data;
                }
            })
            .catch((error) => {
                this.$toast.error('Filed to get widget data.')
            });
        }
    },
    mounted() {
        this.perPage = this.config.perPage;
        this.getWidgets();
    },
};
</script>
