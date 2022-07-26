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
    <modal :name="name" :adaptive="false" :height="750" :width="675" @before-open="getParams">

        <div class="row pt-2 ps-4 pe-4">
            <div class="col-6 sm-auto">
                <h3>Select component</h3>
            </div>
            <div class="col-6 sm-auto text-end">
                    <i @click="$modal.hide(name)" class="dripicons-cross fs-1"></i>
            </div>
        </div>

        <div class="row ps-4 pe-4 mb-2">
            <div class="col-12">
                <nav class="nav nav-pills nav-justified">
                    <a @click="type = 'general'" :class="'nav-link ' + getActive('general')" aria-current="page" href="#">Website</a>
                    <!-- <a @click="type = 'plugins'" :class="'nav-link ' + getActive('plugins')" href="#">Plugins</a> -->
                    <a @click="type = 'type-related'" :class="'nav-link ' + getActive('type-related')" href="#">Dynamic</a>
                </nav>
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
                                <span class="text-muted mt-1 ms-1 me-1">Benefits</span>
                            </div>
                        </div>

                        <div v-if="name != 'tabs'" class="col-3">
                            <div @click="selectComponent('tabs')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Tabbed menu</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('horizintal_line')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-minus d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Horizontal line</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('menus')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-menu d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Menus</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('blog_header')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-document d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Title & Subtitle</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('empty')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-wrong d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Empty space</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('html')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-code d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Embed htlm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div v-if="column === null" class="col-3">
                            <div @click="selectComponent('comments')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-align-justify d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Comments</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('social_bar')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-web d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Social sharing</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('contact_form')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-mail d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Form</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('terms_conditions')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-checkmark d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Accept terms</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('event_search_result')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-search d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Event search</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('blog_search_result')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-search d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Blog search</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('text_editor')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-document-edit d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Content Body</span>
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

                        <div class="col-3">
                            <div @click="selectComponent('gallery')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-photo-group d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Image gallery</span>
                            </div>
                        </div>
                    </div>

                </template>

                <template v-if="type == 'plugins'">

                </template>

                <template v-if="type == 'type-related'">

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('instructor_avatar')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Instructor avatar</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('instructor_courses')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Instructor's lessons</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('instructor_events')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list-large d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Instructor's courses</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('course_author')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-user-id d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course manager</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('book_ticket')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-card d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course tickets</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('benefits')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-thumb d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course benefits</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('topics')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-menu d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course topics</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('course_instructors')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-user-group d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course instructors</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('course_video_testimonials')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-camcorder d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course video testimonials</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('course_written_testimonials')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-message d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course written testimonials</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('course_faq')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-search d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course Q & A</span>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-3">
                            <div @click="selectComponent('course_details')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course details</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('course_location')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-location d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Course location</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                    
                        <div class="col-3">
                            <div @click="selectComponent('blog_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-blog d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Blog list</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('instructors_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-checklist d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Instructors list</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('events_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list-large d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Courses list</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('city_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list-large d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">City list</span>
                            </div>
                        </div>
                  
                    </div>

                    <div class="row mb-2">

                        <div class="col-3">
                            <div @click="selectComponent('homepage_events')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list-large d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Hompage events</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('homepage_gallery')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-view-list-large d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Hompage Gallery</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('knowledge_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-blog d-block mt-1"></i>
                                <span class="text-muted mt-1 ms-1 me-1">Knowledge list</span>
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
