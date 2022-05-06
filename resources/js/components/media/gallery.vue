<template>
<div class="card-body">
    <!--
    <h4 class="header-title">
        <button @click="confirmSelection" v-if="selectedImages.length" class="float-end btn btn-soft-success btn-rounded">
            Confirm Selection
        </button>
    </h4>
    -->

    <div class="row">
        <div class="col">
            <h3 v-if="mainDysplayImage" class="text-truncate pb-1" :title="mainDysplayImage.name">{{mainDysplayImage.name}}</h3>
            <h3 v-else class="text-truncate pb-1" :title="opImage.name">{{opImage.name}}</h3>
        </div>
        <div class="col-auto">
            <button @click="saveToClipboard()" class="btn btn-soft-primary"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
        </div>
        <div class="col-auto">
            <a :href="opImage.url" target="_blank" class="btn btn-soft-primary"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <div style="height: 600px" id="" class="carousel">
                <div class="carousel-inner" role="listbox">
                    <div v-if="activeImg" v-for="(img, index) in selectedImage ? [selectedImage] : images" :class="'carousel-item ' + (activeImg.id == img.id ? ' active' : '')" :key="index">
                        <template v-if="!lodash.find(img.subfiles, {'subselected' : true})">
                            <div v-if="img.extension.toLowerCase() == 'pdf'" style="width: 100%; height: 300px" class=" text-secondary rounded text-center">
                                <i class="mdi mdi-file-pdf-outline" style="font-size: 160px;"></i>
                            </div>

                            <img v-else-if="imageExtensions.includes(img.extension.toLowerCase())" @click="confirmSelection(img)" :src="img.url" alt="..." class="d-block img-fluid" />

                            <div v-else style="width: 100%; height: 300px" class=" text-secondary rounded text-center">
                                <i class="mdi mdi-file" style="font-size: 160px;"></i>
                            </div>
                            
                        </template>
                        <template v-else>
                            <img @click="confirmSelection(lodash.find(img.subfiles, {'subselected' : true}))" :src="lodash.find(img.subfiles, {'subselected' : true}).url" alt="..." class="d-block img-fluid" />
                        </template>
                    </div>
                    <a class="carousel-control-prev" href="#" role="button" @click.prevent="list('back')" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#" role="button" @click.prevent="list('next')" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div>
        </div>

        <div v-if="activeImg && activeImg.parrent == null && sidebarImages.length" class="col-md-3">
            <div class="mt-4" style="max-height: 500px; overflow: hidden; overflow-y: scroll; text-align: center; background-color:#f3f7f9 !important; padding:20px;">
                <div v-for="(im, index) in sidebarImages" style="cursor: pointer" :key="index" class="mb-2">
                    <template v-if="getVersion(im.version)">
                        <h5 class="text-start">
                            {{ getVersion(im.version).version }}
                            <i @click="deleteFile(im)" class="mdi mdi-delete text-muted vertical-middle"></i>
                        </h5>
                        <p class="text-start text-muted d-block mb-2">{{ getVersion(im.version).description }}</p>
                    </template>
                    <template v-else>
                        <h5>
                            Custom
                            <i @click="deleteFile(im)" class="mdi mdi-delete text-muted vertical-middle"></i>
                        </h5>
                    </template>

                    <img @click="selectImg(im)" :src="im.url" alt="image" class="img-fluid rounded" width="200" :style="'height: 100px; width: auto; ' + (im.subselected ? ' border: 4px solid #1abc9c;' : ' border: 4px solid #f3f7f9;')">
                    <p class="mb-0 text-truncate" :title="im.name">
                        {{im.name}}
                    </p>
                    <hr class="mt-2 mb-2">
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>


