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

                <multidropdown
                    v-if="category"
                    title="Template"
                    :multi="false"
                    @updatevalue="update_template"
                    :prop-value="template_value"
                    route="templates"
                ></multidropdown>

                <multidropdown
                    v-if="category"
                    title="Categories"
                    @updatevalue="update_category"
                    :prop-value="category_value"
                    route="categories"
                ></multidropdown>

                <rows
                    v-if="rows"
                    title="Content"
                    required=1
                    
                    :prop-value="rows_value"
                ></rows>

                <!--<page
                    v-if="page"
                    title="Content"
                    required=1
                    @updatevalue="update_page"
                    :prop-value="rows_value"
                ></page>-->


            </div> <!-- end col-->
        </div>

        <div v-if="category">
            <tc v-if="template_value" :template="template_value"></tc>
        </div>
        <!-- end row -->

        <div v-if="errors" class="row mt-3">
            <span class="text-danger" v-for="error in errors">{{error[0]}}</span>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-success waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-success waves-effect waves-light m-1"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Edit</button>
                <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div>

    </div> <!-- end card-body -->
</div> <!-- end card-->

</template>

<script>
import multidropdown from './inputs/multidropdown.vue';
import Tc from './tc.vue';
    export default {
  components: { multidropdown, Tc },
        props: {
            pageTitle: String,
            title: String,
            description: String,
            rows: String,
            category: String,
            route: String,
            type: String,
            id: Number,
            data: {}
        },
        data() {
            return {
                title_value: null,
                description_value: null,
                rows_value: null,
                category_value: null,
                errors: null,
                test: null,
                template_value: null,
                loading: false
            }
        },
        methods: {
            update_title(value){
                this.title_value = value;
            },
            update_description(value){
                this.description_value = value;
            },
            update_rows(value){
                console.log("updated");
                this.rows_value = value;
            },
            update_category(value){
                this.category_value = value;
            },
            update_template(value){
                this.template_value = value;
            },
            add(){
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route + '/add',
                    {
                        title: this.title_value,
                        description: this.description_value,
                        rows: JSON.stringify(this.rows_value),
                        category_id: this.category_value,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        //this.$emit('refreshcategories');
                        this.$emit('created', response.data);
                        this.$emit('updatemode', 'list');
                        this.$toast.success('Created Successfully!')
                    }
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
            },
            edit(){
                this.loading = true;
                this.errors = null;
                axios
                .post('/api/' + this.route + '/edit/' + this.id,
                    {
                        title: this.title_value,
                        description: this.description_value,
                        rows: JSON.stringify(this.rows_value),
                        category_id: this.category_value,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.route == 'categories' ? this.$emit('edited', response.data) : this.$emit('refreshcategories');
                        this.$emit('updatemode', 'list');
                        this.$toast.success('Edited Successfully!')
                        this.loading = false;
                    }
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
            },
            get(){
                axios
                .get('/api/' + this.route + '/get/' + this.id)
                .then((response) => {
                    if (response.status == 200){
                        var data = response.data.data;
                        this.title_value = data.title;
                        this.description_value = data.description;
                        if (data.rows){
                            console.log(data.rows);
                            this.rows_value = JSON.parse(data.rows);
                            console.log(JSON.stringify(this.rows_value));
                        }
                        this.category_value = data.category_id;
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            }
        },
        mounted() {
            if (this.type == "edit"){
                if (this.data) {
                    var data = this.data;
                    this.title_value = data.title;
                    this.description_value = data.description;
                    if (data.rows){
                        // this.rows_value = JSON.parse(data.rows);
                    }
                    this.category_value = data.category_id;
                } else {
                    this.get()
                }

            }
        }
    }
</script>
