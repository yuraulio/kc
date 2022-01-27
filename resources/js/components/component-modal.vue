<style scoped>
    .modal-size {
        overflow-y: scroll;
        height: 70vh;
    }
</style>

<template>
    <modal name="component-modal" :resizable="true" height="auto" :adaptive="true" @before-open="getParams">

        <div class="p-4 row modal-size">
            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('text_editor')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted  dripicons-document-edit"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Rich Text</h5>
                                <p class="mb-2 text-muted">The Editor</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('content_box')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-article"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Content Box</h5>
                                <p class="mb-2 text-muted">Teaser</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div v-if="column === null" class="col-md-6 col-xl-6">
                <div @click="selectComponent('hero')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-monitor"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Hero Header</h5>
                                <p class="mb-2 text-muted">Page header</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('image')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted  dripicons-photo-group
                                            "></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Image</h5>
                                <p class="mb-2 text-muted">Full size img</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

             <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('text_box')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-document-edit"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Text Area</h5>
                                <p class="mb-2 text-muted">Free input field</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('blog_header')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-browser"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Title/Subtitle </h5>
                                <p class="mb-2 text-muted">Title and subtitle</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

             <div v-if="column === null" class="col-md-6 col-xl-6">
                <div @click="selectComponent('comments')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-align-justify"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Comments </h5>
                                <p class="mb-2 text-muted">Pre component</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('social_bar')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-web"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Social </h5>
                                <p class="mb-2 text-muted">Social Bar</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('youtube')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-camcorder"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Youtube </h5>
                                <p class="mb-2 text-muted">Embed youtube video</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('html')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-code"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Embed htlm </h5>
                                <p class="mb-2 text-muted">Html code</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('contact_form')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-mail"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Contact form </h5>
                                <p class="mb-2 text-muted">Contact us</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

            <div class="col-md-6 col-xl-6">
                <div @click="selectComponent('empty')" class="widget-rounded-circle card bg-grey">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg mt-2">
                                    <i style="font-size: 4em" class="h1 text-muted dripicons-wrong"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1 mt-2 font-16">Empty space</h5>
                                <p class="mb-2 text-muted">No content</p>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>

            <div class="col-lg-12 sm-auto">
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" @click="rearange()" class="btn btn-block btn-soft-info rounded-pill waves-effect waves-light m-1t">Rearange</button>
            <button @click="$modal.hide('component-modal')" type="button" class="btn btn-block btn-soft-danger rounded-pill waves-effect waves-light m-1t">Close</button>
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
            //
        }
    },
    methods: {
        getParams(params) {
            // console.log("params", params)
        },
        selectComponent(component) {
            // console.log("sending component")
            if (this.row !== null && this.column !== null){
                eventHub.$emit('component-change', [component, this.row, this.column]);
            } else {
                eventHub.$emit('component-added', component);
            }
        },
        rearange() {
            eventHub.$emit('component-rearange')
        },
    }
}
</script>
