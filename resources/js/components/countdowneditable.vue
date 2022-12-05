<template>
<div class="row">

        <div class="col-sm-12 mt-3 mb-3">
            <div class="page-title-box">
                <h4 v-if="title" class="page-title d-inline-block">Edit page</h4>
                <h4 v-else class="page-title d-inline-block">New page</h4>

                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success waves-effect waves-light me-2 float-end" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Create</button>
                <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success waves-effect waves-light me-2 float-end"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Save</button>
                <a href="/pages" type="button" class="btn btn-soft-secondary waves-effect waves-light me-2 float-end">Cancel</a>
            </div>
        </div>

        <div class="col-lg-9" >

            <template v-for="input in (type == 'edit' ? config.editInputs : config.addInputs)" >
                <multiput
                    :key="input.key"
                    :keyput="input.key"
                    :label="input.label"
                    :type="input.type"
                    :size="input.size"
                    :value="item[input.key]"
                    :existing-value="item[input.key]"
                    @inputed="inputed($event, input)"
                    :multi="input.multi"
                    :taggable="input.taggable"
                    :fetch="input.fetch"
                    :route="input.route"
                    :placeholder="input.placeholder"
                >
                </multiput>
                <ul v-if="errors && errors[input.key]" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                    <li class="parsley-required">{{ errors[input.key][0] }}</li>
                </ul>

                <template v-if="errors && input.key == 'subcategories'">
                    <template v-for="(errorInput, key) in item.subcategories">
                        <ul v-if="errors && errors[input.key + '.' + key]" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                            <li class="parsley-required">{{ fixError(errors[input.key + '.' + key][0], input.key + '.' + key, errorInput.title) }}</li>
                        </ul>
                    </template>
                </template>
            </template>



            <!-- <template v-if="template_value && loader == false">
                <tcedit
                    :mode="type"
                    ref="tc"
                    :pseudo="false"
                    :predata="data.content"
                    :collapseAllProp="collapseAll"
                    :slug="slug_value"
                    class="mb-3"
                ></tcedit>
            </template> -->
            <!-- <template v-else>
                <div style="margin-top: 150px" class="text-center">
                    <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
                </div>
            </template> -->

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

                    <!-- <h4 class="mb-2">{{pageTitle}}</h4> -->

                    <div class="row">
                        <div class="col-xl-12">

                            <multidropdown
                                title="Delivery"
                                :multi="false"
                                @updatevalue="update_delivery"
                                @prop-value="delivery"
                                :fetch="true"
                                route="getDeliveries"
                            ></multidropdown>

                            <multidropdown
                                title="Category"
                                :multi="true"
                                @updatevalue="update_category"
                                :fetch="true"
                                route="getCategories"
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

                            <!-- this is now category group, is hidden -->
                            <!-- <multidropdown
                                title="Categories"
                                @updatevalue="update_category"
                                :prop-value="category_value"
                                route="categories"
                                class="visually-hidden"
                            ></multidropdown> -->

                            <!-- this is now categories, used to be subcategories -->
                            <!-- <multidropdown
                                title="Categories"
                                @updatevalue="update_subcategory"
                                :prop-value="subcategory_value"
                                :fetch="false"
                                :data="subcategories"
                            ></multidropdown> -->


                            <div :key="'ck'"  class="form-check form-switch mb-3" style="cursor: pointer">
                                <input :key="'on'" @click="published = !published" :id="'cinput'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="'cinput'" :checked="published">
                                <label class="form-check-label" for="light-mode-check">Published</label>
                            </div>



                            <!--
                            <div class="form-check form-switch mb-3" style="cursor: pointer">
                                <input @click="dynamic = !dynamic" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :checked="dynamic">
                                <label class="form-check-label">Dynamic page</label>
                            </div>
                            -->

                            <datepicker-component
                                title="Publish from"
                                @updatevalue="update_published_from"
                                :prop-value="item['published_from']"
                            ></datepicker-component>

                            <datepicker-component
                                title="Publish to"
                                @updatevalue="update_published_to"
                                :prop-value="item['published_to']"
                            ></datepicker-component>

                            <!--
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
                            -->
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
import Multiput from './inputs/multiput.vue';

