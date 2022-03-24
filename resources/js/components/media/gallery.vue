<template>
  <div class="card-body" >
    <h4 class="header-title">
        <button @click="confirmSelection" v-if="selectedImages.length" class="float-end btn btn-soft-success btn-rounded">
            Confirm Selection
        </button>
    </h4>

    <div class="row">
        <div class="col-9">
            <div style="height: 600px" id="" class="carousel">
                <div  class="carousel-inner" role="listbox">
                    <div v-for="(img, index) in selectedImage ? [selectedImage] : images" :class="'carousel-item ' + (activeImg == img.id ? ' active' : '')" :key="index">
                    <template v-if="!lodash.find(img.subfiles, {'subselected' : true})">
                        <div v-if="img.extension == 'pdf'" style="width: 100%; height: 300px" class=" text-secondary rounded text-center">
                            <i class="mdi mdi-file-pdf-outline font-28"></i>
                        </div>
                        <img v-else :src="img.url" alt="..." class="d-block img-fluid" />
                        <div class="carousel-caption d-none d-md-block card bg-dark" style="margin-bottom: -60px">
                            <h3 class="text-white">
                                {{ img.name.length < 40 ? img.name : limit(img.name, 40) + '...' }}
                                <span @click="$set(img, 'selected', false)" v-if="img.selected" class="badge bg-success rounded-pill float-end pr-2" style="line-height: 1.1;margin-right: 5px; margin-top: -25px;cursor: pointer">
                                    <i class="mdi mdi-check"></i>
                                </span>
                                <span @click="$set(img, 'selected', true)" v-else class="badge bg-light rounded-pill text-dark float-end pr-2" style="line-height: 1.1;margin-right: 5px; margin-top: -25px;cursor: pointer">
                                    <i class="mdi mdi-check"></i>
                                </span>
                            </h3>
                            <p>{{ img.url }}</p>
                        </div>
                    </template>
                    <template v-else>
                        <img
                            :src="lodash.find(img.subfiles, {'subselected' : true}).url"
                            alt="..."
                            class="d-block img-fluid"
                        />
                        <div class="carousel-caption d-none d-md-block card bg-dark" style="margin-bottom: -60px">
                            <h3 class="text-white">{{ lodash.find(img.subfiles, {'subselected' : true}).name }}
                                <span @click="$set(lodash.find(img.subfiles, {'subselected' : true}), 'selected', false)" v-if="lodash.find(img.subfiles, {'subselected' : true}).selected" class="badge bg-success rounded-pill float-end pr-2" style="line-height: 1.1;margin-right: 5px; margin-top: -25px;cursor: pointer"><i class="mdi mdi-check"></i></span>
                                <span @click="$set(lodash.find(img.subfiles, {'subselected' : true}), 'selected', true)" v-else class="badge bg-light rounded-pill text-dark float-end pr-2" style="line-height: 1.1;margin-right: 5px; margin-top: -25px;cursor: pointer"><i class="mdi mdi-check"></i></span>
                            </h3>
                            <p>{{ img.url }}</p>
                        </div>
                    </template>
                </div>
            <a
                class="carousel-control-prev"
                href="#"
                role="button"
                @click.prevent="list('back')"
                data-bs-slide="prev"
            >
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a
                class="carousel-control-next"
                href="#"
                role="button"
                @click.prevent="list('next')"
                data-bs-slide="next"
            >
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
            </div>
        </div>
        </div>
        <div class="col-3">
            <div class="mt-4" style="max-height: 500px; overflow: hidden; overflow-y: scroll; text-align: center; background-color:#f3f7f9 !important; padding:20px;">
                <div  v-for="(im, index) in activeImg ? lodash.find(images, {id: activeImg}).subfiles : []" style="cursor: pointer" :key="index" class="mb-2">
                    <img @click="selectImg(im)" :src="im.url" alt="image" class="img-fluid rounded" width="200" :style="'height: 100px; width: auto; ' + (im.subselected ? '    border: 4px solid #1abc9c;' : '')">
                    <p class="mb-0">
                        {{ limit(im.name, 20) }}...
                        <i @click="deleteFile(im)" class="mdi mdi-delete me-2 text-muted vertical-middle"></i>
                    </p>
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
        opImage: {}
    },
    data() {
        return {
            selectedImage: null,
            activeImg: null,
            lodash: _,
            beforeActive: null,
            subactiveImg: null,
            selectedImages: []
        };
    },
    computed: {
        currentImages() {
        return this.images
            ? _.map(this.images, function (o) {
                return o.url;
            })
            : [];
        },
    },
    methods: {
        deleteFile(file) {
            this.$parent.$parent.deleteFile(file);
        },
        confirmSelection() {
            if (this.$parent.$parent.mode == 'single') {
                var image = this.selectedImages[0];
                delete image.subfiles;
                this.$parent.$parent.updatedMediaImage(image);
            } else {
                var images = this.selectedImages;
                images.forEach(function(image) {
                    delete image.subfiles;
                });
                console.log("gallery", images);
                this.$parent.$parent.updatedMediaImage(images);
            }

            this.$modal.hide('gallery-modal');
            this.$toast.success('New image selected!');
        },
        selectImg(img) {
            var subfiles = _.find(this.images, {id: this.activeImg}).subfiles;
            if (img && !img.subselected) {
                this.$set(img, 'subselected', true);
            } else {
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
                this.activeImg = _.findIndex(this.images, {id : this.activeImg }) > 0 ?
                this.images[_.findIndex(this.images, {id : this.activeImg }) - 1].id : this.images[this.images.length - 1].id
            } else {
                this.activeImg = _.findIndex(this.images, {id : this.activeImg }) != this.images.length - 1 ?
                this.images[_.findIndex(this.images, {id : this.activeImg }) + 1].id : this.images[0].id
            }
        },
        limit (string = '', limit = 0) {
            return string.substring(0, limit)
        }
    },
    mounted() {
        this.activeImg = this.opImage ? this.opImage.id : null;
        //console.log("cim", this.currentImages);
    },
    watch: {
        "images": {
            handler: function() {
                this.selectedImages = [];
                this.images.forEach((element) => {
                    if (element.selected == true) {
                        this.selectedImages.push(element);
                    }
                    element.subfiles = element.subfiles ?? [];
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
    height: 500px;
    width: auto;
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
