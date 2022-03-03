<style scoped>
    .modal-size {
        overflow: hidden;
    }
    .components .card {
        width: 120px;
    }
</style>

<template>
    <modal :name="name" :adaptive="true" :height="500" :width="675" @before-open="getParams">

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
                    <a @click="type = 'general'" :class="'nav-link ' + getActive('general')" aria-current="page" href="#">General input</a>
                    <a @click="type = 'plugins'" :class="'nav-link ' + getActive('plugins')" href="#">Plugins</a>
                    <a @click="type = 'type-related'" :class="'nav-link ' + getActive('type-related')" href="#">Type related</a>
                </nav>
            </div>
        </div>

        <div class="p-4 pt-2 pe-3 row modal-size">
            <div class="components">

                <template v-if="type == 'general'">

                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('text_editor')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-document-edit d-block mt-1"></i>
                            <span class="text-muted mt-1">Rich Text</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('content_box')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                            <span class="text-muted mt-1">Teaser</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('hero')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-monitor d-block mt-1"></i>
                            <span class="text-muted mt-1">Hero Header</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('image')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-photo d-block mt-1"></i>
                            <span class="text-muted mt-1">Image</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('gallery')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-photo-group d-block mt-1"></i>
                            <span class="text-muted mt-1">Galery</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('blog_header')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Title & Subtitle</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('youtube')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-camcorder d-block mt-1"></i>
                            <span class="text-muted mt-1">Youtube</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('html')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-code d-block mt-1"></i>
                            <span class="text-muted mt-1">Embed htlm</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('empty')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-wrong d-block mt-1"></i>
                                <span class="text-muted mt-1">Empty space</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('horizintal_line')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-minus d-block mt-1"></i>
                                <span class="text-muted mt-1">Horizontal line</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('inspirational_teaser')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                                <span class="text-muted mt-1">Inspirational teaser</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('event_teaser')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-article d-block mt-1"></i>
                                <span class="text-muted mt-1">Event teaser</span>
                            </div>
                        </div>
                    </div>

                </template>

                <template v-if="type == 'plugins'">

                    <div class="row mb-2">
                    <div v-if="column === null" class="col-3">
                        <div @click="selectComponent('comments')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-align-justify d-block mt-1"></i>
                            <span class="text-muted mt-1">Comments</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('social_bar')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-web d-block mt-1"></i>
                            <span class="text-muted mt-1">Social</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('contact_form')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-mail d-block mt-1"></i>
                            <span class="text-muted mt-1">Contact form</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('menus')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-menu d-block mt-1"></i>
                            <span class="text-muted mt-1">Menus</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('instructor_avatar')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                            <span class="text-muted mt-1">Instructor avatar</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('instructor_text')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                            <span class="text-muted mt-1">Instructor text</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('instructor_courses')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                            <span class="text-muted mt-1">Instructor courses</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('instructor_events')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-user d-block mt-1"></i>
                            <span class="text-muted mt-1">Instructor events</span>
                        </div>
                    </div>
                    </div>
                
                </template>

                <template v-if="type == 'type-related'">
                
                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('post_feature')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-blog d-block mt-1"></i>
                            <span class="text-muted mt-1">Post list</span>
                        </div>
                    </div>

                    <div v-if="name != 'tabs'" class="col-3">
                        <div @click="selectComponent('tabs')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Tabs</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_text')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course text</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_details')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course details</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('course_author')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course author</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_video')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course video</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('book_ticket')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Book ticket</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('benefits')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course benefits</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                    <div class="col-3">
                        <div @click="selectComponent('topics')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course topics</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_instructors')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course instructors</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_testimonials')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course testimonials</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div @click="selectComponent('course_location')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                            <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                            <span class="text-muted mt-1">Course location</span>
                        </div>
                    </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div @click="selectComponent('course_faq')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1">Course faq</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('course_hero')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1">Course hero</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('blog_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1">Blog list</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div @click="selectComponent('instructors_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1">Instructors list</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                         <div class="col-3">
                            <div @click="selectComponent('events_list')" class="widget-rounded-circle card bg-grey text-center d-inline-block h-100 mb-1">
                                <i style="font-size: 20px" class="text-muted dripicons-browser d-block mt-1"></i>
                                <span class="text-muted mt-1">Events list</span>
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
