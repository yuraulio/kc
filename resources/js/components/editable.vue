<template>

<div class="">
        <modal name="save-modal" :resizable="true" height="auto" :adaptive="true" >
            <div class="row p-4">
                <div class="col-lg-12 p4">
                    <text-field
                        v-if="title"
                        title="Template Title"
                        @updatevalue="update_title"
                        :prop-value="data ? data.title : ''"
                        required=1
                    ></text-field>
                    <ul v-if="errors && errors['title']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false"><li class="parsley-required">{{errors['title'][0]}}</li></ul>
                </div>
                <div class="col-lg-12 text-center mt-3">
                    <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success rounded-pill waves-effect waves-light" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>
                    <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success rounded-pill waves-effect waves-light"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Edit</button>
                    <button @click="$modal.hide('save-modal')" type="button" class="btn btn-soft-danger rounded-pill waves-effect waves-light"><i class="fe-x me-1"></i> Cancel</button>
                </div>

                <!-- <div v-show="$refs.tc" class="col-lg-12">
                    <previewgrid :data="$refs.tc ? $refs.tc.data : []"></previewgrid>
                </div> -->
            </div>
        </modal>

        <div v-if="false">
        <div v-for="(val, index, key) in fields" :key="key" class="col-12 mb-1">
            <div v-for="(column, indr, key) in val.columns" :key="key">
                <div class="card" v-if="index != 'components'">
                    <div class="card-body pb-0">
                        <div v-if="val.columns.length > 1">
                            <ul class="nav nav-pills navtab-bg nav-justified">
                                <li v-for="(v, ind) in val.columns" :key="ind" class="nav-item">
                                    <a href="#home1" @click="setTabActive(index, ind)" data-bs-toggle="tab" aria-expanded="false" :class="'nav-link' + (v.active === true ? ' active' : '')">
                                        {{ v.template.title }} #{{ ind + 1 }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <h5 v-else class="card-title">{{ column.template.title }}</h5>
                    </div>
                    <div class="tab-content" style="padding-top: 0px" >
                        <div v-for="(vl, indx) in val.columns" :key="'tabpane' + indx" :class="'tab-pane ' + (vl.active === true ? ' active' : '')">
                            <div class="card-body row">
                                <multiput
                                    v-for="input in column.template.inputs"
                                    :key="input.key"
                                    :keyput="input.key"
                                    :label="input.label"
                                    :type="input.type"
                                    :value="input.value"
                                    :size="input.size"
                                    @inputed="inputed($event, input)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <tcdit v-if="fields" ref="tc" :pseudo="true" :mode="type" :predata="type == 'edit' ? predata : null"></tcdit>

        <div class="col-lg-12 text-center mt-3">
            <button v-if="type == 'new' || type == 'edit'" @click="$modal.show('save-modal'); $forceUpdate()" type="button" class="btn btn-soft-success rounded-pill waves-effect waves-light" ><i class="fe-check-circle me-1"></i> Review & Save</button>
            <button @click="$emit('updatemode', 'list')" type="button" class="btn btn-soft-danger rounded-pill waves-effect waves-light"><i class="fe-x me-1"></i> Cancel</button>
        </div>
</div> <!-- end card-->

</template>

<script>
import multidropdown from './inputs/multidropdown.vue';
import Tc from './tc.vue';
import Tcdit from './tcdit.vue';
import previewgrid from './previewgrid.vue';

export default {
  components: { multidropdown, Tc, Tcdit, previewgrid },
        props: {
            pageTitle: String,
            title: String,
            description: String,
            rows: String,
            category: String,
            route: String,
            type: String,
            id: Number,
            data: {},
            additionalTemplates: {},
            fields: {},
            predata: {}
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
            add(){
                console.log("row", this.$refs.tc)
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route + '/add',
                    {
                        title: this.title_value,
                        description: this.description_value,
                        rows: this.$refs.tc ? JSON.stringify(this.$refs.tc.data) : null,
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
                        rows: this.$refs.tc ? JSON.stringify(this.$refs.tc.data) : null,
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
console.log("fields", this.predata)
            if (this.type == "edit"){
                if (this.data) {
                    var data = this.data;
                    this.title_value = data.title;
                    this.description_value = data.description;
                    if (data.rows){
                        this.rows_value = JSON.parse(data.rows);
                    }
                    this.category_value = data.category_id;
                    console.log("templates", this.data)
                } else {
                    this.get()
                }

            }
        },
        watch: {
            /* additionalTemplates() {
                if (this.template_value) {
                    this.template_value.concat(this.additionalTemplates)
                } else {
                    this.template_value = this.additionalTemplates
                    console.log('TM', this.template_value)
                }
            } */
        }
    }
</script>
