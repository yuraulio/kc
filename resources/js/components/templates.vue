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
                v-for="template in templates"
                v-bind:key="template.id"
                :title="template.title"
                :description="template.description"
                @updatemode="updatemode"
                :id="template.id"
                @updateid="updateid"
                @updatetitle="updatetitle"
            >
            </row-box>
            </div>
            <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div>
        </div>

        <div v-if="mode == 'new'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                rows="true"
                type="new"
                route="templates"
                page-title="New Template"
            ></add-edit>
        </div>

        <div v-if="mode == 'edit'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                rows="true"
                type="edit"
                route="templates"
                page-title="Edit Template"
                :id="id"
            ></add-edit>
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
                loading: false
            }
        },
        watch: {
            filter: function() {
                this.getData();
            }
        },
        methods: {
            updatemode(variable){
                if (variable == "delete") {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            this.remove();
                        }
                     })
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
            getData(){
                this.loading = true;
                axios.get('/api/templates?filter=' + this.filter)
                    .then((response) => {
                        this.templates = response.data["data"];
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.loading = false;
                    });
            },
            remove(){
                this.errors = null;
                axios
                .delete('/api/templates/delete/' + this.id)
                .then((response) => {
                    if (response.status == 200){
                        this.templates.splice(_.findIndex(this.templates, { 'id' : this.id }), 1);
                        this.$emit('updatemode', 'list');
                    }
                    Swal.fire(
                        'Deleted!',
                        'Item has been deleted.',
                        'success'
                    )
                })
                .catch((error) => {
                    console.log(error)
                    Swal.fire(
                        'Not Deleted!',
                        'Deleteing has failed.',
                        'error'
                    )
                    this.loading = false;
                });
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
