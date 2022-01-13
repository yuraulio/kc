<template>

<div class="card">
    <div class="card-body">

        <h4 class="mb-2">{{pageTitle}}</h4>

        <div class="row">
            <div class="col-xl-12">
                
                <text-field 
                    v-if="title"
                    title="Title"
                    @updatevalue="update_title"
                    :prop-value="title_value"
                    required=1
                ></text-field>

                <text-field 
                    v-if="description"
                    title="Description"
                    @updatevalue="update_description"
                    :prop-value="description_value"
                ></text-field>

            
                <rows
                    v-if="rows"
                    title="Content"
                    required=1
                ></rows>
                

            </div> <!-- end col-->
        </div>
        <!-- end row -->

        <div v-if="errors" class="row mt-3">
            <span class="text-danger" v-for="error in errors">{{error[0]}}</span>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-success waves-effect waves-light m-1"><i class="fe-check-circle me-1"></i> Create</button>
                <button v-if="type == 'edit'" @click="edit()" type="button" class="btn btn-success waves-effect waves-light m-1"><i class="mdi mdi-square-edit-outline me-1"></i> Edit</button>
                <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div>

    </div> <!-- end card-body -->
</div> <!-- end card-->

</template>

<script>
    export default {
        props: {
            pageTitle: String,
            title: String,
            description: String,
            rows: String,
            route: String,
            type: String,
            id: Number,
        },
        data() {
            return {
                title_value: null,
                description_value: null,
                errors: null
            }
        },
        methods: {
            update_title(value){
                this.title_value = value;
            },
            update_description(value){
                this.description_value = value;
            },
            add(){
                this.errors = null;
                axios
                .post('/api/' + this.route + '/add',
                    {
                        title: this.title_value,
                        description: this.description_value,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.$emit('refreshcategories');
                        this.$emit('updatemode', 'list');
                    }
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                });
            },
            edit(){
                this.errors = null;
                axios
                .post('/api/' + this.route + '/edit/' + this.id,
                    {
                        title: this.title_value,
                        description: this.description_value,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.$emit('refreshcategories');
                        this.$emit('updatemode', 'list');
                    }
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                });
            },
            get(){
                axios
                .get('/api/' + this.route + '/get/' + this.id)
                .then((response) => {
                    if (response.status == 200){
                        var category = response.data.data;
                        this.title_value = category.title;
                        this.description_value = category.description;
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            }
        },
        mounted() {
            if (this.type == "edit"){
                this.get();
            }
        }
    }
</script>