export default {
    props: {
        images: {},
        opImage: {},
        imageExtensions: [],
    },
    data() {
        return {
            selectedImage: null,
            activeImg: null,
            lodash: _,
            beforeActive: null,
            subactiveImg: null,
            selectedImages: [],
            mainDysplayImage: null,
            versions: [{
                    w: 470,
                    h: 470,
                    q: 60,
                    fit: "crop",
                    version: "instructors-testimonials",
                    description: "Applies to : Our Instructor Page (Footer) & Event -> Instructors",
                },
                {
                    w: 542,
                    h: 291,
                    q: 60,
                    fit: "crop",
                    version: "event-card",
                    description: "Applies to : Homepage Events list",
                },
                {
                    w: 470,
                    h: 470,
                    q: 60,
                    fit: "crop",
                    version: "users",
                    description: "Applies to : Testimonial square image",
                },
                {
                    w: 2880,
                    h: 1248,
                    q: 60,
                    fit: "crop",
                    version: "header-image",
                    description: "Applies to: Event header carousel (Main event page)",
                },
                {
                    w: 90,
                    h: 90,
                    q: 60,
                    fit: "crop",
                    version: "instructors-small",
                    description: "Applies to : Event -> Topics (syllabus-block)",
                },
                {
                    w: 300,
                    h: 300,
                    q: 60,
                    fit: "crop",
                    description: "feed-image",
                    version: "feed-image",
                },
                {
                    w: 1920,
                    h: 832,
                    q: 60,
                    fit: "crop",
                    version: "social-media-sharing",
                    description: "Applies to: Social media sharing default image",
                },
                {
                    w: 680,
                    h: 320,
                    q: 60,
                    fit: "crop",
                    version: "blog-content",
                    description: "Applies to: Blog content image",
                },
                {
                    w: 343,
                    h: 193,
                    q: 60,
                    fit: "crop",
                    version: "blog-featured",
                    description: "Applies to: Blog Featured image",
                },
            ],
        };
    },
    computed: {
        currentImages() {
            return this.images ?
                _.map(this.images, function (o) {
                    return o.url;
                }) :
                [];
        },
        sidebarImages() {
            return this.activeImg ? this.lodash.find(this.images, {id: this.activeImg.id}).subfiles : [];
        }
    },
    methods: {
        deleteFile(file) {
            file.parent = file.parent_id;
            this.$parent.$parent.deleteFile(file);
        },
        confirmSelection(image) {
            if (this.$parent.$parent.mode != null ) {
                this.$parent.$parent.updatedMediaImage(image);
                this.$modal.hide('gallery-modal');
                this.$toast.success('New image selected!');
            }
        },
        selectImg(img) {
            var subfiles = _.find(this.images, {
                id: this.activeImg.id
            }).subfiles;
            if (img && !img.subselected) {
                this.mainDysplayImage = img;
                this.$set(img, 'subselected', true);
            } else {
                this.mainDysplayImage = this.opImage;
                this.$set(img, 'subselected', false);
            }
            subfiles.forEach(element => {
                if (element.id != img.id) {
                    this.$set(element, 'subselected', false);
                }
            });
        },
        list(type) {
            if (type == 'back') {
                this.activeImg = _.findIndex(this.images, {
                        id: this.activeImg.id
                    }) > 0 ?
                    this.images[_.findIndex(this.images, {
                        id: this.activeImg.id
                    }) - 1] : this.images[this.images.length - 1]
            } else {
                this.activeImg = _.findIndex(this.images, {
                        id: this.activeImg.id
                    }) != this.images.length - 1 ?
                    this.images[_.findIndex(this.images, {
                        id: this.activeImg.id
                    }) + 1] : this.images[0]
            }
        },
        saveToClipboard() {
            var text = "";
            if (this.mainDysplayImage) {
                text = this.mainDysplayImage.url;
            } else {
                text = this.opImage.url;
            }
            this.$copyText(text)
            .then((e) => {
                this.$toast.success('Copied');
                console.log(e)
            }, function (e) {
                console.log(e)
            });
        },
        getVersion(version) {
            var return_value = null;
            if (version) {
                this.versions.forEach(function(version1){
                    if (version1.version == version) {
                        return_value = version1;
                    }
                });
            }
            return return_value;
        }
    },
    mounted() {
        this.activeImg = this.opImage ? this.opImage : null;
        //console.log("cim", this.currentImages);
    },
    watch: {
        "images": {
            handler: function () {
                this.selectedImages = [];
                this.images.forEach((element) => {
                    if (element.selected == true) {
                        this.selectedImages.push(element);
                    }
                    element.subfiles = element.subfiles ? element.subfiles : [];
                    element.subfiles.forEach((sub) => {
                        if (sub.selected == true) {
                            this.selectedImages.push(sub);
                        }
                    })
                })
                // console.log("selectedimgs", this.selectedImages, this.$parent)

            },
            deep: true
        }
    },
};
</script>

<style scoped>
.carousel {
    justify-content: center;
    position: relative;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
    height: 90%;
    display: flex;
}

.carousel-item .active {
    display: inline-flex !important;
    justify-content: center !important;
}

.img-fluid {
    margin: 0 auto !important;
    height: auto!important;
    width: auto!important;
}

.carousel-inner {
    position: relative;
    width: 100%;
    overflow: hidden;
    max-height: 600px;
    object-fit: contain;
    display: flex;
    align-items: center;
    flex-direction: row;
    height: 100%;
}

.carousel-control-prev:hover {
    background-color: rgba(0, 0, 0, 0.15)
}

.carousel-control-next:hover {
    background-color: rgba(0, 0, 0, 0.15)
}

.carousel-caption {
    display: flex;
    align-self: flex-end;
}
</style>
