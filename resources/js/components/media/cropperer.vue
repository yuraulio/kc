<template>
<div class="row">

    <div class="col-12">
        <div v-if="warning" class="alert alert-warning mt-3" role="alert">
            Images will be minified in up to two minutes.
        </div>
    </div>

    <div class="col-9">
        <div class="row">
            <div class="col-7">
                <h4>{{version}}</h4>
                <div v-show="imgSrc" :key="imgSrc ? imgSrc : 'emp'" class="img-cropper" style>
                    <vue-cropper ref="cropper" :checkCrossOrigin="false" :src="imgSrc" preview=".preview"/>
                </div>
                <label v-show="imgSrc == null" :name="'image'" style="width: 100%; min-height: 300px">
                    <form method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        <div class="dz-message needsclick" style="margin: 0 auto; min-height: 300px">
                            <i class="h1 text-muted dripicons-cloud-upload"></i>
                            <div class="text-center">
                                <input ref="input" type="file" name="image" accept="image/*" @change="setImage" />
                                <span class="font-16">Drop files here or click to upload</span>
                            </div>
                        </div>
                    </form>
                </label>
                <div class="btn-group mb-2 mt-2">
                    <button type="button" class="btn btn-soft-primary" @click.prevent="zoom(0.2)">
                        <i class="mdi mdi-magnify-plus-outline"></i>
                    </button>
                    <button type="button" class="btn btn-soft-primary" @click.prevent="zoom(-0.2)">
                        <i class="mdi mdi-magnify-minus-outline"></i>
                    </button>
                </div>
                <div class="btn-group mb-2 mt-2">
                    <button type="button" class="btn btn-soft-primary" @click.prevent="move(-10, 0)">
                        <i class="mdi mdi-arrow-left"></i>
                    </button>
                    <button type="button" class="btn btn-soft-primary" @click.prevent="move(10, 0)">
                        <i class="mdi mdi-arrow-right"></i>
                    </button>
                    <button type="button" class="btn btn-soft-primary" @click.prevent="move(0, 10)">
                        <i class="mdi mdi-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-soft-primary" @click.prevent="move(0, -10)">
                        <i class="mdi mdi-arrow-down"></i>
                    </button>
                </div>

                <div class="btn-group mb-2 mt-2">
                    <button type="button" class="btn btn-soft-primary" @click.prevent="rotate(90)">
                        <i class="mdi mdi-rotate-right"></i>
                    </button>
                    <button type="button" class="btn btn-soft-primary" @click.prevent="rotate(-90)">
                        <i class="mdi mdi-rotate-left"></i>
                    </button>

                </div>

                <div class="btn-group mb-2 mt-2" style="float: right">
                    <button type="button" class="btn btn-soft-primary" @click.prevent="reset">
                        Reset
                    </button>
                </div>
            </div>
            <div class="col-5">
                 <div class="row mb-2">
                <div class="col-lg-12 d-grid">
                    <p>Preview</p>
                    <div class="cropped-image preview" style="margin: 0px auto;"/>
                </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-12 d-grid mb-2">
                        <label class="form-label">Name</label>
                        <input v-model="imgname" type="text" class="form-control" />
                    </div>
                    <div class="col-lg-12 d-grid mb-2">
                        <label class="form-label">Alt text</label>
                        <input v-model="alttext" type="text" class="form-control" />
                    </div>
                    <div class="col-lg-12 d-grid mb-2">
                        <label class="form-label">Link</label>
                        <input v-model="link" type="text" class="form-control" />
                    </div>
                    <div class="col-lg-12 d-flex align-items-end mt-2">
                        <template>
                            <button @click="upload('edit')" class="btn btn-soft-success btn-block w-100" :disabled="isUploading">
                                <span v-if="isUploading"><i class="fas fa-spinner fa-spin"></i> Uploading...</span>
                                <span v-else>
                                    Update
                                </span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body bg-light" style="max-height: 50vh; overflow: hidden; overflow-y: scroll">
                <div class="tab-content pt-0">

                    <div class="tab-pane d-block" id="profile1">
                        <div class="col-sm-12">

                            <h5>Original image</h5>
                            <p v-if="parrentImage" class="text-start text-muted d-block">
                                {{ size(parrentImage.size) }}
                            </p>
                            <img @click="version='original'; selectedVersion=null; imgname=parrentImage.name; alttext=parrentImage.alt_text; link=parrentImage.link" crossorigin="anonymous" :src="parrentImage ? ('/uploads/' + parrentImage.path) : ''" alt="image" class="img-fluid rounded" />
                            <hr>

                            <template v-for="(version1, index) in versions">
                                <h5 class="">
                                    {{ version1.version }}
                                    <i v-if="findVersionData(version1.version)" @click="deleteFile(findVersionData(version1.version), index)" class="mdi mdi-delete text-muted vertical-middle cursor-pointer"></i>
                                </h5>
                                <p v-if="findVersionData(version1.version)" class="text-start text-muted d-block">
                                    {{ size(findVersionData(version1.version).size) }}
                                </p>
                                <p class="text-muted d-block mb-2">{{ version1.description }}</p>
                                <template v-if="findVersionData(version1.version) != null">
                                    <img @click="version=version1.version; selectedVersion=version1; versionSelected();" crossorigin="anonymous" :src="'/uploads/' + findVersionData(version1.version).path + '?key=' + imageKey" alt="image" class="img-fluid rounded" />
                                </template>
                                <template v-else>
                                    <button @click="version=version1.version; selectedVersion=version1; versionSelected();" class="btn btn-primary">Set image</button>
                                </template>
                                <hr>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
