<template>

<div class="">
        <modal name="save-modal" :resizable="true" height="auto" :adaptive="true" >
            <div class="row p-4">
                <div class="col-lg-12 p4">
                    <text-field
                        v-if="title"
                        title="Template Title"
                        @updatevalue="update_title"
                        :prop-value="title_value"
                        required=1
                    ></text-field>
                    <ul v-if="errors && errors['title']" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false"><li class="parsley-required">{{errors['title'][0]}}</li></ul>
                </div>

                <div class="col-lg-12 p4">
                    <multidropdown
                        title="Type"
                        :multi="false"
                        @updatevalue="update_type"
                        required=0
                        :fetch="true"
                        route="getPageTypes"
                        setlabel="description"
                        placeholder="Select type"
                        :prop-value="templateType"
                    ></multidropdown>
                </div>

                <div class="col-lg-12 p4">
                    <multidropdown
                        title="Dynamic"
                        :multi="false"
                        @updatevalue="update_dynamic"
                        :prop-value="dynamic"
                        :fetch="false"
                        :data="[
                            {
                                title: 'Static page',
                                id: false
                            },
                            {
                                title: 'Dynamic page',
                                id: true
                            }
                        ]"
                        placeholder="Select one"
                        marginbottom="mb-0"
                        :allowEmpty="false"
                    ></multidropdown>
                </div>

                <div class="col-lg-12 text-center mt-3">
                    <button v-if="type == 'new'" @click="add()" type="button" class="btn btn-soft-success  waves-effect waves-light" :disabled="loading"><i v-if="!loading" class="fe-check-circle me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>
                    <button v-if="type == 'edit'" :disabled="loading" @click="edit()" type="button" class="btn btn-soft-success  waves-effect waves-light"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>
                    <button @click="$modal.hide('save-modal')" type="button" class="btn btn-soft-secondary  waves-effect waves-light"><i class="fe-x me-1"></i> Cancel</button>
                </div>

                <!-- <div v-show="$refs.tc" class="col-lg-12">
                    <previewgrid :data="$refs.tc ? $refs.tc.data : []"></previewgrid>
                </div> -->
            </div>
        </modal>

        <tcdit
            v-if="show"
            ref="tc"
            :pseudo="true"
            :mode="type"
            :predata="type == 'edit' ? predata : null"
            :pageTitle="pageTitle"
            @save="save()"
        ></tcdit>
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
                show: false,
                dynamic: {
                    title: 'Static page',
                    id: false
                },
                templateType: null,
                type_list: [],
            }
        },
        methods: {
            update_title(value){
                this.title_value = value;
            },
            update_dynamic(value) {
                this.dynamic = value;
            },
            update_type(value) {
                this.templateType = value;
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
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/' + this.route,
                    {
                        title: this.title_value,
                        dynamic: this.dynamic.id,
                        type: this.templateType.title,
                        rows: this.$refs.tc ? JSON.stringify(this.$refs.tc.data) : null,
                    }
                )
                .then((response) => {
                    if (response.status == 201){
                        this.$toast.success('Created Successfully!');
                        window.location = "/templates";
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
                        dynamic: this.dynamic.id,
                        type: this.templateType.title,
                        rows: this.$refs.tc ? JSON.stringify(this.$refs.tc.data) : null,
                        id: this.id,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.$toast.success('Edited Successfully!')
                        this.loading = false;
                        window.location = "/templates";
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
                        this.templateType = data.type;
                        if (!data.dynamic) {
                            this.dynamic = {
                                title: 'Static page',
                                id: false
                            };
                        } else {
                            this.dynamic = {
                                title: 'Dynamic page',
                                id: true
                            };
                        }
                        if (data.rows){
                            this.rows_value = JSON.parse(data.rows);
                        }

                        this.getTypes();

                        this.show = true;
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            },
            save() {
                this.$modal.show('save-modal');
                $forceUpdate();
            },
            getTypes() {
                axios
                .get('/api/getPageTypes')
                .then((response) => {
                    if (response.status == 200){
                        this.type_list = response.data.data;
                        this.setTemplateType();
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            },
            setTemplateType() {
                if (typeof this.templateType === 'string' || this.templateType instanceof String) {
                    var title = this.templateType;
                    var index = this.type_list.findIndex(function(type) {
                        return type.title ==  title;
                    });
                    this.templateType = this.type_list[index];
                }
            }
        },
        mounted() {
            if (this.type == "edit"){
                if (this.data) {
                    var data = this.data;
                    this.title_value = data.title;
                    this.description_value = data.description;
                    this.templateType = data.type;
                    if (data.rows){
                        this.rows_value = JSON.parse(data.rows);
                    }
                    this.category_value = data.category_id;
                    this.show = true;

                    this.getTypes();
                } else {
                    this.get()
                }

            } else {
                this.title_value = 'New Template';
                this.show = true;
            }
        },
        watch: {
        }
    }
</script>
