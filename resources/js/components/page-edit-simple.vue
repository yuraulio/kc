<style scoped>
    .nav-link {
        color: #6c757d;
    }
    .nav-link.active {
        border-bottom: 4px solid #6658dd;
    }
    .card {
        border-radius: 25px;
    }
    .title-input {
        background: transparent;
        outline: none;
        border: 0px;
        box-shadow: none;
        font-size: 23px;
        width: 100%;
    }

    .component-tabs.settings>div>.settings-icon {
        color: #6c757d!important;
    }

    .component-tabs.main .main-component-tab {
        display: inline-block;
    }
    .component-tabs.main .settings-component-tab {
        display: none;
    }
    .component-tabs.settings .main-component-tab {
        display: none;
    }
    .component-tabs.settings .settings-component-tab {
        display: inline-block;
    }

 
    
    .column-navigation {
        min-width: unset;
        transform: translateY(-3px);
        font-size: 12px;
    }
    .column-navigation a {
        padding: 2px 5px;
    }
    .column-navigation a.active{
        border-bottom: 2px solid #98a6ad;
    }
    .column-navigation-margin {
        margin-right: 26px;
    }
    .add-component-icon {
        font-size: 32px;

    }

</style>

<template>
<div>
    <div class="page-edit-simple" v-if="page && page.template">
        <div class="row">
            <div class="col-md-12 align-self-center">
                <div class="page-title-box mt-3 row">
                    <div class="col-md-6">
                        <!--
                        <input @input="setSlug()" v-model="page.title" class="d-inline-block title-input mb-3">
                        -->
                    </div>
                    <div class="col-md-6">
                        <button :disabled="loading" @click="changeMode()" type="button" class="btn btn-soft-info waves-effect waves-light float-end ms-2 mb-3"><i class="dripicons-toggles me-1" style="transform: translateY(2px);"></i>Page builder</button>
                        <button :disabled="loading" @click="type == 'new' ? add() : edit()" type="button" class="btn btn-soft-success waves-effect waves-light float-end ms-2 mb-3"><i v-if="!loading" class="mdi mdi-square-edit-outline me-1"></i><i v-else class="fas fa-spinner fa-spin"></i> Save</button>

                        <template v-if="type != 'new'">
                            <button v-if="type != 'new'" :disabled="loading" @click="preview()" type="button" class="btn btn-soft-warning waves-effect waves-light float-end ms-2 mb-3"><i class="dripicons-preview me-1" style="transform: translateY(2px);"></i>Preview</button>
                            
                            <template v-if="!page.dynamic">
                                <template v-if="page.published">
                                    <input @click="page.published = !page.published" type="checkbox" class="btn-check float-end" id="btn-check-outlined" autocomplete="off" :checked="page.published">
                                    <label class="btn btn-outline-primary float-end ms-2 mb-3" for="btn-check-outlined"><i class="dripicons-checkmark me-1" style="transform: translateY(2px);"></i>Published</label>
                                </template>
                                <template v-else>
                                    <input @click="page.published = !page.published" type="checkbox" class="btn-check float-end" id="btn-check-outlined" autocomplete="off" :checked="page.published">
                                    <label class="btn btn-outline-primary float-end ms-2 mb-3" for="btn-check-outlined"><i class="dripicons-cross me-1" style="transform: translateY(2px);"></i> Unpublished</label>
                                </template>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2 align-self-center">
                <ul class="nav mb-3">
                    <li class="nav-item">
                        <a @click="tab = 'Content'" :class="'nav-link ' + (tab == 'Content' ? 'active' : '')" href="#">General</a>
                    </li>
                    <li class="nav-item">
                        <a @click="tab = 'Meta'" :class="'nav-link ' + (tab == 'Meta' ? 'active' : '')" href="#">SEO</a>
                    </li>
                </ul>
            </div>
        </div>

        <template v-for="(row, row_index) in content" v-if="content">
            <template v-for="(column, column_index) in row.columns">
                <template v-if="column.template.simple_view == true && column.template.tab == tab">
                    <div :class="'row ' + 'row' + row_index">
                        <div class="col-md-8 offset-md-2 align-self-center">
                            <div :class="'card ' + 'column' + column_index" :style="(column_index == 0 ? '' : 'display: none;')">
                                <div class="card-body">
                                    <div :class="'row component-tabs ' + (column.tab == 'settings' ? 'settings' : 'main')">
                                        <div class="col-12">
                                            <p class="text-muted d-inline-block">{{ column.template.title }}</p>
                                            <i v-if="column.template.removable !== false" @click="removeRow(row_index)" class="dripicons-trash text-muted float-end ms-2 cursor-pointer" title="Delete component"></i>
                                            <i v-if="settingsExist(column)" @click="column.tab == 'settings' ? column.tab = 'main' : column.tab = 'settings'" :class="'settings-icon text-muted float-end ms-2 ' + (column.template.simple_view_settings_icon ? column.template.simple_view_settings_icon : 'dripicons-gear')"></i>
                                            <i @click="column.template.mobile = !column.template.mobile" :class="'text-muted float-end ms-2 cursor-pointer ' + (column.template.mobile ? ' dripicons-device-mobile ' : ' dripicons-device-desktop ')" title="Show on mobile"></i>
                                            <template v-if="simpleColumnCount(row.columns) > 1">
                                                <ul :class="'nav column-navigation d-inline-block float-end mb-0 nav-row' + row_index + ' ' + (settingsExist(column) == false ? 'column-navigation-margin' : '')">
                                                    <template v-for="(column, column_index) in row.columns">
                                                        <li :class="'nav-item d-inline-block nav-column' + column_index">
                                                            <a @click="showColumnTab(row_index, column_index)" :class="'nav-link text-muted ' + (column_index == 0 ? 'active' : '')" href="#!">Col {{column_index + 1}}</a>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </template>
                                        </div>

                                        <multiput
                                            v-for="(input, input_index) in column.template.inputs"
                                            v-show="!column.template.dynamic || input.dynamic"
                                            v-if="input.simple_view != false"
                                            :key="input.key"
                                            :keyput="input.key + row_index + column_index + input_index"
                                            :label="input.label"
                                            :type="input.type"
                                            :value="input.key == 'meta_slug' ? page.slug : input.value"
                                            :tabsProp="input.tabs ? input.tabs : []"
                                            :size="input.size"
                                            :width="input.width"
                                            @inputed="inputed($event, input)"
                                            @inputedTabs="inputedTabs($event, input)"
                                            :route="input.route"
                                            :multi="false"
                                            :existingValue="input.value"
                                            :uuid="$uuid.v4()"
                                            :inputs="input.inputs"
                                            :class="input.main ? 'main-component-tab' : 'settings-component-tab'"
                                            :imageVersion="input.image_version ? input.image_version : null"
                                            :hideAltText="true"
                                        />

                                        <!-- preview -->
                                        <template v-if="column.component == 'youtube'">
                                            <div v-show="!column.template.dynamic" class="col-12">
                                                <label class="form-label mt-2">Preview</label>
                                                <div class="text-center">
                                                    <iframe
                                                        :width="'100%'"
                                                        :height="'600'"
                                                        :src="'https://www.youtube.com/embed/' + findInputValue(column.template.inputs, 'youtube_embed')"
                                                        title="YouTube video player"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen
                                                    ></iframe>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

        <div v-if="tab != 'Meta'" class="text-center">
            <i @click.prevent="addCustomComponent" class="dripicons-plus add-component-icon cursor-pointer"></i>
        </div>

        <div v-if="isBasicEditorEmpty() && tab != 'Meta'" class="text-center mt-3">
            <p>
                There are no basic component to edit. Add a new basic component or switch to Page builder.
            </p>
            <button :disabled="loading" @click="changeMode()" type="button" class="btn btn-soft-info waves-effect waves-light"><i class="dripicons-toggles me-1" style="transform: translateY(2px);"></i>Page builder</button>
        </div>

        <component-modal
            :row="null"
            :column="null"
            name="simple"
        ></component-modal>
    </div>

    <div v-else class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <div class="card-body p-4">
                    <div class="error-ghost text-center">
                        <gicon></gicon>
                    </div>

                    <div class="text-center">
                        <h3 class="mt-4">Select Template and Type go get started</h3>
                        
                        <!--
                        <text-field
                            title="Administration Title"
                            @updatevalue="setPageTitle"
                            prop-value=""
                            required=1
                        ></text-field>
                        -->

                        <multidropdown
                            title="Template"
                            :multi="false"
                            @updatevalue="setTemplate"
                            route="templates?per_page=0"
                            required=1
                        ></multidropdown>

                        <multidropdown
                            title="Type"
                            :multi="false"
                            @updatevalue="setPageType"
                            required=1
                            :fetch="true"
                            route="getPageTypes"
                        ></multidropdown>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import gicon from './gicon.vue';