export default {
        components: {
            multidropdown,
            gicon,
            Multiput

        },
        props: {
            pageTitle: String,
            title: String,
            category: String,
            route: String,
            type: String,
            id: Number,
            data: {},
            additionalTemplates: {},
            config: {}
        },
        data() {
            return {
                delivery: null,
                errors: null,
                test: null,
                loading: false,
                loader: true,
                published: true,
                button_status: false,
                lodash: _,
                item: {},
                published_from_value: null,
                published_to_value: null,

            }
        },
        methods: {
            inputed($event, key) {
                this.$set(this.item, $event.key, $event.data);

            },
            update_delivery(value) {
                console.log(value)
                this.item.delivery = value;
            },
            update_category(value){
                this.item.category = value;
                // this.subcategory_value = [];
            },
            update_published_from(value){
                this.item.published_from = value;
            },
            update_published_to(value){
                this.item.published_to = value;
            },
            setCategories(){

                //this.delivery = this.item.delivery;

                console.log('setCategory this item')
                console.log(this.item)
                console.log('set category method')
                //this.delivery = this.item.delivery

                // this.delivery.id = this.item.delivery[0].id
                // this.delivery.title = 'test'





                // reset categorise and subcategories
                // this.categories = [];
                // this.subcategories = [];
                // this.category_value = [];
                //this.subcategory_value = [];

                // get categories and subcategories based on the page type
                // axios
                // .get('/api/category_group/' + this.type_value.id)
                // .then((response) => {
                //     this.categories = response.data.data;
                //     this.category_value = this.categories;

                //     var subcategories = [];
                //     if (this.category_value) {
                //     this.category_value.forEach(function(category, index) {
                //         if (category.subcategories) {
                //             category.subcategories.forEach(function(subcategory, index) {
                //                 subcategories.push(subcategory);
                //             });
                //         }
                //     });
                //     this.subcategories = subcategories;
                // }
                // })
                // .catch((error) => {
                //     console.log(error)
                // });

            },
            add(){
                console.log('published: ', this.published)
                console.log('on save :', this.item)
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route,
                    {
                        title: this.item.title,
                        content: this.item.content ? this.item.content : '',
                        //categories: this.category_value,

                        published_from: this.item.published_from,
                        published_to: this.item.published_to,
                        countdown_from: this.item.countdown_from,
                        published: this.published,
                        delivery: this.item.delivery,
                        category: this.item.category,
                        button_status: this.item.button_status ? this.item.button_status : this.button_status,
                        button_title: this.item.button_title

                    }
                )
                .then((response) => {
                    if (response.status == 201){
                        //this.$emit('refreshcategories');
                        this.$emit('created', response.data.data);
                        this.$emit('updatemode', 'list');
                        this.$toast.success('Created Successfully!')
                        //window.location="/countdown/" + response.data.data.id;
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

            edit() {

                this.item.published = this.published

                axios
                    .patch('/api/' + this.route + '/' + this.id, this.item)
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
                        this.item.content = data.content
                        this.item.title = data.title;
                        this.published = data.published
                        //this.category = data.categories;

                        this.setCategories();

                        this.loader = false;
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            },

        },
        mounted() {

            if (this.data) {

                this.item = this.data

                if(this.data.delivery !== undefined && this.data.delivery.length != 0){
                    this.delivery = this.data.delivery

                    this.delivery = {
                        id: this.data.delivery[0].id,
                        title: this.data.delivery[0].name
                    }

                    // console.log('mounted')
                    console.log('TYPE DELIVERY')
                    console.log(this.delivery)

                }





                // this.item.title = data.title;
                // this.item.published = data.published;
                //this.item.content = data.content

                //this.category_value = data.categories;


               // this.published_from_value = data.published_from;
                //this.published_to_value = data.published_to;

                // TODO LOAD DELIVERY
                //this.setCategories();



                this.loader = false;
            } else {
                this.get()
            }

        },
        watch: {
            // "category_value": function() {
            //     var subcategories = [];
            //     if (this.category_value) {
            //         this.category_value.forEach(function(category, index) {
            //             if (category.subcategories) {
            //                 category.subcategories.forEach(function(subcategory, index) {
            //                     subcategories.push(subcategory);
            //                 });
            //             }
            //         });
            //         this.subcategories = subcategories;
            //     }
            // }
        }
    }
</script>

<style scoped>
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
