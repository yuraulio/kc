<template>
<div>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="visibility: visible; width: 100%" aria-modal="true" role="dialog">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Rearange components</h5>
            <button @click="preview = false" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div> <!-- end offcanvas-header-->

        <div class="offcanvas-body" style="padding: 0px !important">
            <preview v-if="spreview" :gedata="data" :pseudo="pseudo" :preview="preview"></preview>
        </div> <!-- end offcanvas-body-->
    </div>

    <div v-if="!pseudo">

        <div v-if="pageTitle" class="">
            <div class="col-sm-12 mt-2 mb-2">
                <div class="page-title-box">
                    <h4 class="page-title">{{pageTitle}}</h4>
                </div>
            </div>
        </div>

        <div v-if="name == 'main'" class="row mb-1">
            <div class="col-12">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a @click="tab = 'Content'" :class="'nav-link ' + (tab == 'Content' ? 'active' : '')" href="#!">Content</a>
                    </li>
                    <li class="nav-item">
                        <a @click="tab = 'Meta'" :class="'nav-link ' + (tab == 'Meta' ? 'active' : '')" href="#!">SEO</a>
                    </li>
                    <li class="nav-item">
                        <a @click="tab = 'Other'" :class="'nav-link ' + (tab == 'Other' ? 'active' : '')" href="#!">Other</a>
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="name == 'tabs'" class="row ">
            <div class="col-12">
                <nav class="nav nav-pills flex-column flex-sm-row navtab-bg">
                    <template v-for="(tab_item, tab_index) in tabs">
                        <a @click="tabs_tab = tab_item" :class="'mb-1 me-1 ms-0 flex-sm-fill text-sm-center nav-link ' + (tabs_tab == tab_item ? 'active' : '')" href="#!">
                            <template v-if="!tab_open_edit.includes(tab_item)">
                                {{tab_item}}
                                <div @click.stop="removeTabsTab(tab_item)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-trash cursor-pointer" title="Delete tab"></i>
                                </div>
                                <div @click="tab_open_edit.push(tab_item)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-document-edit"></i>
                                </div>
                            </template>
                            <template v-else>
                                <input style="height: 21px;" type="text" :value="tab_item" :ref="tab_item">
                                <div @click="tab_open_edit.splice(tab_open_edit.indexOf(tab_item), 1)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-trash mt-1 cursor-pointer" title="Delete tab"></i>
                                </div>
                                <div @click="renameTab(tab_item)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-checkmark"></i>
                                </div>
                            </template>
                        </a>
                    </template>

                    <button @click="add_tab = true" class="mb-1 btn btn-soft-success add-column-button">
                        Add tab
                    </button>
                </nav>
            </div>

            <div class="col-12 mt-1 mb-1" v-if="add_tab == true">
                <div class="row">
                    <div class="col-8">
                        <input v-model="new_tab" type="text" class="form-control">
                    </div>
                    <div class="col-2 text-center">
                        <button @click="addTab()" class="btn btn-soft-success w-100">
                            <i class="dripicons-checkmark"></i>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button @click="add_tab = false, new_tab = ''" class="btn btn-soft-secondary w-100">
                            <i class="dripicons-cross"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <draggable 
            v-if="data"
            v-model="data" 
            key="cols"
            handle=".handle"
        >
            <transition-group tag="div" >
                <div v-for="(val, index, key) in data" :key="'prim'+ index" :class="'col-12 mb-1 card toggle-card ' + (name == 'tabs' && tabs_tab == val.tabs_tab ? ' tab-components d-block ' : ' d-none ') + (name == 'main' && tab == val.tab ? ' d-block ' : ' d-none ')">

                    <div class="row handle">
                        <div class="col-2">
                            <button @click="toggleCollapse(val)" class="btn btn-sm btn-soft-secondary" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseelement' + val.id" aria-expanded="false" aria-controls="collapseExample">
                                <i v-if="val.collapsed == true" class="mdi mdi-chevron-down"></i>
                                <i v-else class="mdi mdi-chevron-up"></i>
                            </button>
                        </div>
                        <div class="col-4 text-center">
                            <div v-if="val.width" class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    {{ val.width == 'full' ? 'Full' : (val.width == 'content' ? 'Content' :'Blog') }} Width<i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-center" style="" data-popper-placement="bottom-start">
                                    <a @click.prevent="val.width = 'full'" :class="'dropdown-item ' + (val.width == 'full' ? 'active' : '')" href="#!">Full Width</a>
                                    <a @click.prevent="val.width = 'content'" :class="'dropdown-item ' + (val.width == 'content' ? 'active' : '')" href="#!">Content Width</a>
                                    <a @click.prevent="val.width = 'blog'" :class="'dropdown-item ' + (val.width == 'blog' ? 'active' : '')" href="#!">Blog Width</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div v-if="val.disable_color !== true" class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <template v-if="val.color == 'white'">
                                        White <i class="mdi mdi-chevron-down"></i>
                                    </template>
                                    <template v-if="val.color == 'blue_gradient'">
                                        Blue gradient <i class="mdi mdi-chevron-down"></i>
                                    </template>
                                    <template v-if="val.color == 'blue'">
                                        Blue <i class="mdi mdi-chevron-down"></i>
                                    </template>
                                    <template v-if="val.color == 'gray'">
                                        Gray <i class="mdi mdi-chevron-down"></i>
                                    </template>
                                    
                                </button>
                                <div class="dropdown-menu dropdown-menu-center" style="">
                                    <a @click.prevent="val.color = 'white'" 
                                        :class="'dropdown-item ' + (val.color == 'white' ? 'active' : '')" 
                                        href="#!">White
                                    </a>

                                    <a @click.prevent="val.color = 'blue_gradient'" 
                                        :class="'dropdown-item ' + (val.color == 'blue_gradient' ? 'active' : '')" 
                                        href="#!">Blue gradient
                                    </a>

                                    <a @click.prevent="val.color = 'blue'" 
                                        :class="'dropdown-item ' + (val.color == 'blue' ? 'active' : '')" 
                                        href="#!">Blue
                                    </a>

                                    <a @click.prevent="val.color = 'gray'" 
                                        :class="'dropdown-item ' + (val.color == 'gray' ? 'active' : '')" 
                                        href="#!">Gray
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>
                    <div v-for="(column, indr, key) in val.columns" :key="key">

                        <span @click="removeRow(index)" v-if="indr == (val.columns.length - 1) && val.removable !== false" class="position-absolute top-0 start-100 close-button" title="Delete component" style="cursor: pointer">
                            <button class="btn btn-sm btn-soft-danger" type="button">
                                <i class="dripicons-trash"></i>
                            </button>
                        </span>

                        <div :key="activeChange" v-show="column.template && column.active" class="">
                            <div class="card-body pb-3">
                                <div v-if="val.columns.length > 1">
                                    <ul class="nav nav-pills navtab-bg nav-justified">
                                        <li v-for="(v, ind) in val.columns" :key="ind" :class="'nav-item ' + getColumnWidth(column, val.columns)">
                                            <a href="#home1" @click="setTabActive(index, ind)" data-bs-toggle="tab" aria-expanded="false" :class="'nav-link' + (v.active === true ? ' active' : '')">
                                                {{ v.template.title }}
                                                <div @click.stop="removeColumn(val.columns, column.id, index, ind)" class="d-inline-block float-end ms-1">
                                                    <i class="dripicons-trash" title="Delete column"></i>
                                                </div>
                                                <div @click="changeComponent(index, ind, column.template)" class="d-inline-block float-end">
                                                    <i class="dripicons-return"></i>
                                                </div>
                                            </a>
                                            <div v-if="val.columns.length > 1" class="mt-1">
                                                Width: {{v.width}} / 6
                                                <input @click="calculateWidth(val.columns, ind, $event, index)" :value="v.width" class="w-100" type="range" maxlength="1" min="1" max="6">
                                            </div>

                                            <div v-if="v.template.dynamic != null" class="d-block text-center ms-2">
                                                <span class="text-muted font-13 d-inline-block me-1" style="margin-top: 4px;">Dynamic</span><input v-model="v.template.dynamic" type="checkbox" class="form-check-input">
                                            </div>

                                            <div class="d-block text-center">
                                                <span class="text-muted font-13 d-inline-block me-1" style="margin-top: 4px;">Show on mobile</span><input v-model="v.template.mobile" type="checkbox" class="form-check-input">
                                            </div>

                                            <div v-if="checkComponentVersion(v)" class="alert alert-warning m-2" role="alert">
                                                This component is updated. Refresh to get new version. Refresh will delete data.
                                                <i @click="refreshComponent(v)" style="font-size: 20px" class="text-muted dripicons-clockwise m-1 d-inline-block"></i>
                                            </div>
                                        </li>
                                        <li v-if="val.columns.length < 3">
                                            <button @click="split(val.columns, indr, 'push')" class="btn btn-success add-column-button ms-1">
                                                <i class="dripicons-plus"></i>
                                            </button>
                                        </li>
                                        <li v-if="val.tooBig">
                                            <span class="badge bg-warning">Row too wide</span>
                                        </li>
                                    </ul>
                                </div>
                                <template v-else>
                                    <h5 :class="'card-title mb-0 ' + getColumnWidth(column, val.columns)">
                                        <div @click="changeComponent(index, 0, column.template)" class="d-inline-block cursor-pointer">
                                            {{ column.template.title }}
                                        </div>
                                        <span v-if="column.template.one_column != true" class="text-muted font-13 float-end" style="margin-top: 4px;">
                                            <div @click="split(val.columns, indr, 'push')" class="ms-3 mr-2 d-inline-block cursor-pointer">
                                                Add column
                                            </div>
                                        </span>
                                        <div v-if="column.template.dynamic != null" class="d-inline-block float-end ms-2">
                                            <span class="text-muted font-13 d-inline-block me-1" style="margin-top: 4px;">Dynamic</span><input v-model="column.template.dynamic" type="checkbox" class="form-check-input">
                                        </div>

                                        <div v-if="column.template.mobile != null" class="d-inline-block float-end">
                                            <span class="text-muted font-13 d-inline-block me-1" style="margin-top: 4px;">Show on mobile</span><input v-model="column.template.mobile" type="checkbox" class="form-check-input">
                                        </div>
                                    </h5>
                                    <div v-if="checkComponentVersion(column)" class="alert alert-warning m-2" role="alert">
                                        This component is updated. Refresh to get new version. Refresh will delete data.
                                        <i @click="refreshComponent(column)" style="font-size: 20px" class="text-muted dripicons-clockwise m-1 d-inline-block"></i>
                                    </div>
                                </template>

                            </div>
                            <div :class="'tab-content collapse pb-3 ' + (val.initialCollapsed ? '' : 'show')" :id="'collapseelement' + val.id" style="padding-top: 0px" >
                                
                                    <div v-for="(vl, indx) in val.columns" :key="'tabpane' + indx" :class="'tab-pane ' + (vl.active === true ? ' active' : '')">
                                        <div v-show="column.active" class="card-body row pb-0 pt-0">

                                            <multiput
                                                v-for="(input, ri) in column.template.inputs"
                                                v-show="!column.template.dynamic || input.dynamic"
                                                :key="input.key"
                                                :keyput="input.key + index + indx + ri + indr"
                                                :label="input.label"
                                                :type="input.type"
                                                :value="input.key == 'meta_slug' ? slug : input.value"
                                                :tabsProp="input.tabs ? input.tabs : []"
                                                :size="input.size"
                                                :width="input.width"
                                                @inputed="inputed($event, input)"
                                                @inputedTabs="inputedTabs($event, input)"
                                                :route="input.route"
                                                :multi="false"
                                                :existingValue="input.value"
                                                :uuid="$uuid.v4()"
                                                :mode="mode"
                                                :inputs="input.inputs"
                                            />
                                        </div>

                                        <!-- preview -->
                                        <template v-if="column.component == 'youtube'">
                                            <div v-show="!column.template.dynamic" class="card-body row pb-0 pt-0">
                                                <div class="col-12">
                                                    <label class="form-label mt-2">Preview</label>
                                                    <div class="text-center">
                                                        <iframe 
                                                            :width="findInputValue(column.template.inputs, 'youtube_full_width') ? '100%' : (findInputValue(column.template.inputs, 'youtube_width') || '100%')"
                                                            :height="findInputValue(column.template.inputs, 'youtube_height') || '600'" 
                                                            :src="'https://www.youtube.com/embed/' + findInputValue(column.template.inputs, 'youtube_embed')" 
                                                            title="YouTube video player" 
                                                            frameborder="0" 
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                            allowfullscreen
                                                        ></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="column.component == 'html'">
                                            <div class="card-body row pb-0 pt-0">
                                                <div class="col-12">
                                                    <label class="form-label mt-2">Preview</label>
                                                    <br>
                                                    <p v-html="findInputValue(column.template.inputs, 'html_embed')"></p>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </transition-group>
        </draggable>
    </div>

    <div v-if="data && pseudo">

        <div v-if="name == 'main'" class="row">
            <div class="col-sm-12 mt-3 mb-3">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <input :value="pageTitle" @change="updateTemplateTitle" class="d-inline-block title-input">
                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" @click="rearange()" class="btn btn-block btn-soft-info waves-effect waves-light m-1t ">Rearange</button>
                </div>
            </div>
        </div>

        <div v-if="name == 'main'" class="row mb-1">
            <div class="col-12">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a @click="tab = 'Content'" :class="'nav-link ' + (tab == 'Content' ? 'active' : '')" href="#!">Content</a>
                    </li>
                    <li class="nav-item">
                        <a @click="tab = 'Meta'" :class="'nav-link ' + (tab == 'Meta' ? 'active' : '')" href="#!">Meta</a>
                    </li>
                    <li class="nav-item">
                        <a @click="tab = 'Other'" :class="'nav-link ' + (tab == 'Other' ? 'active' : '')" href="#!">Other</a>
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="name == 'tabs'" class="row ">
            <div class="col-12">
                <nav class="nav nav-pills flex-column flex-sm-row navtab-bg">
                    <template v-for="(tab_item, tab_index) in tabs">
                        <a @click="tabs_tab = tab_item" :class="'mb-1 me-1 ms-0 flex-sm-fill text-sm-center nav-link ' + (tabs_tab == tab_item ? 'active' : '')" href="#!">
                            <template v-if="!tab_open_edit.includes(tab_item)">
                                {{tab_item}}
                                <div @click.stop="removeTabsTab(tab_item)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-trash" title="Delete tab"></i>
                                </div>
                                <div @click="tab_open_edit.push(tab_item)" class="d-inline-block float-end">
                                    <i class="dripicons-document-edit"></i>
                                </div>
                            </template>
                            <template v-else>
                                <input style="height: 21px;" type="text" :value="tab_item" :ref="tab_item">
                                <div @click="tab_open_edit.splice(tab_open_edit.indexOf(tab_item), 1)" class="d-inline-block float-end ms-1">
                                    <i class="dripicons-trash mt-1" title="Delete tab"></i>
                                </div>
                                <div @click="renameTab(tab_item)" class="d-inline-block float-end">
                                    <i class="dripicons-checkmark"></i>
                                </div>
                            </template>
                        </a>
                    </template>

                    <button @click="add_tab = true" class="mb-1 btn btn-soft-success add-column-button">
                        Add tab
                    </button>
                </nav>
            </div>

            <div class="col-12 mt-1 mb-1" v-if="add_tab == true">
                <div class="row">
                    <div class="col-8">
                        <input v-model="new_tab" type="text" class="form-control">
                    </div>
                    <div class="col-2 text-center">
                        <button @click="addTab()" class="btn btn-soft-success w-100">
                            <i class="dripicons-checkmark"></i>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button @click="add_tab = false, new_tab = ''" class="btn btn-soft-secondary w-100">
                            <i class="dripicons-cross"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <draggable 
            v-model="data" 
            key="cols"
            handle=".handle"
        >
            <transition-group tag="div" >
                <div v-for="(val, index) in data" :key="'prim'+ index" :class="'row mb-1 ' + (name == 'tabs' && tabs_tab == val.tabs_tab ? ' d-block ' : ' d-none ') + (name == 'main' && tab == val.tab ? ' d-block ' : ' d-none ')">
                    <div v-if="val.width" class="btn-group" style="width: 20%;margin-left: 40%;margin-right: 40%;">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{ val.width == 'full' ? 'Full' : (val.width == 'content' ? 'Content' :'Blog') }} Width<i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-center" style="" data-popper-placement="bottom-start">
                            <a @click.prevent="val.width = 'full'" :class="'dropdown-item ' + (val.width == 'full' ? 'active' : '')" href="#!">Full Width</a>
                            <a @click.prevent="val.width = 'content'" :class="'dropdown-item ' + (val.width == 'content' ? 'active' : '')" href="#!">Content Width</a>
                            <a @click.prevent="val.width = 'blog'" :class="'dropdown-item ' + (val.width == 'blog' ? 'active' : '')" href="#!">Blog Width</a>
                        </div>
                    </div>
                    <div v-for="(column, indr) in val.columns" :key="'column' + indr" :class="'d-inline-block col-lg-' + getColumnWidth(column, val.columns)">
                        <div class="" style="position: relative">
                            <div @click.prevent="" :key="'pseudo' + indr" class="dropzone  mb-2" style="min-height:150px">
                                <div class="dz-message needsclick" style="margin: 0px !important; display: flex; justify-content: center; flex-direction: column">
                                    <div @click="changeComponent(index, indr, column.template)">
                                        <i :class="'h1 handle text-muted ' + column.template.icon"></i>
                                        <div class="text-center">
                                            <span class="text-muted font-13">
                                                <strong>{{ column.template.title }} {{ val.order }}</strong>
                                            </span>
                                            <small class="text-muted font-11">{{ column.order }}</small>
                                        </div>
                                    </div>
                                    <span v-if="column.template.one_column != true" class="text-muted font-13">
                                        <i @click="split(val.columns, indr, 'push')" v-if="val.columns.length < 3" class="success dripicons-stack mr-2"></i>
                                        <i v-if="val.columns.length <= 3 && val.columns.length > 1 && indr > 0" @click="split(val.columns, indr, 'splice')" class="danger dripicons-trash mr-2"></i>
                                        <br>
                                        <template v-if="val.columns.length > 1">
                                            Width: {{column.width}} / 6
                                            <input @click.stop="calculateWidth(val.columns, indr, $event, index)" :value="column.width" class="w-100" type="range" maxlength="1" min="1" max="6">
                                        </template>
                                    </span>
                                </div>

                                <multiput
                                    v-for="input in column.template.inputs"
                                    v-if="input.key == 'tabs'"
                                    :pseudo="true"
                                    :key="input.key"
                                    :keyput="input.key"
                                    :label="input.label"
                                    :type="input.type"
                                    :value="input.value"
                                    :tabsProp="input.tabs ? input.tabs : []"
                                    :size="input.size"
                                    :width="input.width"
                                    @inputed="inputed($event, input)"
                                    @inputedTabs="inputedTabs($event, input)"
                                    :route="input.route"
                                    :multi="false"
                                    :existingValue="input.value"
                                    :uuid="$uuid.v4()"
                                />

                            </div>
                            <span @click="removeRow(index)" v-if="indr == (val.columns.length - 1) && val.removable !== false" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" title="Delete component" style="cursor: pointer">
                                Remove
                                <span class="visually-hidden"></span>
                            </span>

                        </div>
                    </div>
                </div>
            </transition-group>
        </draggable>
    </div>


    <form v-if="tabs_tab || name == 'main'" @click.prevent="addCustomComponent" class="dropzone dz-clickable" style="min-height:80px; border: 2px dashed #6658dd !important" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
        <div class="dz-message needsclick" style="margin: 0px !important; color: #6658dd">
            <i class="h1  dripicons-view-apps" style="color: #6658dd"></i>
            <div class="text-center">
                <span class="text-muted font-13">
                    <strong>Click to Add Custom Component</strong>
                </span>
            </div>
        </div>
    </form>

    <component-modal
        :row="row_index"
        :column="column_index"
        :name="name"
    ></component-modal>

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
        mode: {},
        pageTitle: null,
        collapseAllProp: Boolean,
        name: {
            type: String,
            default: "main",
        },
        tabsProp: [],
        slug: String,
    },
    data() {
        return {
            lodash: _,
            data: [],
            activeChange: false,
            preview: false,
            spreview: true,
            row_index: null,
            column_index: null,
            collapseAll: true,
            tab: "Content",
            tabs: [],
            tabs_tab: null,
            add_tab: false,
            new_tab: "",
            tab_open_edit: [],
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
                col.active = false;

                // set columns width
                var colWidth = 6 / (columns.length + 1);
                col.width = colWidth;
                columns.forEach(function(column) {
                    column.width = colWidth;
                });

                columns.push(col);
            } else {
                columns.splice(1, indr);

                // set columns width
                var colWidth = 6 / (columns.length);
                columns.forEach(function(column) {
                    column.width = colWidth;
                });
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
        inputedTabs($event, value) {
            this.$set(value, 'tabs', $event.data);
        },
        rearange(preview) {
            if (preview && preview === true) {
                this.spreview = false;
                this.preview = true;
                this.spreview = true;
            } else {
                this.$modal.show("rearange-modal")
            }
        },
        addCustomComponent() {
            this.row_index = null;
            this.column_index = null;
            this.$modal.show(this.name);
        },
        reupdateTemplate() {

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
                    return this.data.splice(index, 1);
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Deleted!", "Component has been deleted.", "success");
                }
            });
        },
        changeComponent(row_index, column_index, component) {
            if (component.changable !== false) {
                this.row_index = row_index;
                this.column_index = column_index;
                this.$modal.show(this.name);
            }
        },
        toggleCollapse(val) {
            if (val.collapsed) {
                val.collapsed = !val.collapsed;
            } else {
                this.$set(val, 'collapsed', true);
            }
        },
        toggleCollapseAll() {
            if (this.collapseAll) {
                $('.toggle-card .collapse').collapse('hide');
            } else {
                $('.toggle-card .collapse').collapse('show');
            }

            var collapseAll = this.collapseAll;
            this.data.forEach(row => {
                if (typeof row.collapsed !== 'undefined') {
                    row.collapsed = collapseAll;
                } else {
                    this.$set(row, 'collapsed', collapseAll);
                }
            });

            this.collapseAll = !this.collapseAll;

        },
        removeColumn(columns, column_id, row_index, column_index) {
            Swal.fire({
                title: "Are you sure?",
                text: "Delete column?",
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
                    var index = columns.findIndex(function(column) {
                        return column.id == column_id;
                    });
                    columns.splice(index, 1);

                    // set columns width
                    var colWidth = 6 / (columns.length);
                    columns.forEach(function(column) {
                        column.width = colWidth;
                    });

                    this.setTabActive(row_index, 0);
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Delete!", "Column has been deleted.", "success");
                }
            });
        },
        findInputValue(inputs, key){
            var index = inputs.findIndex(function(input) {
                return input.key == key;
            });
            return inputs[index].value;
        },
        getColumnWidth(column, columns) {
            if (column.width) {
                return column.width * 2;
            } 
            var width = (12 / columns.length) / 2;
            this.$set(column, "width", Number(width));
            return width * 2;
        },
        calculateWidth(columns, index, event, rowIndex) {
            columns[index].width = Number(event.target.value);

            var width = 0;
            columns.forEach(function(column) {
                width = width + Number(column.width);
            });

            var nextIndex = index + 1;
            if (!columns[nextIndex]) {
                nextIndex = 0;
            }

            columns[nextIndex].width = columns[nextIndex].width - (width - 6);

            var finalWidth = 0;
            columns.forEach(function(column) {
                if (columns.length == 3){
                    if (column.width > 4) {
                        column.width = 4;
                    }
                }
                if (columns.length == 2){
                    if (column.width > 5) {
                        column.width = 5;
                    }
                }
                if (columns.length == 1){
                    if (column.width > 6) {
                        column.width = 6;
                    }
                }
                if (column.width < 1) {
                    column.width = 1;
                }
                finalWidth = finalWidth + column.width;
            });

            if (finalWidth > 6) {
                this.$set(this.data[rowIndex], "tooBig", true);
            } else {
                this.$set(this.data[rowIndex], "tooBig", false);
            }
        },
        addTab() {
            if (!this.tabs.includes(this.new_tab)) {
                this.tabs.push(this.new_tab);
                if (this.tabs_tab == null) {
                    this.tabs_tab = this.new_tab;
                }
                this.new_tab = "";
                this.add_tab = false;
            }
        },
        removeTabsTab(tab_name) {
            Swal.fire({
                title: "Are you sure?",
                text: "Delete tab?",
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
                    var data = this.data;
                    this.data.forEach(function(row, index) {
                        if (row.tabs_tab == tab_name) {
                            data.splice(index, 1);
                        }
                    });

                    var tabs = this.tabs;
                    this.tabs.forEach(function(tab, index) {
                        if (tab == tab_name) {
                            tabs.splice(index, 1);
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Deleted!", "Tab has been deleted.", "success");
                }
            });
        },
        renameTab(tab_name){
            var input = this.$refs[tab_name];
            var new_tab_name = input[0].value;

            if (new_tab_name) {
                if (!this.tabs.includes(new_tab_name)) {
                    var data = this.data;
                    this.data.forEach(function(row, index) {
                        if (row.tabs_tab == tab_name) {
                            data[index].tabs_tab = new_tab_name;
                        }
                    });

                    var tabs = this.tabs;
                    this.tabs.forEach(function(tab, index) {
                        if (tab == tab_name) {
                            tabs[index] = new_tab_name;
                        }
                    });

                    this.tab_open_edit.splice(this.tab_open_edit.indexOf(new_tab_name), 1);
                }
            }
        },
        checkComponentVersion(column) {
            var version = column.template.version ? column.template.version : 1;
            if (this.extractedComponents[column.component] && this.extractedComponents[column.component].version > version){
                return true;
            }
            return false;
        },
        refreshComponent(column) {
            column.template = JSON.parse(JSON.stringify(this.extractedComponents[column.component]));
        },
        updateTemplateTitle(){
            this.$parent.title_value = $('.title-input').val();
        }
    },

    watch: {
        "data": function(val) {
            this.$emit('updatetabscomponent', val);
        },
        "predata": function(val) {
            this.data = this.predata;
        },
        "tabs": function(val) {
            this.$emit('updatetabs', val);
        },
        "collapseAllProp": function() {
            this.toggleCollapseAll();
        }
    },
    mounted() {
        if (this.pseudo) {
            if (this.predata) {
                this.data = this.predata;
            } else {
                // this.data = this.data ?? [];

                if (typeof this.tabsProp !== 'undefined') {
                    this.tabs = this.tabsProp;
                }

                if (this.name == 'main') {
                    // add meta component
                    var meta = {
                        "id": this.$uuid.v4(),
                        "width": this.extractedComponents['meta'].width,
                        "order": this.data.length + 1,
                        "description": "",
                        "removable": false,
                        "disable_color": true,
                        "tab": "Meta",
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
                    this.data.push(meta);

                    // add menu component
                    var menu = {
                        "id": this.$uuid.v4(),
                        "width": this.extractedComponents['menus'].width,
                        "order": this.data.length + 1,
                        "description": "",
                        "collapsed": true,
                        "removable": false,
                        "disable_color": true,
                        "tab": "Content",
                        "columns": [
                            {
                                "id": this.$uuid.v4(),
                                "order": 0,
                                "component": 'menus',
                                "active": true,
                                "template": this.extractedComponents['menus']
                            }
                        ]
                    }
                    this.data.push(menu);
                }
            }
        }
        if (this.pseudo == false) {
            // this.data = this.predata;
            if (typeof this.predata !== 'undefined') {
                var parsed = this.predata;
                if (parsed.length) {
                    parsed.forEach(element => {
                        element.initialCollapsed = element.collapsed ? element.collapsed : false;
                    });
                }
           
                if (this.mode != "edit" && parsed.length) {
                    parsed.forEach(element => {
                        element.columns.forEach(column => {
                            if (column.component != 'tabs') {
                                column.active = column.order < 1 ? true : false;
                                // if (this.extractedComponents[column.component] != null) {
                                //     column.template = this.extractedComponents[column.component];
                                // }
                            }
                        });
                    });
                }
                this.data = parsed;
            }
        }

        if (typeof this.tabsProp !== 'undefined') {
            this.tabs = this.tabsProp;
        }


        if (this.data) {
            this.data.forEach(row => {
                if (!row.color){
                    this.$set(row, 'color', 'white');
                }
            });
        }

        eventHub.$on('component-rearange-' + this.name, ((preview) => {
            if (preview && preview === true) {
                this.spreview = false;
                this.preview = true;
                this.spreview = true;
            } else {
                this.rearange();
            }
        }));

        eventHub.$on('component-added-' + this.name, ((component) => {
            this.data = this.data ?? [];
            var comp = {
                "id": this.$uuid.v4(),
                "width": this.extractedComponents[component].width,
                "order": this.data.length + 1,
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

            this.data.push(comp);
        }));

        eventHub.$on('component-change-' + this.name, ((data) => {
            var component = data[0];
            var row_index = data[1];
            var column_index = data[2];
            this.data[row_index].columns[column_index].component = component;
            this.data[row_index].columns[column_index].template = JSON.parse(JSON.stringify(this.extractedComponents[component]));

            this.$modal.hide("component-modal");
        }));

        eventHub.$on('order-changed-' + this.name, ((data) => {
            this.data = data;
        }));
    },
    beforeDestroy() {
        eventHub.$off('component-added-' + this.name);
        eventHub.$off('component-rearange-' + this.name);
        eventHub.$off('order-changed-' + this.name);
        eventHub.$off('component-change-' + this.name);    
    }
}
</script>

<style>
.bg-grey {
    background-color: rgb(234 237 239 / 48%) !important;
}
.dropdown-menu-center {
  /* left: 50% !important; */
  right: auto !important;
  text-align: center !important;
  /* transform: translate(-50%, 0) !important; */
}
.close-button {
    transform: translateX(-40px);
}
.close-button i {
    transform: translateY(2px);
}
.cursor-pointer {
    cursor: pointer;
}
.add-column-button {
    height: 37px
}
.add-column-button>i {
    font-size: 25px;
    transform: translateY(-3px);
}

.d-block {
    display: block!important;
}
.d-inline-block {
    display: inline-block!important;
}

.tab-components {
    border: 5px solid #f5f6f8;
}

.title-input {
    background: transparent;
    outline: none;
    border: 0px;
    box-shadow: none;
    font-size: 23px;
    width: 100%;
}
</style>
