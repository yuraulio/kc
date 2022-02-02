<style scoped>

</style>

<template>
    <div>

            <div class="page-title-box mt-3 mb-3">
                <h4 class="page-title">Comments</h4>
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
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
            <div v-if="!loading">
                <div class="card mb-2">
                    <div class="card-body">
                        <vuetable ref="vuetable"
                            :fields="[
                                {
                                    name: 'comment',
                                    title: 'Comment',
                                },
                                {
                                    name: 'page',
                                    title: 'Page',
                                    dataClass: 'text-center',
                                    titleClass: 'text-center',
                                },
                                {
                                    name: 'created_at_formated',
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
                            :api-url="'/api/comments?filter=' + this.filter"
                            :api-mode="false"
                            :data="comments['data']"
                        >
                            <template slot="actions" slot-scope="props">
                                <div class="text-sm-end">
                                    <a @click="remove(props.rowData.id, props.rowData.comment)" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                </div>
                            </template>
                        </vuetable>
                    </div>
                </div>

                <pagination v-if="comments['meta']" class="mt-3"
                :data="comments['meta']"
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
</template>

<script>
    import Vuetable from 'vuetable-2'

    export default {
        components: {
            Vuetable
        },
        props: {

        },
        data() {
            return {
                comments: [],
                id: null,
                comment: null,
                filter: "",
                loading: false,
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
                this.comments['data'].unshift($event);
            },
            updatemode(variable){
                if (variable == "delete") {
                     Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this! Delete comment '" + this.comment + "'?",
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
                                .delete('/api/comments/' + this.id)
                                .then((response) => {
                                    if (response.status == 200){
                                        this.comments['data'].splice(_.findIndex(this.comments['data'], { 'id' : this.id }), 1);
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
                }
            },
            updateid(variable){
                this.id = variable;
            },
            updatecomment(variable){
                this.comment = variable;
            },
            getData(page = 1){
                this.loading = true;
                axios.get('/api/comments?filter=' + this.filter + '&page=' + page)
                    .then((response) => {
                        this.comments = response.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.loading = false;
                    });
            },
            remove(id, comment){
                this.id = id;
                this.comment = comment;
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
