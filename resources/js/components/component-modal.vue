<style scoped>
    .modal-size {
        overflow: hidden;
    }
    .components .card {
        width: 120px;
    }
</style>

<template>
    <modal name="component-modal" :adaptive="true" :height="400" @before-open="getParams">

        <div class="row pt-2 ps-4 pe-4">
            <div class="col-6 sm-auto">
                <h3>Select component</h3>
            </div>
            <div class="col-6 sm-auto text-end">
                    <i @click="$modal.hide('component-modal')" class="dripicons-cross fs-1"></i>
            </div>
        </div>

        <div class="row ps-4 pe-4 mb-2">
            <div class="col-12">
                <nav class="nav nav-pills nav-justified">
                    <a @click="type = 'general'" :class="'nav-link ' + getActive('general')" aria-current="page" href="#">General input</a>
                    <a @click="type = 'plugins'" :class="'nav-link ' + getActive('plugins')" href="#">Plugins</a>
                    <a @click="type = 'type-related'" :class="'nav-link ' + getActive('type-related')" href="#">Type related</a>
                </nav>
            </div>
        </div>

        <div class="p-4 pt-2 pe-3 row modal-size">
            <div class="col-12 components">

                <template v-if="type == 'general'">

                    <div @click="selectComponent('text_editor')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-document-edit d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Rich Text</span>
                    </div>

                    <div @click="selectComponent('content_box')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Teaser</span>
                    </div>

                    <div @click="selectComponent('hero')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-monitor d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Hero Header</span>
                    </div>

                    <div @click="selectComponent('image')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-photo d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Image</span>
                    </div>

                    <div @click="selectComponent('gallery')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-photo-group d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Galery</span>
                    </div>

                    <!--
                    <div @click="selectComponent('text_box')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-document-edit d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Text Area</span>
                    </div>
                    -->

                    <div @click="selectComponent('blog_header')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Title & Subtitle</span>
                    </div>

                    <div @click="selectComponent('youtube')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-camcorder d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Youtube</span>
                    </div>

                    <div @click="selectComponent('html')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-code d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Embed htlm</span>
                    </div>

                    <div @click="selectComponent('empty')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-wrong d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Empty space</span>
                    </div>

                    <div @click="selectComponent('horizintal_line')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-minus d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Horizontal line</span>
                    </div>

                </template>

                <template v-if="type == 'plugins'">

                    <div v-if="column === null" @click="selectComponent('comments')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-align-justify d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Comments</span>
                    </div>

                    <div @click="selectComponent('social_bar')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-web d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Social</span>
                    </div>

                    <div @click="selectComponent('contact_form')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-mail d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Contact form</span>
                    </div>

                    <div @click="selectComponent('menus')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-menu d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Menus</span>
                    </div>

                    <div @click="selectComponent('instructor_avatar')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                        <span class="text-muted mt-1">Instructor avatar</span>
                    </div>

                    <div @click="selectComponent('instructor_text')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                        <span class="text-muted mt-1">Instructor text</span>
                    </div>

                    <div @click="selectComponent('instructor_courses')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                        <span class="text-muted mt-1">Instructor courses</span>
                    </div>

                    <div @click="selectComponent('instructor_events')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                        <span class="text-muted mt-1">Instructor events</span>
                    </div>
                
                </template>

                <template v-if="type == 'type-related'">
                
                    <div @click="selectComponent('post_feature')" class="widget-rounded-circle card bg-grey text-center d-inline-block me-2 mb-2">
                        <i style="font-size: 20px" class="text-muted dripicons-blog d-block mt-1"></i>
                        <span class="text-muted m-2 mt-1">Post feature</span>
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
                eventHub.$emit('component-change', [component, this.row, this.column]);
            } else {
                eventHub.$emit('component-added', component);
            }
        },
        rearange() {
            eventHub.$emit('component-rearange')
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
