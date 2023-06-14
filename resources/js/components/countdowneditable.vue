<template>
<div class="row">

        <div class="col-sm-12 mt-3 mb-3">
            <div class="page-title-box">
                <h4 v-if="type != 'new'" class="page-title d-inline-block">Edit countdown</h4>
                <h4 v-else class="page-title d-inline-block">New Countdown</h4>

                <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success waves-effect waves-light me-2 float-end" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Save</button>
                <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success waves-effect waves-light me-2 float-end"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Save</button>
                <a href="/countdown" type="button" class="btn btn-soft-secondary waves-effect waves-light me-2 float-end">Cancel</a>
            </div>
        </div>

        <div class="col-lg-9" >
            <template v-for="input in (type == 'edit' ? config.editInputs : config.addInputs)" >
                <multiput
                    :key="input.key"
                    :keyput="input.key"
                    :label="input.label"
                    :options="input.options"
                    :type="input.type"
                    :size="input.size"
                    :value="item[input.key]"
                    :existing-value="item[input.key]"
                    @inputed="inputed($event, input)"
                    @selectAll="selectAll($event)"
                    :multi="input.multi"
                    :taggable="input.taggable"
                    :fetch="input.fetch"
                    :route="input.route"
                    :placeholder="input.placeholder"
                >
                </multiput>
                <div v-if="input.key == 'should_visible'" class="row">
                    <div class="col-12">
                        <multidropdown
                                title="Delivery"
                                :multi="true"
                                @updatevalue="update_delivery"
                                :prop-value="delivery"
                                :fetch="false"
                                route="getDeliveries"
                                :data="deliveries"
                            ></multidropdown>

                            <multidropdown
                                title="Category"
                                :multi="true"
                                @updatevalue="update_category"
                                :prop-value="category_value"
                                :fetch="false"
                                :data="categories"
                            ></multidropdown>
                    </div>

                </div>
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

        </div>
        <div class="col-lg-3 mt-2">
            <div class="card ">
                <div class="card-body">

                    <!-- <h4 class="mb-2">{{pageTitle}}</h4> -->

                    <div class="row">
                        <div class="col-xl-12">



                            <div :key="'ck'"  class="form-check form-switch mb-3" style="cursor: pointer">
                                <input :key="'on'" @click="published = !published" :id="'cinput'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="'cinput'" :checked="published">
                                <label class="form-check-label" for="light-mode-check">Published</label>
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
            route: String,
            type: String,
            id: Number,
            data: {},
            config: {}
        },
        data() {
            return {
                delivery: null,
                category_value: [],
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
                categories: [],
                deliveries: []

            }
        },
        methods: {
            inputed($event, key) {
                this.$set(this.item, $event.key, $event.data);

            },
            selectAll($event){
                // data for save
                this.item.category = null
                this.item.delivery = null




                //data for selected render frontend
                this.category_value = null
                this.delivery = null

                if($event.key == 'delivery'){
                    this.item.delivery = this.deliveries
                    this.delivery = this.deliveries

                }else if($event.key == 'category'){
                    this.item.category = this.categories
                    this.category_value = this.categories

                    console.log(this.item.category)
                }
            },
            update_delivery(value = []) {

                if(value == null){
                    value = []
                }

                this.item.delivery = value;
            },
            update_category(value){

                this.item.category = value;
                // this.subcategory_value = [];
            },
            // update_published_from(value){
            //     this.item.published_from = value;
            // },
            // update_published_to(value){
            //     this.item.published_to = value;
            // },
            setCategories(data){
                console.log('trig cat')

                if(typeof(data.delivery) !== 'undefined' && data.delivery.length != 0){

                    let del = []

                    data.delivery.forEach(function(delivery, index) {

                        let obj = {
                            id: delivery.id,
                            title: delivery.name
                        }
                        del.push(obj);

                    });

                    this.delivery = del;



                }

                this.category_value = [];
                if(typeof(data.category) !== 'undefined' && data.category.length != 0){
                    let cat = []

                    data.category.forEach(function(category, index) {

                        let obj = {
                            id: category.id,
                            title: category.name
                        }
                        cat.push(obj);

                    });

                    this.category_value = cat;

                }

                //this.selectRadio()
            },
            getCategories(){
                axios
                .get('/api/getCategories')
                .then((response) => {

                    if (response.status == 200){
                        var data = response.data.data;

                        //console.log('data fetch: ', data)
                        this.categories = data

                        this.selectRadio()
                    }
                })
                .catch((error) => {
                    console.log(error)
                });


            },
            getDeliveries(){
                axios
                .get('/api/getDeliveries')
                .then((response) => {

                    if (response.status == 200){
                        var data = response.data.data;

                        this.deliveries = data

                        this.selectRadio()

                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            },
            add(){
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
                        countdown_to: this.item.countdown_to,
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
                        //this.$emit('created', response.data.data);
                        //this.$emit('updatemode', 'list');
                        this.$toast.success('Created Successfully!')
                        window.location="/countdown/" + response.data.data.id;

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

                // if(this.item.delivery !== undefined && this.item.delivery[0] !== undefined){
                //     this.item.delivery = this.item.delivery[0]
                // }

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
                if(this.id !== undefined){
                    axios
                        .get('/api/' + this.route + '/' + this.id)
                        .then((response) => {
                            if (response.status == 200){
                                var data = response.data.data;

                                this.item = data

                                this.setCategories(data);

                                this.loader = false;
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                        });


                }

            },
            selectRadio(){

                if(this.item.category !== undefined){


                    if(this.categories.length != 0 && this.item.category.length == this.categories.length){
                        this.item['should_visible'] = 'category'

                    }else if(this.deliveries.length != 0 && this.item.delivery.length == this.deliveries.length){

                        this.item['should_visible'] = 'delivery'
                    }
                }
            }

        },
        mounted() {

            this.getDeliveries()
            this.getCategories()


            if (this.data) {
                console.log('trig data')

                this.item = this.data
                this.setCategories(this.data)


                this.loader = false;
            } else {
                console.log('trig mounten')

                this.get()

            }





        },
        watch: {
        }
    }
</script>

<style scoped>
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
