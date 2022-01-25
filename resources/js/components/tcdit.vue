<template>
<div>
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="visibility: visible; width: 100%" aria-modal="true" role="dialog">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling</h5>
            <button @click="preview = false" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div> <!-- end offcanvas-header-->

        <div class="offcanvas-body" style="padding: 0px !important">
            <preview v-if="spreview" :gedata="data" :pseudo="pseudo" :preview="preview"></preview>
        </div> <!-- end offcanvas-body-->
    </div>

    <div v-if="data && !pseudo">
        <div  v-for="(val, index, key) in data" :key="key" class="col-12 mb-1 card">

            <div v-if="val.width" class="row">
            <div  class="btn-group" style="width: 20%;margin-left: 40%;margin-right: 40%;">
                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ val.width == 'full' ? 'Full' : (val.width == 'content' ? 'Content' :'Blog') }} Width<i class="mdi mdi-chevron-down"></i>
                            </button>
                <div class="dropdown-menu dropdown-menu-center" style="" data-popper-placement="bottom-start">
                    <a @click.prevent="val.width = 'full'" :class="'dropdown-item ' + (val.width == 'full' ? 'active' : '')" href="#">Full Width</a>
                    <a @click.prevent="val.width = 'content'" :class="'dropdown-item ' + (val.width == 'content' ? 'active' : '')" href="#">Content Width</a>
                    <a @click.prevent="val.width = 'blog'" :class="'dropdown-item ' + (val.width == 'blog' ? 'active' : '')" href="#">Blog Width</a>
                </div>
            </div>
            </div>
            <div v-for="(column, indr, key) in val.columns" :key="key">

                <div :key="activeChange" v-show="column.template && column.active" class="">
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
                            <div v-show="column.active" class="card-body row">
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

    <div v-if="data && pseudo">
        <draggable v-model="data" key="cols">
            <transition-group tag="div" >
                <div v-for="(val, index) in data" :key="'prim'+ index" class="row mb-1">
                    <!-- <draggable v-model="val.columns">
                        <transition-group tag="div" class="row" key="subcols"> -->
                        <div v-if="val.width" class="btn-group" style="width: 20%;margin-left: 40%;margin-right: 40%;">
                            <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ val.width == 'full' ? 'Full' : (val.width == 'content' ? 'Content' :'Blog') }} Width<i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-center" style="" data-popper-placement="bottom-start">
                                <a @click.prevent="val.width = 'full'" :class="'dropdown-item ' + (val.width == 'full' ? 'active' : '')" href="#">Full Width</a>
                                <a @click.prevent="val.width = 'content'" :class="'dropdown-item ' + (val.width == 'content' ? 'active' : '')" href="#">Content Width</a>
                                <a @click.prevent="val.width = 'blog'" :class="'dropdown-item ' + (val.width == 'blog' ? 'active' : '')" href="#">Blog Width</a>
                            </div>
                        </div>
                        <div v-for="(column, indr) in val.columns" :key="'column' + indr" :class="'col-lg-' + (12 / val.columns.length)">
                            <div class="" style="position: relative">
                                <div @click.prevent="" :key="'pseudo' + indr" class="dropzone  mb-2" style="min-height:150px">
                                    <div class="dz-message needsclick" style="margin: 0px !important; display: flex; justify-content: center; flex-direction: column">
                                        <i :class="'h1 text-muted ' + column.template.icon"></i>
                                        <div class="text-center">
                                        <span class="text-muted font-13">
                                            <strong>{{ column.template.title }} {{ val.order }}</strong></span>
                                            <small class="text-muted font-11">
                                            {{ column.order }}</small>

                                        </div>

                                        <span class="text-muted font-13">
                                            <i @click="split(val.columns, indr, 'push')" v-if="val.columns.length < 3" class="success dripicons-stack mr-2"></i>
                                        <i v-if="val.columns.length <= 3 && val.columns.length > 1 && indr > 0" @click="split(val.columns, indr, 'splice')" class="danger dripicons-trash mr-2"></i>
                                        <!-- <i v-if="val.columns.length <= 3 && val.columns.length > 1 && indr > 0" @click="split(val.columns, indr, 'splice')" class="dripicons-swap mr-2"></i> -->
                                        </span>
                                    </div>
                                </div>
                                <span @click="removeRow(index)" v-if="indr == (val.columns.length - 1)" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="cursor: pointer">
                                    Remove
                                <span class="visually-hidden"></span>
                            </span>
                            </div>

                        </div>

                        <!-- </transition-group>
                    </draggable> -->
                </div>
                </transition-group>
        </draggable>
    </div>


    <form @click.prevent="addCustomComponent" class="dropzone dz-clickable" style="min-height:80px; border: 2px dashed #6658dd !important" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
            <div class="dz-message needsclick" style="margin: 0px !important; color: #6658dd">
                <i class="h1  dripicons-view-apps" style="color: #6658dd"></i>
                <div class="text-center">
                <span class="text-muted font-13">
                    <strong>Click to Add Custom Component</strong></span>
            </div>
            </div>
        </form>
