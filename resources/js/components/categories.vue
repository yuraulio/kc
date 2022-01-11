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
                                <input type="search" class="form-control my-1 my-md-0" id="inputPassword2" placeholder="Search...">
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
                v-for="category in categories"
                v-bind:key="category.id"
                :title="category.title"
                :description="category.description"
                @updatemode="updatemode"
                :id="category.id"
                @updateid="updateid"
            >
            </row-box>

        </div>

        <div v-if="mode == 'new'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                type="new"
                route="categories"
                page-title="New Category"
            ></add-edit>
        </div>

        <div v-if="mode == 'edit'">
            <add-edit
                @updatemode="updatemode"
                @refreshcategories="getData"
                title="true"
                description="true"
                type="edit"
                route="categories"
                page-title="Edit Category"
                :id="id"
            ></add-edit>
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
                categories: [],
                id: null,
            }
        },
        methods: {
            updatemode(variable){
                this.mode = variable;
            },
            updateid(variable){
                this.id = variable;
            },
            getData(){
                axios.get('/api/categories/')
                    .then((response) => {
                        this.categories = response.data["data"];
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
