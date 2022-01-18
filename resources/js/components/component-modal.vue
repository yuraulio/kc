<template>
    <modal name="component-modal" :resizable="true" height="auto" :adaptive="true" @before-open="getParams">
        <div class="p-4 row">
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

            <div class="col-md-6 col-xl-6">
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
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" @click="rearange()" class="btn btn-lg btn-secondary waves-effect waves-light">Rearange</button>
        </div>
        </modal>
</template>

<script>
export default {
    data() {
        return {
            //
        }
    },
    methods: {
        getParams(params) {
            console.log("params", params)
        },
        selectComponent(component) {
            console.log("sending component")
            eventHub.$emit('component-added', component);
        },
        rearange() {
            eventHub.$emit('component-rearange')
        },
    }
}
</script>
