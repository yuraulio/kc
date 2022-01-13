vue<style scoped>

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
        
            <row-box 
                v-for="page in pages"
                v-bind:key="page.id"
                :title="page.title"
                :description="page.description"
                @updatemode="updatemode"
                :id="page.id"
                @updateid="updateid"
                @updatetitle="updatetitle"
            >
            </row-box>

        </div>

        <div v-if="mode == 'new'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                category="true"
                type="new"
                route="pages"
                page-title="New Page"
            ></add-edit>
        </div>

        <div v-if="mode == 'edit'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                category="true"
                type="edit"
                route="pages"
                page-title="Edit Page"
                :id="id"
            ></add-edit>
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

    export default {
        props: {
            
        },
        data() {
            return {
                mode: "list",
                pages: [],
                id: null,
                title: null,
                filter: "",
            }
        },
        watch: {
            filter: function() {
                this.getData();
            }
        },
        methods: {
            updatemode(variable){
                this.mode = variable;
            },
            updateid(variable){
                this.id = variable;
            },
            updatetitle(variable){
                this.title = variable;
            },
            getData(){
                axios.get('/api/pages?filter=' + this.filter)
                    .then((response) => {
                        this.pages = response.data["data"];
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },
        },
        mounted() {
            this.getData();
        }
    }
</script>