import uploadImage from "../inputs/upload-image.vue";

export default {
    props: {
        prevalue: {},
        imageKey: "",
        warning: false,
    },
    components: {
        VueCropper,
        uploadImage,
    },
    data() {
        return {
            selectedVersion: null,
            isUploading: false,
            originalFile: null,
            uploadedVersions: [],
            imgSrc: null,
            cropImg: "",
            data: null,
            imgname: "",
            alttext: "",
            link: "",
            cropBoxData: {},
            imgData: {},
            compression: 100,
            parrentImage: null,
            version: "original",
            width_ratio: null,
            height_ratio: null,
            versionData: null,
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
    mounted() {
        if (this.prevalue) {
            if (this.prevalue.parrent) {
                this.parrentImage = this.prevalue.parrent;
                this.version = this.prevalue.version;
            } else {
                this.parrentImage = this.prevalue;
                this.version = 'original';
            }

            this.imgSrc = '/uploads' + this.parrentImage.path;
            this.uploadedVersions = this.parrentImage.subfiles;
            this.originalFile = this.prevalue;

            this.imgname = this.parrentImage ? this.parrentImage.name : '';
            this.alttext = this.parrentImage.alt_text ? this.parrentImage.alt_text : '';
            this.link = this.parrentImage.link ? this.parrentImage.link : '';

            if (typeof FileReader === "function") {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.imgSrc = event.target.result;
                    this.imgSrc.setAttribute('crossorigin', 'anonymous');

                    // rebuild cropperjs with the updated source
                    this.$refs.cropper.replace(this.imgSrc);
                    setTimeout(() => {
                        this.getData();
                        this.getCropBoxData();
                        this.setCropBoxData();
                    }, 600);
                };

                this.$forceUpdate();
            }
        }
    },
    methods: {
        versionSelected() {
            if (this.selectedVersion) {
                var image_width, image_height;

                var img = new Image();
                img.onload = () => {
                    image_width = img.width;
                    image_height = img.height;
                    this.setCropBox(image_width, image_height)
                }
                img.src = this.parrentImage.url;

                this.versionData = this.findVersionData(this.selectedVersion.version);
                this.imgname = this.versionData ? this.versionData.name : "";
                this.alttext = this.versionData ? this.versionData.alt_text : "";
                this.link = this.versionData ? this.versionData.link : "";

                if (this.imgname == "") {
                    var tmp = this.parrentImage.name.split(".");
                    var extension = tmp[tmp.length - 1];
                    this.imgname = tmp[0] + "-" + this.version + "." + extension;
                }
                if (this.alttext == "") {
                    this.alttext = this.originalFile.alt_text;
                }
                if (this.link == "") {
                    this.link = this.originalFile.link;
                }

            }
        },
        setCropBox(image_width, image_height) {
            var cropper_height = this.$refs.cropper.$el.clientHeight;
            var cropper_width = this.$refs.cropper.$el.clientWidth;

            this.width_ratio = cropper_width / image_width;
            this.height_ratio = cropper_height / image_height;

            this.$set(this.cropBoxData, 'width', this.selectedVersion.w * this.width_ratio);
            this.$set(this.cropBoxData, 'height', this.selectedVersion.h * this.height_ratio);
            this.setCropBoxData();
        },
        calculateRatio(num_1, num_2){
            for(num=num_2; num>1; num--) {
                if((num_1 % num) == 0 && (num_2 % num) == 0) {
                    num_1=num_1/num;
                    num_2=num_2/num;
                }
            }
            var ratio = num_1+":"+num_2;
            return ratio;
        },
        nameWithLang({
            version,
            description
        }) {
            return `${version} — [${description}]`
        },
        upload(event) {
            this.getCropBoxData();
            this.$refs.cropper.getCroppedCanvas({
                width: this.cropBoxData.width,
                height: this.cropBoxData.height,
            }).toBlob(
                (blob) => {
                    // blob.version = this.version;
                    this.$emit(event, blob);
                },
                "image/jpeg",
                this.compression / 100
            );
        },
        imageAdded($event) {
            this.imgSrc = $event.url;
        },
        flipX() {
            const dom = this.$refs.flipX;
            let scale = dom.getAttribute("data-scale");
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleX(scale);
            dom.setAttribute("data-scale", scale);
        },
        flipY() {
            const dom = this.$refs.flipY;
            let scale = dom.getAttribute("data-scale");
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleY(scale);
            dom.setAttribute("data-scale", scale);
        },
        getCropBoxData() {
            //this.data = JSON.stringify(this.$refs.cropper.getCropBoxData());
            this.cropBoxData = JSON.parse(
                JSON.stringify(this.$refs.cropper.getCropBoxData())
            );
        },
        getData() {
            this.data = JSON.stringify(this.$refs.cropper.getData(), null, 4);
            this.imgData = JSON.parse(
                JSON.stringify(this.$refs.cropper.getData(), null, 4)
            );
        },
        move(offsetX, offsetY) {
            this.$refs.cropper.move(offsetX, offsetY);
        },
        reset() {
            this.$refs.cropper.reset();
            this.versionSelected();
            // this.getCropBoxData();
        },
        rotate(deg) {
            this.$refs.cropper.rotate(deg);
        },
        setCropBoxData() {
            for (const [key, value] of Object.entries(this.cropBoxData)) {
                this.$set(this.cropBoxData, key, parseFloat(value));
            }
            if (!this.cropBoxData) return;
            this.$refs.cropper.setCropBoxData(this.cropBoxData);
        },
        setData() {
            for (const [key, value] of Object.entries(this.imgData)) {
                this.$set(this.imgData, key, parseFloat(value));
            }
            if (!this.imgData) return;
            this.$refs.cropper.setData(this.imgData);
        },
        setImage(e) {
            console.log("set Image", e);
            const file = e.target.files[0];
            if (file.type.indexOf("image/") === -1) {
                alert("Please select an image file");
                return;
            }
            this.originalFile = file;
            this.imgname = this.originalFile ? this.originalFile.name.replace(/(\.[^.]*)$/, '') : '';
            if (typeof FileReader === "function") {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.imgSrc = event.target.result;
                    // rebuild cropperjs with the updated source
                    this.$refs.cropper.replace(event.target.result);
                    setTimeout(() => {
                        this.getData();
                        this.getCropBoxData();
                    }, 600);
                };
                reader.readAsDataURL(file);
            } else {
                alert("Sorry, FileReader API not supported");
            }
        },
        showFileChooser() {
            this.$refs.input.click();
        },
        zoom(percent) {
            this.$refs.cropper.relativeZoom(percent);
        },
        findVersionData(version){
            var return_value = null;
            this.prevalue.subfiles.forEach(function(image){
                if (image.version == version) {
                    return_value = image;
                }
            });
            this.prevalue.siblings.forEach(function(image){
                if (image.version == version) {
                    return_value = image;
                }
            });
            return return_value;
        },
        deleteFile(file, index) {
            file.parent = file.parent_id;
            this.$parent.$parent.deleteFile(file);
        },
        updateUploadedVersions() {
            this.uploadedVersions = this.parrentImage.subfiles;
        },
        size(size){
            if (size < 1000000) {
                return parseFloat(size * 0.001).toFixed(1) + " kB";
            } else {
                return parseFloat(size * 0.000001).toFixed(1) + " MB";
            }
        }
    },
    beforeDestroy() {
        this.imgSrc = null;
        this.$parent.$parent.selectedFile = null;
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.dz-message {
    margin: 0px auto;
    display: flex;
    min-height: 300px;
    flex-direction: column;
    justify-content: center;
    align-content: center;
    align-items: center;
}

input[type="file"] {
    display: none;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0 5px 0;
}

.header h2 {
    margin: 0;
}

.header a {
    text-decoration: none;
    color: black;
}

.content {
    display: flex;
    justify-content: space-between;
}

.cropper-area {
    width: 614px;
}

.actions {
    margin-top: 1rem;
}

.actions a {
    display: inline-block;
    padding: 5px 15px;
    background: #6658dd;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    margin-right: 1rem;
    margin-bottom: 1rem;
}

textarea {
    width: 100%;
    height: 100px;
}

.preview-area {
    width: 307px;
}

.preview-area p {
    font-size: 1.25rem;
    margin: 0;
    margin-bottom: 1rem;
}

.preview-area p:last-of-type {
    margin-top: 1rem;
}

.preview {
    width: 100%;
    height: calc(372px * (9 / 16));
    overflow: hidden;
}

.crop-placeholder {
    width: 100%;
    height: 200px;
    background: #ccc;
}

.cropped-image img {
    max-width: 100%;
}
</style>