import multidropdown from './inputs/multidropdown.vue';
import templateComponents from './template-components.json'
import slugify from '@sindresorhus/slugify';

    export default {
        components: { multidropdown, gicon },
        props: {
            pageId: null,
            page: null,
            content: null,
            type: null
        },
        data() {
            return {
                tab: "Content",
                loading: false,
                template: null,
                pageType: null,
                pageTitle: "",
            }
        },
        methods: {
            findInputValue(inputs, key){
                var index = inputs.findIndex(function(input) {
                    return input.key == key;
                });
                return inputs[index].value;
            },
            inputed($event, value) {
                this.$set(value, 'value', $event.data);
            },
            changeMode() {
                this.page.content = this.content;
                this.page.rows = JSON.stringify(this.content);
                if (this.page.published == undefined) {
                    this.page.published = false;
                }
                if (this.page.indexed == undefined) {
                    this.page.indexed = true;
                }
                if (this.page.dynamic == undefined) {
                    this.page.dynamic = false;
                }
                this.$emit('changeMode', this.page);
            },
            add(){
                this.errors = null;
                this.loading = true;
                axios
                .post('/api/pages',
                    {
                        title: this.page.title,
                        categories: this.page.categories,
                        subcategories: this.page.subcategories,
                        content: JSON.stringify(this.content),
                        template_id: this.page.template.id,
                        published: false,
                        indexed: true,
                        dynamic: this.page.template.dynamic,
                        published_from: this.page.published_from,
                        published_to: this.page.published_to,
                        type: this.page.type,
                        slug: this.page.slug,

                    }
                )
                .then((response) => {
                    if (response.status == 201){
                        this.$toast.success('Created Successfully!');
                        window.location="/page/" + response.data.data.id;
                    }
                    this.loading = false;
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    this.$toast.error("Failed to create. " + this.errors[Object.keys(this.errors)[0]]);
                    this.loading = false;
                });
            },
            edit() {
                if(this.pageId == 4 || this.pageId == 6){
                    let user = 'users';
                  
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to update " + user + "' term?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {
                          this.editPageUpdateTerms(0)
                        }else{
                           this.editPageUpdateTerms(1)
                        }
            
                    })

                
                }else{
                    this.editPageNotUpdateTerms()
                }
            },

            editPageUpdateTerms(terms_val) {

                this.loading = true;
                this.errors = null;
                axios
                .patch('/api/pages/' + this.page.id,
                    {
                        title: this.page.title,
                        categories: this.page.categories,
                        subcategories: this.page.subcategories,
                        content: JSON.stringify(this.content),
                        template_id: this.page.template.id,
                        published: this.page.published,
                        indexed: this.page.indexed,
                        dynamic: this.page.dynamic,
                        id: this.page.id,
                        published_from: this.page.published_from,
                        published_to: this.page.published_to,
                        type: this.page.type,
                        slug: this.page.slug,
                        terms_val:terms_val,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.$toast.success('Saved Successfully!')
                        this.loading = false;
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                    this.$toast.error("Failed to save. " + this.errors[Object.keys(this.errors)[0]]);
                });
            },

            editPageNotUpdateTerms() {

                this.loading = true;
                this.errors = null;
                axios
                .patch('/api/pages/' + this.page.id,
                    {
                        title: this.page.title,
                        categories: this.page.categories,
                        subcategories: this.page.subcategories,
                        content: JSON.stringify(this.content),
                        template_id: this.page.template.id,
                        published: this.page.published,
                        indexed: this.page.indexed,
                        dynamic: this.page.dynamic,
                        id: this.page.id,
                        published_from: this.page.published_from,
                        published_to: this.page.published_to,
                        type: this.page.type,
                        slug: this.page.slug,

                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.$toast.success('Saved Successfully!')
                        this.loading = false;
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                    this.$toast.error("Failed to save. " + this.errors[Object.keys(this.errors)[0]]);
                });
            },
            preview() {
                this.loading = true;
                this.errors = null;
                axios
                .patch('/api/pages/' + this.page.id,
                    {
                        title: this.page.title,
                        categories: this.page.categories,
                        subcategories: this.page.subcategories,
                        content: JSON.stringify(this.content),
                        template_id: this.page.template.id,
                        published: this.page.published,
                        indexed: this.page.indexed,
                        dynamic: this.page.dynamic,
                        id: this.page.id,
                        published_from: this.page.published_from,
                        published_to: this.page.published_to,
                        type: this.page.type,
                        slug: this.page.slug,
                    }
                )
                .then((response) => {
                    if (response.status == 200){
                        this.loading = false;
                        window.open(
                            process.env.MIX_APP_URL + '/__preview/' + this.page.uuid + '?p=HEW7M9hd8xY2gkRk',
                            '_blank'
                        );
                    }
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
            },
            settingsExist(column) {
                var result = false;
                column.template.inputs.forEach((input) => {
                    if (input.main !== true) {
                        result = true;
                    }
                });
                return result;
            },
            setPage() {

                if (this.template && this.pageType) {
                    if (this.page) {
                        this.page.template = this.template;
                        this.page.type = this.pageType;
                        this.$emit('setPage', this.page);
                    } else {
                        var page = {
                            content: this.template.rows,
                            title: "New page",
                            template: this.template,
                            type: this.pageType
                        };
                        this.$emit('setPage', page);
                    }
                }
            },
            setTemplate(template) {
                this.template = template;
                this.setPage();
            },
            setPageType(pageType) {
                this.pageType = pageType;
                this.setPage();
            },
            setPageTitle(pageTitle) {
                this.pageTitle = pageTitle;
                this.setPage();
            },
            simpleColumnCount(columns) {
                var count = 0;
                columns.forEach((column) => {
                    if (column.template.simple_view == true) {
                        count = count + 1;
                    }
                });
                return count;
            },
            showColumnTab(row_index, column_index) {
                $(".nav-row" + row_index + " a").removeClass("active");
                $(".nav-row" + row_index + " li.nav-column" + column_index + " a").addClass("active");

                $(".row" + row_index + ">div>.card").hide();
                $(".row" + row_index + " .column" + column_index).show();
            },
            addCustomComponent() {
                this.$modal.show('simple');
            },
            removeRow(index) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Delete component?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    showLoaderOnConfirm: true,
                    buttonsStyling: false,
                    customClass: {
                        cancelButton: "btn btn-soft-secondary",
                        confirmButton: "btn btn-soft-danger",
                    },
                    preConfirm: () => {
                        return this.$parent.content.splice(index, 1);
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire("Delete!", "Component has been deleted.", "success");
                    }
                });
            },
            setSlug() {
               this.$parent.page.slug = slugify(this.page.title);
            },
            isBasicEditorEmpty() {
                var result = true;
                this.content.forEach((row)=>{
                    if (row.tab == 'Content') {
                        row.columns.forEach((column)=>{
                            if (column.template.simple_view == true) {
                                result = false;
                            }
                        });
                    }
                });
                return result;
            }
        },
        computed: {
            extractedComponents() {
                return templateComponents;
            }
        },
        mounted() {
            if (this.content) {
                this.content.forEach(row => {
                    row.columns.forEach(column => {
                        this.$set(column, 'tab', 'main');
                    });
                });
            }

            eventHub.$on('component-added-simple', ((component) => {
                var comp = {
                    "id": this.$uuid.v4(),
                    "width": this.extractedComponents[component].width,
                    "order": this.$parent.content.length + 1,
                    "description": "",
                    "collapsed": false,
                    "color": 'white',
                    "tab": this.extractedComponents[component].tab,
                    "tabs_tab": this.tabs_tab,
                    "columns": [
                        {
                            "id": this.$uuid.v4(),
                            "order": 0,
                            "component": component,
                            "active": true,
                            "template": JSON.parse(JSON.stringify(this.extractedComponents[component]))
                        }
                    ]
                }

                this.$parent.content.push(comp);
            }));

            eventHub.$on('updateslug', ((value) => {
                this.page.slug = value;
            }));
        },
        destroyed() {
            eventHub.$off('component-added-simple');
            eventHub.$off('updateslug');
        }
    }
</script>
