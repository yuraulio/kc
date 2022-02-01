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
                    @updatevalue="update_rows"
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
            <tc ref="tc" :template="template_value"></tc>
            <label style="width: 100%">
            <form @click.prevent="$emit('add-custom-component')" class="dropzone dz-clickable" style="min-height:100px" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                <div class="dz-message needsclick" style="margin: 0px !important">
                    <i class="h1 text-muted dripicons-view-apps"></i>
                    <div class="text-center">
                    <span class="text-muted font-13">
                        <strong>Click to Add Custom Component</strong></span>
                </div>
                </div>
            </form>
        </label>
        </div>
        <!-- end row -->
        <multiput
            type="multidropdown"
            v-if="subcategories"
            title="Subcategories"
            :multi="true"
            :taggable="true"
            @inputed="update_subcategories"
            route="subcategories"
            :existing-value="subcategories_value"
            :keyput="'subcategories'"
        ></multiput>

        <div v-if="errors" class="row mt-3">
            <span class="text-danger" v-for="error in errors">{{error[0]}}</span>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-success waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-success waves-effect waves-light m-1"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>
                <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div>

        <!--
        <list
            v-if="subcategories"
            title="Subcategories"
            :prop-value="subcategories_value"
            route="categories"
            :id="id"
            class="mt-3"
            placeholder="Add new subcategory"
            @refresh="refreshCategories"
        ></list> 
        -->

    </div> <!-- end card-body -->
</div> <!-- end card-->

</template>

<script>
import multidropdown from './inputs/multidropdown.vue';
import Multiput from './inputs/multiput.vue';
import Tc from './tc.vue';
    export default {
  components: { multidropdown, Tc, Multiput },
        props: {
            pageTitle: String,
            title: String,
            description: String,
            rows: String,
            category: String,
            subcategories: String,
            route: String,
            type: String,
            id: Number,
            data: {},
            additionalTemplates: {}
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
                loading: false,
                subcategories_value: null,
            }
        },
        methods: {
            update_subcategories(value) {
                this.subcategories_value = value.data;
                // console.log('SCV', this.subcategories_value)
            },
            update_title(value){
                this.title_value = value;
            },
            update_description(value){
                this.description_value = value;
            },
            update_rows(value){
                this.rows_value = value;
            },
            update_category(value){
                this.category_value = value;
            },
            update_template(value){
                this.template_value = value;
            },
            rearange() {
                this.$refs.tc.rearange();
            },
            refreshCategories(){
                this.$emit('refreshcategories');
            },
            add(){
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route,
                    {
                        title: this.title_value,
                        description: this.description_value,
                        rows: JSON.stringify(this.rows_value),
                        category_id: this.category_value,
                        subcategories: this.subcategories_value
                    }
                )
                .then((response) => {
                    if (response.status == 201){
                        //this.$emit('refreshcategories');
                        this.$emit('created', response.data.data);
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
                .patch('/api/' + this.route + '/' + this.id,
                    {
                        title: this.title_value,
                        description: this.description_value,
                        rows: JSON.stringify(this.rows_value),
                        category_id: this.category_value,
                        id: this.id,
                        subcategories: this.subcategories_value
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.route == 'categories' ? this.$emit('edited', response.data.data) : this.$emit('refreshcategories');
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
                .get('/api/' + this.route + '/' + this.id)
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
                        this.subcategories_value = data.subcategories;
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
                        this.rows_value = JSON.parse(data.rows);
                    }
                    this.category_value = data.category_id;
                    this.subcategories_value = data.subcategories;
                } else {
                    this.get()
                }

            }
        },
        watch: {
            additionalTemplates() {
                if (this.template_value) {
                    this.template_value.concat(this.additionalTemplates)
                } else {
                    this.template_value = this.additionalTemplates
                    console.log('TM', this.template_value)
                }
            }
        }
    }
</script>
