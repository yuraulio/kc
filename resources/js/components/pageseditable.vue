<template>
<div class="row">

        <div class="col-sm-12 mt-3 mb-3">
            <div class="page-title-box">
                <h4 v-if="title_value" class="page-title d-inline-block">Edit page</h4>
                <h4 v-else class="page-title d-inline-block">New page</h4>

                <button :disabled="loading" @click="changeMode()" type="button" class="btn btn-soft-info waves-effect waves-light float-end">Simple Mode</button>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body pb-0">
                    <text-field
                        v-if="title"
                        title="Administration Title"
                        @updatevalue="update_title"
                        :prop-value="title_value"
                        required=1
                    ></text-field>

                    <ul v-if="errors && errors['title']" class="parsley-errors-list filled mb-3" id="parsley-id-7" aria-hidden="false"><li class="parsley-required">{{errors['title'][0]}}</li></ul>

                    <text-field
                        title="Slug"
                        @updatevalue="update_slug"
                        :prop-value="slug_value"
                        class="visually-hidden"
                    ></text-field>
                </div>
            </div>
        </div>

        <div class="col-lg-9" >
            <template v-if="template_value && loader == false">
                <tcedit
                    :mode="type"
                    ref="tc"
                    :pseudo="false"
                    :predata="data.content"
                    :collapseAllProp="collapseAll"
                    :slug="slug_value"
                    class="mb-3"
                ></tcedit>
            </template>
            <template v-else>
                <div style="margin-top: 150px" class="text-center">
                    <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
                </div>
            </template>

            <!--
            <div v-else class="card">
                <div class="card-body p-4">
                    <div class="error-ghost text-center">
                        <gicon></gicon>
                    </div>
                    <div class="text-center">
                        <h3 class="mt-4">Select Template go get started</h3>
                        <multidropdown
                            title="Template"
                            :multi="false"
                            @updatevalue="update_template"
                            :prop-value="template_value"
                            route="templatesAll"
                        ></multidropdown>
                    </div>
                </div>
            </div>
            -->
        </div>
        <div class="col-lg-3">
            <div class="card ">
                <div class="card-body">

                    <button @click="collapseAll = !collapseAll" class="btn btn-sm btn-soft-secondary w-100 mb-3" type="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
                        <template v-if="collapseAll == true">
                            Collapse All
                        </template>
                        <template v-else>
                            Expand All
                        </template>

                    </button>

                    <h4 class="mb-2">{{pageTitle}}</h4>

                    <div class="row">
                        <div class="col-xl-12">

                            <multidropdown
                                title="Type"
                                :multi="false"
                                @updatevalue="update_type"
                                :prop-value="type_value"
                                :fetch="false"
                                :data="type_list"
                            ></multidropdown>

                            <!--
                            <multidropdown
                                title="Template"
                                :key="template_value ? template_value.id : 'temmult'"
                                :multi="false"
                                @updatevalue="update_template"
                                :prop-value="template_value"
                                route="templates"
                            ></multidropdown>
                            -->

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

                            <template v-if="!dynamic">
                                <div :key="'ck'"  class="form-check form-switch mb-3" style="cursor: pointer">
                                    <input :key="'on'" @click="published = !published" :id="'cinput'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="'cinput'" :checked="published">
                                    <label class="form-check-label" for="light-mode-check">Published</label>
                                </div>

                                <div class="form-check form-switch mb-3" style="cursor: pointer">
                                    <input @click="indexed = !indexed" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :checked="indexed">
                                    <label class="form-check-label">Indexed</label>
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-center">
                                    <span class="badge bg-warning mb-3">Dynamic page</span>
                                </div>
                            </template>

                            <!--
                            <div class="form-check form-switch mb-3" style="cursor: pointer">
                                <input @click="dynamic = !dynamic" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :checked="dynamic">
                                <label class="form-check-label">Dynamic page</label>
                            </div>
                            -->

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
                                    <button v-if="type != 'new'" @click="preview()" class="btn btn-block btn-soft-warning waves-effect waves-light m-1">
                                        Live preview
                                    </button>
                                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" :disabled="!template_value" @click="rearange()" class="btn btn-block btn-soft-info waves-effect waves-light m-1">Pseudo preview</button>
                                    <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success waves-effect waves-light m-1" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Create</button>
                                    <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success waves-effect waves-light m-1"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>

                                    <a href="/pages" type="button" class="btn btn-soft-secondary waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</a>
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
// import Tcedit from './tcdit.vue';
import gicon from './gicon.vue';
import slugify from '@sindresorhus/slugify';

