<template>
<div class="row">

        <div class="col-lg-9" >
            <tcedit 
                v-if="template_value" 
                :mode="type" 
                ref="tc" 
                :pseudo="false" 
                :predata="template_value ? JSON.parse(template_value.rows) : null"
            ></tcedit>
            <div v-else class="card">
                <div class="card-body p-4">
                    <div class="error-ghost text-center">
                        <gicon></gicon>
                    </div>

                    <div class="text-center">
                        <h3 class="mt-4">Select Template go get started</h3>
                        <p class="text-muted mb-0">Select template in the right menu or below, you'll have an option to update it manually.</p>
                        <multidropdown
                            v-if="category"
                            title="Template"
                            :multi="false"
                            @updatevalue="update_template"
                            :prop-value="template_value"
                            route="templates"
                        ></multidropdown>
                    </div>

                </div> <!-- end card-body -->
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card ">
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

                            <ul v-if="errors && errors['title']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false"><li class="parsley-required">{{errors['title'][0]}}</li></ul>

                            <multidropdown
                                title="Type"
                                :multi="false"
                                @updatevalue="update_type"
                                :prop-value="type_value"
                                :fetch="false"
                                :data="type_list"
                            ></multidropdown>

                            <multidropdown
                                title="Template"
                                :key="template_value ? template_value.id : 'temmult'"
                                :multi="false"
                                @updatevalue="update_template"
                                :prop-value="template_value"
                                route="templates"
                            ></multidropdown>

                            <multidropdown
                                title="Categories"
                                @updatevalue="update_category"
                                :prop-value="category_value"
                                route="categories"
                            ></multidropdown>

                            <multidropdown
                                title="Subcategories"
                                @updatevalue="update_subcategory"
                                :prop-value="subcategory_value"
                                :fetch="false"
                                :data="subcategories"
                            ></multidropdown>

                            <rows
                                v-if="rows"
                                title="Content"
                                required=1
                                @updatevalue="update_rows"
                                :prop-value="rows_value"
                            ></rows>

                            <div :key="'ck'"  class="form-check form-switch mb-3" style="display: inline-block; cursor: pointer">
                                <input :key="'on'" @click="published = !published" :id="'cinput'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="'cinput'" :checked="published">
                                <label class="form-check-label" for="light-mode-check">Published</label>
                            </div>

                            <datepicker-component
                                title="Publish from"
                                @updatevalue="update_published_from"
                                :prop-value="published_from_value"
                            ></datepicker-component>

                            <datepicker-component
                                title="Publish to"
                                @updatevalue="update_published_to"
                                :prop-value="published_to_value"
                            ></datepicker-component>

                            <div class="row mt-3">
                                <div class="col-12 text-center mb-3 d-grid">
                                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" :disabled="!template_value" @click="rearange()" class="btn btn-block btn-soft-info rounded-pill waves-effect waves-light m-1">Preview</button>
                                    <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success rounded-pill waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                                    <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success rounded-pill waves-effect waves-light m-1"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Edit</button>

                                    <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-soft-danger rounded-pill waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div>
                </div> <!-- end col-->
            </div>
        </div>
        <!-- end row -->

        <div v-if="errors" class="row mt-3">
            <span class="text-danger" v-for="error in errors">{{error[0]}}</span>
        </div>

<!--         <div class="row mt-3">
            <div class="col-12 text-center mb-3">
                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-success waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-success waves-effect waves-light m-1"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Edit</button>
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" @click="rearange()" class="btn btn-lg btn-secondary waves-effect waves-light">Preview</button>
                <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</button>
            </div>
        </div> -->

</div>
</template>

<script>
import multidropdown from './inputs/multidropdown.vue';
import Tcedit from './tcdit.vue';
import gicon from './gicon.vue';

