<style scoped>

</style>

<template>
    <div>
        <modal name="create-modal" :resizable="true" height="auto" :adaptive="true">
                <add-edit
                    @updatemode="$modal.hide('create-modal');"
                    @refreshcategories="getData"
                    title="true"
                    type="new"
                    route="categories"
                    @created="created"
                    page-title="New Category"
                ></add-edit>
        </modal>
        <modal name="edit-modal" :resizable="true" height="auto" :adaptive="true">
                <add-edit
                    @updatemode="$modal.hide('edit-modal');"
                    @refreshcategories="getData"
                    title="true"
                    subcategories="true"
                    type="edit"
                    route="categories"
                    @edited="edited"
                    :key="id"
                    page-title="Edit Category"
                    :data="lodash.find(categories, { 'id': id })"
                    :id="id"
                ></add-edit>
        </modal>
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
                                <button @click="$modal.show('create-modal');" type="button" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-body-->
            </div> <!-- end card-->

            <div v-if="!loading">
                <row-box
                    v-for="category in categories"
                    v-bind:key="category.id"
                    :title="category.title"
                    @updatemode="updatemode"
                    :id="category.id"
                    @updateid="updateid"
                    @updatetitle="updatetitle"
                    :user="category.user"
                    :pages="category.pages"
                    :list="category.subcategories"
                >
                </row-box>
            </div>
            <div style="margin-top: 150px" class="text-center" v-else>
                <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
            </div>
        </div>

        <div v-if="mode == 'delete'">
            <delete
                @updatemode="updatemode"
                @refreshcategories="getData"
                :title="title"
                route="categories"
                page-title="Delete Category"
                :id="id"
            ></delete>
        </div>
    <!--<tc></tc>-->
    </div>
</template>

<script>
    import tc from './tc.vue'
    export default {
        components: {
            tc
        },
        props: {

        },
        data() {
            return {
                mode: "list",
                categories: [],
                id: null,
                title: null,
                filter: "",
                loading: false,
                lodash: _
            }
        },
        watch: {
            filter: function() {
                this.getData();
            }
        },
        methods: {
            created($event) {
                this.categories.unshift($event);
                this.$modal.hide('create-modal');
            },
            edited($event) {
                this.categories.splice(_.findIndex(this.categories, { 'id': $event.id }), 1, $event);
                this.$modal.hide('edit-modal');
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
                                .delete('/api/categories/delete/' + this.id)
                                .then((response) => {
                                    if (response.status == 200){
                                        this.categories.splice(_.findIndex(this.categories, { 'id' : this.id }), 1);
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
                } else if (variable == "edit") {
                    this.$modal.show('edit-modal');
                }
                else {
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
                axios.get('/api/categories?filter=' + this.filter)
                    .then((response) => {
                        this.categories = response.data["data"];
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