export default {
  components: { multidropdown, gicon },
        props: {
            pageTitle: String,
            title: String,
            rows: String,
            category: String,
            route: String,
            type: String,
            id: Number,
            uuid: String,
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
                loader: true,
                published: false,
                indexed: true,
                dynamic: false,
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
                    },
                    {
                        'id': 6,
                        'title':'Knowledge'
                    },
                    {
                        'id': 7,
                        'title':'City page'
                    }
                ],
                slug_value: null,
                collapseAll: true,
            }
        },
        methods: {
            rearange() {
                eventHub.$emit('component-rearange', false)
            },
            update_title(value){
                this.title_value = value;
                this.slug_value = slugify(value);
            },
            update_rows(value){
                this.rows_value = value;
            },
            update_slug(value){
                this.slug_value = value;
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
                this.template_value = value;
                if (this.type == "edit") {
                    this.template_value.rows = this.data.content;
                }
                this.$forceUpdate();
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
                        slug: this.slug_value,
                        rows: JSON.stringify(this.rows_value),
                        content: this.template_value ? JSON.stringify(this.$refs.tc.data) : '',
                        categories: this.category_value,
                        subcategories: this.subcategory_value,
                        template_id: this.template_value ? this.template_value.id : null,
                        published: this.published,
                        indexed: this.indexed,
                        dynamic: this.template_value.dynamic,
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
                        window.location="/page/" + response.data.data.id;
                    }
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                    this.$toast.error("Failed to create. " + this.errors[Object.keys(this.errors)[0]]);
                });
            },
            edit(){
                this.loading = true;
                this.errors = null;
                axios
                .patch('/api/' + this.route + '/' + this.id,
                    {
                        title: this.title_value,
                        rows: JSON.stringify(this.rows_value),
                        categories: this.category_value,
                        subcategories: this.subcategory_value,
                        content: this.template_value ? JSON.stringify(this.$refs.tc.data) : '',
                        template_id: this.template_value ? this.template_value.id : null,
                        published: this.published,
                        indexed: this.indexed,
                        dynamic: this.dynamic,
                        id: this.id,
                        published_from: this.published_from_value,
                        published_to: this.published_to_value,
                        type: this.type_value ? this.type_value.title : null,
                        slug: this.slug_value,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.route == 'categories' ? this.$emit('edited', response.data) : this.$emit('refreshcategories');
                        // this.$emit('updatemode', 'list');
                        this.$toast.success('Saved Successfully!');
                        this.loading = false;
                    }
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                    this.errors = error.response.data.errors;
                    this.$toast.error("Failed to save. " + this.errors[Object.keys(this.errors)[0]]);
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
                        this.published = data.published;
                        this.indexed = data.indexed;
                        this.dynamic = data.dynamic;
                        if (data.rows){
                            this.rows_value = JSON.parse(data.rows);
                        }
                        this.category_value = data.categories;
                        this.subcategory_value = data.subcategories;
                        this.template_value = data.template;

                        this.loader = false;
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            },
            changeMode() {
                this.data.title = this.title_value;
                this.data.slug = this.slug_value;
                this.data.published = this.published;
                this.data.content = this.$refs.tc.data;
                this.$emit('changeMode', this.data);
            },
            preview() {
                window.open(
                    process.env.MIX_APP_URL + '/__preview/' + this.data.uuid + '?p=HEW7M9hd8xY2gkRk',
                    '_blank'
                );
            }
        },
        mounted() {
            if (this.data) {
                var data = this.data;
                this.title_value = data.title;
                this.type_value = data.type;
                this.template_value = data.template;
                this.published = data.published;
                this.indexed = data.indexed;
                this.dynamic = data.template.dynamic;
                if (data.rows){
                    this.rows_value = JSON.parse(data.rows);
                }
                // if (data.content){
                //     this.template_value.rows = data.content;
                // }
                this.category_value = data.categories;
                this.subcategory_value = data.subcategories;
                this.published_from_value = data.published_from;
                this.published_to_value = data.published_to;

                var index = this.type_list.findIndex(function(type) {
                    return type.title ==  data.type;
                });
                this.type_value = this.type_list[index];
                this.slug_value = data.slug;

                this.loader = false;
            } else {
                this.get()
            }

            eventHub.$on('updateslug', ((value) => {
                this.slug_value = value;
            }));
        },
        watch: {
            "category_value": function() {
                var subcategories = [];
                if (this.category_value) {
                    this.category_value.forEach(function(category, index) {
                        if (category.subcategories) {
                            category.subcategories.forEach(function(subcategory, index) {
                                subcategories.push(subcategory);
                            });
                        }
                    });
                    this.subcategories = subcategories;
                }
            }
        }
    }
</script>

<style scoped>
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
