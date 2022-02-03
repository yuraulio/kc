<style scoped>

</style>

<template>
    <div>
        <div v-if="mode == 'list'">

            <div class="page-title-box mt-3 mb-3">
                <h4 class="page-title">Pages</h4>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row justify-content-between">

                        <div class="col-md-12 mb-3">
                            <div class="text-md-end mt-3 mt-md-0">
                                <button @click="mode='new'" type="button" class="btn btn-soft-info waves-effect waves-light"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
                            </div>
                        </div><!-- end col-->

                        <div class="col-3">
                            <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input v-model="filter" type="search" class="form-control my-1 my-md-0" style="height: 43px;" id="inputPassword2" placeholder="Search...">
                        </div>
                        <div class="col-md-3">
                            <div class="text-md-end mt-3 mt-md-0">
                                
                                <multidropdown
                                    :multi="false"
                                    @updatevalue="update_type"
                                    :prop-value="type_value"
                                    :fetch="false"
                                    :data="type_list"
                                    placeholder="Pick type"
                                ></multidropdown>

                            </div>
                        </div><!-- end col-->

                        
                        <div class="col-md-3">
                            <div class="text-md-end mt-3 mt-md-0">

                                <multidropdown
                                    :multi="false"
                                    @updatevalue="update_category"
                                    :prop-value="category_value"
                                    route="categories"
                                    placeholder="Pick category"
                                    @change="getData()"
                                ></multidropdown>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-md-end mt-3 mt-md-0">

                                <multidropdown
                                    :multi="false"
                                    @updatevalue="update_subcategory"
                                    :prop-value="subcategory_value"
                                    :fetch="false"
                                    :data="subcategories"
                                    placeholder="Pick subcategory"
                                    @change="getData()"
                                ></multidropdown>

                            </div>
                        </div>

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
import multidropdown from './inputs/multidropdown.vue';

    export default {
        components: {
            page,
            pageseditable,
            multidropdown,
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
                lodash: _,
                type_value: null,
                category_value: null,
                subcategory_value: null,
                type_list: [
                    {
                        'id': 1,
                        'title':'Article'
                    },
                    {
                        'id': 2,
                        'title':'Blog'
                    },
                    {
                        'id': 3,
                        'title':'Course page'
                    },
                    {
                        'id': 4,
                        'title':'Trainer page'
                    },
                    {
                        'id': 5,
                        'title':'General'
                    }
                ],
                subcategories: [],
            }
        },
        watch: {
            filter: function() {
                this.getData();
            },
            "category_value": function() {
                
            }
        },
        methods: {
            created($event) {
                this.pages['data'].unshift($event);
            },
            addCustomComponent() {
                this.$modal.show("component-modal")
            },
            rearange() {
                this.$modal.hide("component-modal")
                this.$refs.adit.rearange();
            },
            updatemode(variable){
                if (variable == "delete") {
                     Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this! Delete page '" + this.title + "'?",
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
                axios.get('/api/pages',
                {
                    params: {
                        filete: this.filter,
                        page: page,
                        type: this.type_value ? this.type_value.title : null,
                        category: this.category_value ? this.category_value.id : null,
                        subcategory: this.subcategory_value ? this.subcategory_value.id : null,
                    }
                }
                )
                    .then((response) => {
                        this.pages = response.data;
                        this.isLoading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.isLoading = false;
                    });
            },
            update_category(value){
                this.category_value = value;
                this.getData();

                var subcategories = [];
                if (this.category_value) {
                    this.category_value.subcategories.forEach(function(subcategory, index) {
                        subcategories.push(subcategory);
                    });
                    this.subcategories = subcategories;
                }
            },
            update_subcategory(value){
                this.subcategory_value = value;
                this.getData();
            },
            update_type(value){
                this.type_value = value;
                this.getData();
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