</div>
</template>

<script>

import templateComponents from './template-components.json'
import multiput from './inputs/multiput.vue'
import draggable from 'vuedraggable'
import Preview from './previewgrid.vue'

export default {
    props: {
        template: {},
        pseudo: {
            type: Boolean,
            default: false
        },
        predata: {},
        mode: {}
    },
    data() {
        return {
            lodash: _,
            data: null,
            activeChange: false,
            preview: false,
            spreview: true
        }
    },
    components: {
        multiput,
        draggable,
        Preview
    },
    computed: {
        extractedComponents() {
            return templateComponents;
        }
    },
    methods: {
        split(columns, indr, operation) {
            if (operation == 'push') {
                var col = JSON.parse(JSON.stringify(columns[indr]));
                col.order = columns.length + 1;
                col.id = this.$uuid.v4();
                columns.push(col);
            } else {
                columns.splice(1, indr);
            }
        },
        setTabActive(index, ind) {
            this.data[index].columns.forEach(column => { column.active = false });
            this.$set(this.data[index].columns[ind], 'active', true);
            this.activeChange = true;
            this.activeChange = false;
            this.$forceUpdate();
        },
        inputed($event, value) {
            this.$set(value, 'value', $event.data);
        },
        rearange(preview) {
            if (preview && preview === true) {
                console.log("PPPPPREE")
                this.spreview = false;
                this.preview = true;
                this.spreview = true;
            } else {
                this.$modal.show("rearange-modal")
            }
        },
        addCustomComponent() {
            this.$modal.show("component-modal", {"data": 'test'})
        },
        reupdateTemplate() {

        },
        removeRow(index) {
            this.data.splice(index, 1);
        }
    },

    watch: {
        predata() {

        }
    },
    mounted() {
        // console.log("TCEDIT MOUNTED")
        /* if (this.predata) {
            this.data = this.predata;
            return;
        } */
        if (this.pseudo) {
            if (this.predata) {
                this.data = this.predata;

            } else {
            this.data = this.data ?? [];
            var comp = {
                "id": this.$uuid.v4(),
                "width": this.extractedComponents['meta'].width,
                "order": this.data.length + 1,
                "description": "",
                "columns": [
                    {
                        "id": this.$uuid.v4(),
                        "order": 0,
                        "component": 'meta',
                        "active": true,
                        "template": this.extractedComponents['meta']
                    }
                ]
            }

            this.data.push(comp);
            }
        }
// console.log("mmm", this.pseudo, this.mode)
        if (this.pseudo == false) {
            console.log("PSEUDO", this.pseudo, this.predata)
            var parsed = this.predata;
            if (this.mode != "edit") {
                parsed.forEach(element => {
                element.columns.forEach(column => {
                    column.active = column.order < 1 ? true : false;
                    console.log("ORDER", column.active)
                    if (this.extractedComponents[column.component] != null) {
                        column.template = this.extractedComponents[column.component];
                        //column.template.inputs.forEach((input) => { input.key = input.key + column.order; })
                    }

                });
            });}

            this.data = parsed;

        }

        eventHub.$on('component-rearange', ((preview) => {
            if (preview && preview === true) {
                this.spreview = false;
                this.preview = true;
                this.spreview = true;
            } else {
                this.rearange();
            }
        }));

        eventHub.$on('component-added', ((component) => {
            console.log('component--', component)
            this.data = this.data ?? [];
            var comp = {
                "id": this.$uuid.v4(),
                "width": this.extractedComponents[component].width,
                "order": this.data.length + 1,
                "description": "",
                "columns": [
                    {
                        "id": this.$uuid.v4(),
                        "order": 0,
                        "component": component,
                        "active": true,
                        "template": this.extractedComponents[component]
                    }
                ]
            }

            this.data.push(comp);
        }));

        eventHub.$on('order-changed', ((data) => {
            // console.log('order-changed')
            this.data = data;
        }));
    },
    beforeDestroy() {
        // console.log("TCEDIT DESTROYED")
        eventHub.$off('component-added');
        eventHub.$off('component-rearange');
        eventHub.$off('order-changed');
    }
}
</script>

<style>
.bg-grey {
    background-color: rgb(234 237 239 / 48%) !important;
}
.dropdown-menu-center {
  left: 50% !important;
  right: auto !important;
  text-align: center !important;
  transform: translate(-50%, 0) !important;
}
</style>
