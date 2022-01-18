<template>
<div>
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="visibility: visible; width: 100%" aria-modal="true" role="dialog">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div> <!-- end offcanvas-header-->

        <div class="offcanvas-body">
            <div v-if="!template" class="col-lg-12">
            <div class="card">
                <div class="card-body text-center">
                    No Components Selected
                </div>
            </div>
        </div>
        <div v-else class="p-4 ">
            <div class="">
            <draggable v-model="data">
                <transition-group>
                    <div class="row" v-for="(value, ind) in data" :key="ind + 'drag'">
                            <div :class="'col-lg-' + (12 / value.columns.length)" v-for="(column, indr) in value.columns" :key="indr + 'dragi'">
                                <div >
                                <div class="card border rounded bg-grey col-12" >
                                <div :class="'card-body p-3'">
                                    <div class="d-flex align-itames-start">
                                        <div class="w-100">
                                            <h5 class="mb-1 mt-0">{{column.template.title}}</h5>
                                            <p> Web Developer </p>
                                            <p class="mb-0 text-muted">
                                                <span class="fst-italic"><b>"</b>{{column}} </span>
                                            </p>
                                        </div>
                                    </div> <!-- end d-flex -->
                                </div> <!-- end card-body -->
                                </div>

                            </div>
                    </div>
                </div>
                </transition-group>
            </draggable>
            </div>
        </div>
        </div> <!-- end offcanvas-body-->
    </div>

    <div v-if="data">
        <div v-for="(val, index, key) in data" :key="key" class="col-12 mb-1">
            <div v-for="(column, indr, key) in val.columns" :key="key">
                <div :key="activeChange" v-show="column.template && column.active" class="card bg-grey">
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
                                    @inputed="inputed($event, input)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form @click.prevent="addCustomComponent" class="dropzone dz-clickable" style="min-height:100px" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
            <div class="dz-message needsclick" style="margin: 0px !important">
                <i class="h1 text-muted dripicons-view-apps"></i>
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

export default {
    props: ['template'],
    data() {
        return {
            lodash: _,
            data: null,
            activeChange: false
        }
    },
    components: {
        multiput,
        draggable
    },
    computed: {
        extractedComponents() {
            return templateComponents;
        }
    },
    methods: {
        setTabActive(index, ind) {
            this.data[index].columns.forEach(column => { column.active = false });
            this.$set(this.data[index].columns[ind], 'active', true);
            this.activeChange = true;
            this.activeChange = false;
            this.$forceUpdate();
        },
        inputed($event, value) {
            value.value = $event.data;
        },
        rearange() {
            this.$modal.show("rearange-modal")
        },
        addCustomComponent() {
            this.$modal.show("component-modal", {"data": 'test'})
        },
    },

    watch: {
        template() {
        if (this.template == null) {
            return;
        }
        var parsed = typeof this.template.rows === 'string' || this.template.rows instanceof String ? JSON.parse(this.template.rows) : this.template.rows;

        console.log("PARSED", parsed)
        parsed.forEach(element => {
            element.columns.forEach(column => {
                column.active = column.order < 1 ? true : false;

                if (this.extractedComponents[column.component] != null) {
                    column.template = this.extractedComponents[column.component];
                    column.template.inputs.forEach((input) => { input.key = input.key + column.order; console.log(input)})
                }

            });
        });

        this.data = parsed;
        console.log("data", this.data);
    }
    },
    mounted() {
        eventHub.$on('component-added', ((component) => {
            console.log('component--', component)
        }));
    },
    beforeDestroy() {
        eventHub.$off('component-added');
    }
}
</script>

<style>
.bg-grey {
    background-color: rgb(234 237 239 / 48%) !important;
}
</style>
