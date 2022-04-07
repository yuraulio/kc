<style scoped>
    .modal-size {
        overflow-y: auto !important;
    }
    .components .card {
        width: 120px;
    }
    .widget-rounded-circle {
        cursor: pointer;
    }
</style>

<template>
    <modal :name="name" :adaptive="false" :height="300" :width="675" @before-open="getParams">

        <div class="row pt-2 ps-4 pe-4">
            <div class="col-6 sm-auto">
                <h3>Select component</h3>
            </div>
            <div class="col-6 sm-auto text-end">
                    <i @click="$modal.hide(name)" class="dripicons-cross fs-1"></i>
            </div>
        </div>

        <div class="p-4 pt-2 pe-3 row modal-size">
            <div class="components">

                <template v-if="type == 'general'">

                    <div class="row mb-2">

                        <div class="col-3">
                            <div @click="selectComponent('image')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-photo d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Image</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('inspirational_teaser')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Info & benefits</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('gallery')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-photo-group d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Image gallery</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('blog_header')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-document d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Title & Subtitle</span>
                            </div>
                        </div>

                       
                    </div>

                    <div class="row mb-2">
                       
                        <div class="col-3">
                            <div @click="selectComponent('contact_form')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-mail d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Form</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('text_editor')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-document-edit d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Rich text editor</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('hero')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-monitor d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Header</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('youtube')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-camcorder d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Youtube</span>
                            </div>
                        </div>
                    </div>

                </template>

            </div>
        </div>
    </modal>
</template>

<script>
export default {
    props: {
        row: Number,
        column: Number,
        name: String,
    },
    data() {
        return {
            type: "general",
        }
    },
    methods: {
        getParams(params) {
        },
        selectComponent(component) {
            if (this.row !== null && this.column !== null){
                eventHub.$emit('component-change-' + this.name, [component, this.row, this.column]);
            } else {
                eventHub.$emit('component-added-' + this.name, component);
            }
        },
        rearange() {
            eventHub.$emit('component-rearange-' + this.name)
        },
        getActive(tab){
            if (tab == this.type) {
                return "active";
            }

            return "";
        }
    }
}
</script>