export default {
  components: { multidropdown, Tcedit, gicon },
        props: {
            pageTitle: String,
            title: String,
            rows: String,
            category: String,
            route: String,
            type: String,
            id: Number,
            data: {},
            additionalTemplates: {}
        },
        data() {
            return {
                title_value: null,
                rows_value: null,
                category_value: [],
                errors: null,
                test: null,
                template_value: null,
                loading: false,
                published: false,
                lodash: _,
                page: {},
                subcategories: null,
                subcategory_value: null,
                published_from_value: null,
                published_to_value: null,
                type_value: null,
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
            }
        },
        methods: {
            rearange() {
                eventHub.$emit('component-rearange', false)
            },
            update_title(value){
                this.title_value = value;
            },
            update_rows(value){
                this.rows_value = value;
            },
            update_category(value){
                this.category_value = value;
                // this.subcategory_value = [];
            },
            update_subcategory(value){
                this.subcategory_value = value;
            },
            update_published_from(value){
                this.published_from_value = value;
            },
            update_published_to(value){
                this.published_to_value = value;
            },
            update_type(value){
                this.type_value = value;
            },
            update_template(value){
                console.log('tem', value)
                this.template_value = value;
                this.$forceUpdate()
            },
            rearange() {
                this.$refs.tc.rearange(true);
            },
            add(){
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route,
                    {
                        title: this.title_value,
                        rows: JSON.stringify(this.rows_value),
                        content: this.template_value ? JSON.stringify(this.$refs.tc.data) : '',
                        category_id: this.category_value,
                        subcategories: this.subcategory_value,
                        template_id: this.template_value ? this.template_value.id : null,
                        published: this.published,
                        published_from: this.published_from_value,
                        published_to: this.published_to_value,
                        type: this.type_value ? this.type_value.title : null,

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
                console.log(JSON.stringify(this.$refs.tc.data));
                axios
                .patch('/api/' + this.route + '/' + this.id,
                    {
                        title: this.title_value,
                        rows: JSON.stringify(this.rows_value),
                        category_id: this.category_value,
                        subcategories: this.subcategory_value,
                        content: this.template_value ? JSON.stringify(this.$refs.tc.data) : '',
                        template_id: this.template_value ? this.template_value.id : null,
                        published: this.published,
                        id: this.id,
                        published_from: this.published_from_value,
                        published_to: this.published_to_value,
                        type: this.type_value ? this.type_value.title : null,
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
                .get('/api/' + this.route + '/' + this.id)
                .then((response) => {
                    if (response.status == 200){
                        var data = response.data.data;
                        // this.data = data;
                        this.title_value = data.title;
                        this.published = data.published
                        if (data.rows){
                            console.log(data.rows);
                            this.rows_value = JSON.parse(data.rows);
                            console.log(JSON.stringify(this.rows_value));
                        }
                        this.category_value = data.categories;
                        this.subcategory_value = data.subcategories;
                        this.template_value = data.template;
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
                    // console.log("edit", this.data)
                    var data = this.data;
                    this.title_value = data.title;
                    this.template_value = data.template;
                    this.published = data.published
                    if (data.rows){
                        this.rows_value = JSON.parse(data.rows);
                    }
                    if (data.content){
                        console.log("datas", JSON.parse(data.content))
                        this.template_value.rows = data.content;
                    }
                    this.category_value = data.categories;
                    this.subcategory_value = data.subcategories;
                    this.published_from_value = data.published_from;
                    this.published_to_value = data.published_to;

                    var index = this.type_list.findIndex(function(type) {
                        return type.title ==  data.type;
                    });
                    this.type_value = this.type_list[index];
                } else {
                    this.get()
                }

            }
        },
        watch: {
            "category_value": function() {
                var subcategories = [];
                console.log("category value", this.category_value);
                if (this.category_value) {
                    this.category_value.forEach(function(category, index) {
                        category.subcategories.forEach(function(subcategory, index) {
                            subcategories.push(subcategory);
                        });
                    });
                    this.subcategories = subcategories;
                }
            }
        }
    }
</script>

<style>

</style>
