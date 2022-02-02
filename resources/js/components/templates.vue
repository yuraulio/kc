<style scoped>

</style>

<template>
    <div>

        <div v-if="mode == 'list'">

            <div class="page-title-box mt-3 mb-3">
                <h4 class="page-title">Templates</h4>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <label for="inputPassword2" class="visually-hidden">Search</label>
                            <div class="me-3">
                                <input v-model="filter" type="search" class="form-control my-1 my-md-0" id="inputPassword2" placeholder="Search...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-md-end mt-3 mt-md-0">
                                <button @click="mode='new'" type="button" class="btn btn-soft-info waves-effect waves-light"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
            <div v-if="!loading">
                <!--
                <row-box
                    v-for="template in templates['data']"
                    v-bind:key="template.id"
                    :title="template.title"
                    :description="template.description"
                    @updatemode="updatemode"
                    :id="template.id"
                    @updateid="updateid"
                    @updatetitle="updatetitle"
                    :user="template.user"
                    :pages="template.pages"
                >
                </row-box>
                -->

                <div class="card mb-2">
                    <div class="card-body">
                        <vuetable ref="vuetable"
                            :fields="[
                                {
                                    name: 'title',
                                    title: 'Name',
                                },
                                {
                                    name: 'pages',
                                    title: 'Pages used on',
                                    dataClass: 'text-center',
                                    titleClass: 'text-center',
                                },
                                {
                                    name: 'created_at',
                                    title: 'Created at',
                                    dataClass: 'text-center',
                                    titleClass: 'text-center',
                                },
                                {
                                    name: 'user',
                                    title: 'Created by',
                                    dataClass: 'text-center',
                                    titleClass: 'text-center',
                                },
                                {
                                    name: 'actions',
                                    title: 'Actions',
                                    titleClass: 'text-end',
                                }
                            ]"
                            :api-url="'/api/templates?filter=' + this.filter"
                            :api-mode="false"
                            :data="templates['data']"
                        >
                            <template slot="actions" slot-scope="props">
                                <div class="text-sm-end">
                                    <a @click="edit(props.rowData.id, props.rowData.title)" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    <a @click="remove(props.rowData.id, props.rowData.title)" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                </div>
                            </template>
                        </vuetable>
                    </div>
                </div>

                <pagination v-if="templates['meta']" class="mt-3"
                :data="templates['meta']"
                @pagination-change-page="getData"
                :limit = 5
                align = "center"
                :show-disabled = "true"
                ></pagination>


            </div>
            <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div>
        </div>

        <div v-if="mode == 'new'">
            <editable
                @updatemode="updatemode"
                @refreshcategories="getData"
                @created="created"
                title="true"
                :fields="fields"
                description="true"
                rows="true"
                type="new"
                route="templates"
                page-title="New Template"
            ></editable>
        </div>

        <div v-if="mode == 'edit'">
            <editable
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                rows="true"
                type="edit"
                route="templates"
                :page-title="'Edit Template: ' + title"
                :predata="templates['data'] && id ? JSON.parse(lodash.find(templates['data'], {'id': id}).rows) : null"
                :fields="fields"
                :data="lodash.find(templates, { 'id': id })"
                :id="id"
            ></editable>
        </div>

        <div v-if="mode == 'delete'">
            <delete
                @updatemode="updatemode"
                @refreshcategories="getData"
                :title="title"
                route="templates"
                page-title="Delete Template"
                :id="id"
            ></delete>
        </div>

    </div>
</template>

<script>
    import templatesList from './templates.json'
    import Vuetable from 'vuetable-2'

    export default {
        components: {
            Vuetable
        },
        props: {

        },
        data() {
            return {
                mode: "list",
                templates: [],
                id: null,
                title: null,
                filter: "",
                loading: false,
                fields: templatesList,
                lodash: _
            }
        },
        watch: {
            filter: function() {
                this.getData();
            },
        },
        methods: {
            created($event) {
                this.templates['data'].unshift($event);
            },
            updatemode(variable){
                if (variable == "delete") {
                     Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this! Delete template '" + this.title + "'?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        showLoaderOnConfirm: true,
                        buttonsStyling: false,
                        customClass : {
                            cancelButton: 'btn btn-soft-secondary',
                            confirmButton: 'btn btn-soft-danger',
                        },
                        preConfirm: () => {
                            return axios
                                .delete('/api/templates/' + this.id)
                                .then((response) => {
                                    if (response.status == 200){
                                        this.templates['data'].splice(_.findIndex(this.templates['data'], { 'id' : this.id }), 1);
                                        // this.$refs.vuetable.tableData.splice(_.findIndex(this.$refs.vuetable.tableData, { 'id' : this.id }), 1);
                                        this.$emit('updatemode', 'list');
                                    }

                                })
                                .catch(error => {
                                Swal.showValidationMessage(
                                `Request failed: ${error}`
                                )
                            })
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire(
                                        'Deleted!',
                                        'Item has been deleted.',
                                        'success'
                                    )
                            }
                        })
                } else if (variable == 'edit') {
                    this.mode = variable;
                } else {
                    this.mode = variable;
                }
            },
            updateid(variable){
                this.id = variable;
            },
            updatetitle(variable){
                this.title = variable;
            },
            getData(page = 1){
                this.loading = true;
                axios.get('/api/templates?filter=' + this.filter + '&page=' + page)
                    .then((response) => {
                        this.templates = response.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.loading = false;
                    });
            },
            edit(id, title){
                this.id = id;
                this.title = title;
                this.updatemode("edit");
            },
            remove(id, title){
                this.id = id;
                this.title = title;
                this.updatemode("delete");
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
        },
        mounted() {
            this.getData();
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get("filter")) {
                this.filter = urlParams.get("filter");
            }
        }
    }
</script>
