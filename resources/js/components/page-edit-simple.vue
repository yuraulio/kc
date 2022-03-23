<style scoped>
    .nav-link {
        color: #6c757d;
    }
    .nav-link.active {
        border-bottom: 4px solid #6658dd;
    }
</style>

<style>
    .page-edit-simple .image-input img.img-fluid.rounded {
        max-height: 300px;
    }
    .page-edit-simple .image-input .change-media {
        display: block;
        width: 100%;
        margin-top: 0px!important;
    }
    .page-edit-simple .image-input .d-grid {
        display: block!important;
    }
    .page-edit-simple .ck-editor:not(:hover) .ck-editor__top{
        visibility: hidden;
    }
    .page-edit-simple .ck-editor:not(:hover) .ck-content {
        border: 0px;
        box-shadow: none;
    }
    .page-edit-simple .text-editor-input .form-label {
        display: none;
    }
</style>

<template>
<div class="page-edit-simple">
    <div class="page-title-box mt-3 mb-3">
        <h4 class="page-title">Page</h4>
    </div>

    <ul class="nav">
        <li class="nav-item">
            <a @click="tab = 'Content'" :class="'nav-link ' + (tab == 'Content' ? 'active' : '')" href="#">General</a>
        </li>
        <li class="nav-item">
            <a @click="tab = 'Meta'" :class="'nav-link ' + (tab == 'Meta' ? 'active' : '')" href="#">SEO</a>
        </li>
    </ul>

    <template v-for="(row, row_index) in content" v-if="content">
        <template v-for="(column, column_index) in row.columns">
            <template v-if="column.template.simple_view == true && column.template.tab == tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div v-if="column.component != 'text_editor'" class="col-12">
                                        <h5>{{ column.template.title }}</h5>
                                    </div>
                                    <multiput
                                        v-for="(input, input_index) in column.template.inputs"
                                        v-show="!column.template.dynamic"
                                        v-if="input.simple_view != false"
                                        :key="input.key"
                                        :keyput="input.key + row_index + column_index + input_index"
                                        :label="input.label"
                                        :type="input.type"
                                        :value="input.value"
                                        :tabsProp="input.tabs ? input.tabs : []"
                                        :size="input.size"
                                        @inputed="inputed($event, input)"
                                        @inputedTabs="inputedTabs($event, input)"
                                        :route="input.route"
                                        :multi="false"
                                        :existingValue="input.value"
                                        :uuid="$uuid.v4()"
                                        
                                        :inputs="input.inputs"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </template>
    </template>
</div> 
</template>

<script>

    export default {
        props: {
        },
        data() {
            return {
                page_id: null,
                page: null,
                content: null,
                tab: "Content",
            }
        },
        methods: {
            getPage() {
                if (this.page_id){
                    axios
                    .get('/api/pages/' + this.page_id)
                    .then((response) => {
                        if (response.status == 200){
                            this.page = response.data.data;
                            this.content = JSON.parse(this.page.content);
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    });
                }
            }
        },
        mounted() {
            this.page_id = this.$attrs.id;
            this.getPage();
        }
    }
</script>
