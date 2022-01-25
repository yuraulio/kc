<style scoped>

</style>

<template>
    <div>

        <div v-if="mode == 'list'">

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
                                <button @click="mode='new'" type="button" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
            <div v-if="!loading">
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
                page-title="Edit Template"
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

    export default {
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
                console.log($event);
                this.templates['data'].unshift($event);
            },
            updatemode(variable){
                if (variable == "delete") {
                     Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios
                                .delete('/api/templates/' + this.id)
                                .then((response) => {
                                    if (response.status == 200){
                                        this.templates['data'].splice(_.findIndex(this.templates['data'], { 'id' : this.id }), 1);
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
                    // console.log('hooo',_.find(this.templates, {'id': this.id}))

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
        },
        mounted() {
            this.getData();
        }
    }
</script>
