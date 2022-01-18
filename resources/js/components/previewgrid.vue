<template>
<div>
    <div :key="preview" v-show="!data" class="col-lg-12">
            <div class="card">
                <div class="card-body text-center">
                    No Components Selected
                </div>
            </div>
        </div>
        <div v-show="data" class=" ">
            <div class="">
            <draggable v-model="data">
                <transition-group>
                    <div :class="'row ' + (value.width == 'full' ? '' : (value.width == 'content' ? 'col-lg-8 offset-lg-2' : 'col-lg-6 offset-lg-3'))" v-for="(value, ind) in data" :key="ind + 'drag'">
                            <div  :class="'col-lg-' + (12 / value.columns.length)" v-for="(column, indr) in value.columns" :key="indr + 'dragi'">
                                <div v-if="column.component != 'meta'">
                                <div v-if="column.component == 'hero'" class="card  text-white" style="background-color: #6c757d !important">
                                    <div v-if="!preview || (preview && !lodash.find(column.template.inputs, {'key': 'hero_image' }).value)" class="card-img img-fluid text-center cimgfl" style="min-height: 400px" alt="Card image">
                                        <i class="h1 text-muted  dripicons-photo-group
                                        " style="font-size: 8em;"></i>
                                    </div>
                                    <img v-else class="card-img img-fluid" style="object-fit: cover; max-height: 400px" :src="lodash.find(column.template.inputs, {'key': 'hero_image' }).value" alt="Card image">
                                    <div class="card-img-overlay" :style="(preview && !lodash.find(column.template.inputs, {'key': 'hero_image' }).value) ? 'position: relative; ' :'top: 200px'">
                                        <h2 class="card-title text-white">{{ preview ? lodash.find(column.template.inputs, {'key': 'hero_title' }).value : 'Hero Title' }}</h2>
                                        <h4 class="card-text text-white">{{ preview ? lodash.find(column.template.inputs, {'key': 'hero_subtitle' }).value : 'Hero Subtitle' }}</h4>

                                    </div>
                                </div>
                                <div v-else-if="column.component == 'image'" class="card  text-white" style="background-color: #6c757dd6 !important">
                                    <div v-if="!preview || (preview && !lodash.find(column.template.inputs, {'key': 'full_size_image' }).value)" class="card-img img-fluid text-center cimgfl" style="min-height: 300px" alt="Card image">
                                        <i class="h1 text-muted  dripicons-photo-group
                                        " style="font-size: 8em;"></i>
                                    </div>
                                    <img v-else class="card-img img-fluid" style="object-fit: cover; max-height: 400px" :src="lodash.find(column.template.inputs, {'key': 'full_size_image' }).value" alt="Card image">
                                </div>
                                <div v-else-if="column.component == 'text_editor'" class="pt-3">
                                    <div class="">
                                        <div v-if="!preview || (preview && !lodash.find(column.template.inputs, {'key': 'rich_text_box_title' }).value)" class="font-16 text-center fst-italic text-dark">
                                            <i class="mdi mdi-format-quote-open font-20"></i> Cras sit amet nibh libero, in
                                            gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                                            purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
                                            sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
                                            porta. Mauris massa.
                                        </div>

                                        <div v-if="(preview && lodash.find(column.template.inputs, {'key': 'rich_text_box_title' }).value)" class="font-16 text-dark" v-html="lodash.find(column.template.inputs, {'key': 'rich_text_box_title' }).value">

                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="column.component == 'content_box'" class="card">
                                    <img style="height: 200px; background-color: #6c757dd6" v-if="(preview && lodash.find(column.template.inputs, {'key': 'content_box_image' }).value)" class="card-img-top img-fluid" :src="lodash.find(column.template.inputs, {'key': 'content_box_image' }).value" alt="Card image cap">

                                    <div v-else class="card-img img-fluid text-center cimgfl" style="min-height: 200px; background-color: #6c757dd6" alt="Card image">
                                        <i class="h1 text-muted  dripicons-photo-group
                                        " style="font-size: 8em;"></i>
                                    </div>

                                    <div class="card-body text-center">
                                        <h5 v-if="(preview && lodash.find(column.template.inputs, {'key': 'content_box_title' }).value)" class="card-title">{{lodash.find(column.template.inputs, {'key': 'content_box_title' }).value}}</h5>
                                        <h5 v-else class="card-title">Card title</h5>

                                        <p v-if="(preview && lodash.find(column.template.inputs, {'key': 'content_box_subtitle' }).value)" class="card-text">{{lodash.find(column.template.inputs, {'key': 'content_box_subtitle' }).value}}</p>

                                        <p v-else class="card-text">This is a wider card with supporting text below as a
                                            natural.</p>

                                        <p v-if="(preview && lodash.find(column.template.inputs, {'key': 'content_box_btn_text' }).value)" class="card-text text-center">
                                            <button class="btn btn-secondary">{{lodash.find(column.template.inputs, {'key': 'content_box_btn_text' }).value}}</button>
                                        </p>

                                        <p v-else class="card-text text-center">
                                            <button class="btn btn-secondary">Button</button>
                                        </p>
                                    </div>
                                </div>
                                <div v-else-if="column.component == 'blog_header'" class="card">
                                    <div class="text-center">
                                        <h2 v-if="(preview && lodash.find(column.template.inputs, {'key': 'blog_header_title' }).value)" >{{lodash.find(column.template.inputs, {'key': 'blog_header_title' }).value}}</h2>
                                        <h2 v-else>Blog Title</h2>
                                        <p v-if="(preview && lodash.find(column.template.inputs, {'key': 'blog_header_subtitle' }).value)" class="card-text">{{lodash.find(column.template.inputs, {'key': 'blog_header_subtitle' }).value}}</p>
                                        <h4 v-else class="">
                                            This is a wider card with supporting text below as a
                                            natural.
                                        </h4>
                                    </div>
                                </div>
                                <div v-else class="card border rounded bg-grey col-12" >
                                <div v-if="column.template.visible" :class="'card-body p-3'">
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
</div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    props: ['gedata', 'pseudo', 'preview'],
    data() {
        return {
            lodash: _,
            data: this.gedata
        }
    },
    components: {
        draggable
    },
    mounted() {
        console.log("prev", this.data)
        this.data = this.gedata;
    },
    watch: {
        preview() {
            console.log('preview-changed')
        },
        data: {
            deep: true,
            handler(val) {
                eventHub.$emit('order-changed', this.data)
            }
        },
        gedata: {
            deep: true,
            handler(val) {
                console.log("PREDATA CHANGED")
                this.data = this.gedata
            }
        }
    }
}
</script>

<style scoped>
.cimgfl {
    display: flex;
    justify-content: center;
    align-content: space-between;
    flex-direction: column;
}
.card {
    transition: box-shadow .5s;
    cursor: pointer;
    box-shadow: 0 0 11px rgb(33 33 33 / 15%);
}
.offcanvas-body {
    flex-grow: 1;
    padding: 1rem 1rem;
    overflow-y: auto;
    margin: 0px;
    padding: 0px !important;
}
</style>
