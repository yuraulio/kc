<style scoped>

</style>

<template>
    <div>
        <div class="row">
            <!-- <page></page> -->
        </div>
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

            <div v-if="!isLoading">
                <page
                v-for="page in pages['data']"
                v-bind:key="page.id"
                :page="page"
                :description="page.description"
                @updatemode="updatemode"
                @updateid="updateid"
                @updatetitle="updatetitle"
                ></page>

                <pagination v-if="pages['meta']" class="mt-3" 
                :data="pages['meta']" 
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
            <pageseditable
                ref="adit"
                @updatemode="updatemode"
                @refreshcategories="getData"
                @created="created"
                @add-custom-component="addCustomComponent"
                title="true"
                description="true"
                category="true"
                type="new"
                route="pages"
                :additionalTemplates="additionalTemplates"
                page-title="New Page"
            ></pageseditable>
        </div>

        <div v-if="mode == 'edit'">
            <pageseditable
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                category="true"
                type="edit"
                route="pages"
                page-title="Edit Page"
                :data="lodash.find(pages.data, { 'id': id })"
                :id="id"
            ></pageseditable>
        </div>

        <div v-if="mode == 'delete'">
            <delete
                @updatemode="updatemode"
                @refreshcategories="getData"
                :title="title"
                route="pages"
                page-title="Delete Page"
                :id="id"
            ></delete>
        </div>

    </div>
</template>
<script>
import page from './page.vue'
import pageseditable from './pageseditable.vue'
    export default {
        components: {
            page,
            pageseditable
        },
        props: {

        },
        data() {
            return {
                mode: "list",
                pages: [],
                id: null,
                title: null,
                filter: "",
                isLoading: false,
                additionalTemplates: [],
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
                // console.log($event);
                this.pages['data'].unshift($event);
            },
            addCustomComponent() {
                this.$modal.show("component-modal")
            },
            rearange() {
                this.$modal.hide("component-modal")
                // console.log(this.$refs.adit)
                this.$refs.adit.rearange();
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
                                .delete('/api/pages/' + this.id)
                                .then((response) => {
                                    if (response.status == 200){
                                        this.pages['data'].splice(_.findIndex(this.pages['data'], { 'id' : this.id }), 1);
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
                this.isLoading = true;
                axios.get('/api/pages?filter=' + this.filter + '&page=' + page)
                    .then((response) => {
                        this.pages = response.data;
                        // console.log(this.pages)
                        this.isLoading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.isLoading = false;
                    });
            },
        },
        mounted() {
            this.getData();
        }
    }
</script>

<style>
.widget-rounded-circle {
    transition: box-shadow .5s;
    cursor: pointer;
    box-shadow: 0 0 11px rgba(33,33,33,.2);
}
.widget-rounded-circle:hover {
  box-shadow: 0 0 11px rgba(33,33,33,.4);
}
</style>
