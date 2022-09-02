<template>
<div class="cropper-outer p-2">
    <div class="row cropper-data">
        <div class="col-sm-8">
            <div>
                <div v-show="imgSrc" :key="imgSrc ? imgSrc : 'emp'" class="img-cropper" style>
                    <vue-cropper 
                    ref="cropper" 
                    :checkCrossOrigin="false" 
                    :src="imgSrc" 
                    preview=".preview" 
                    @zoom.prevent 
                    :img-style="{'max-height': '680px'}"/>
                </div>
                <!--
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
                -->
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row mb-2">
                <div class="col-lg-12 d-grid">
                    <input v-model="imgname" type="text" class="form-control cropper-image-name invisible-input ps-0" placeholder="Enter file name"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Alt text:</label>
                </div>
                <div class="col">
                    <input v-model="alttext" type="text" class="form-control invisible-input text-end" placeholder="Enter alt text"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Link:</label>
                </div>
                <div class="col">
                    <input v-model="link" type="text" class="form-control invisible-input text-end" placeholder="Enter link"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Version:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ version }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Size:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ formatSize(size) }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Date:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ date }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">User:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ user ? (user.firstname + ' ' + user.lastname) : '' }}</span>
                </div>
            </div>
            <div v-if="extension == 'png'" class="row mb-2">
                <div class="col-lg-12">
                    <input v-model="jpg" type="checkbox" id="jpg" class="form-check-input me-1" style="position: relative;">
                    <label for="jpg" class="form-label">Convert image versions to jpg format. (Reduces size.)</label>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-12">
                    <button @click="upload('edit')" class="btn btn-soft-success btn-block w-100 mt-2" :disabled="isUploading">
                        <span v-if="isUploading"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
                        <span v-else>
                            Save
                        </span>
                    </button>
                </div>
                <div class="col-4">
                    <a :href="getUrl()" target="_blank" class="btn btn-soft-warning w-100 mt-2">View</a>
                </div>
                <div class="col-4">
                    <button @click="resetData(); reset();" class="btn btn-soft-info btn-block w-100 mt-2">
                        Reset
                    </button>
                </div>
                <div class="col-4">
                    <button @click="close()" class="btn btn-soft-secondary btn-block w-100 mt-2">
                        Cancel
                    </button>
                </div>
                <div class="col-12">
                    <button v-if="findVersionData(version)" @click="deleteFile(findVersionData(version))" class="btn btn-soft-danger btn-block w-100 mt-2">Delete</button>
                    <button v-if="parentMode" @click="confirmSelection(findVersionData(version))" class="btn btn-soft-primary btn-block w-100 mt-2">Use</button>
                </div>
            </div>
        </div>
    </div>
    <div id="versions" class="row horizontal-scroll">
        <div class="col-12">
            <div class="p-1">
                <img @click="disable(); version='original'; selectedVersion=null; imgname=parrentImage.name; alttext=parrentImage.alt_text; link=parrentImage.link; id=parrentImage.id; versionData=null; versionSelected();" crossorigin="anonymous" :src="parrentImage ? ('/uploads/' + parrentImage.path) : ''" alt="image" class="img-fluid rounded" :style="version == 'original' ? 'border: 4px solid #1abc9c;' : 'border: 4px solid #f3f7f9;'"/>
                <h5>Original image</h5>
                <!--
                <button v-if="parentMode" @click="confirmSelection(parrentImage)" style="width: 100%" class="btn btn-soft-primary mt-2">Select image</button>
                -->            
            </div>

            <template v-for="(version1, index) in versions" v-if="matchVersions(version1.version)">
                <div class="p-1">
                    <template v-if="findVersionData(version1.version) != null">
                        <img @click="version=version1.version; selectedVersion=version1; versionSelected();" crossorigin="anonymous" :src="'/uploads/' + findVersionData(version1.version).path + '?key=' + imageKey" alt="image" class="img-fluid rounded" :style="version == version1.version ? 'border: 4px solid #1abc9c;' : 'border: 4px solid #f3f7f9;'" />
                        <!--
                        <button v-if="parentMode" @click="confirmSelection(findVersionData(version1.version))" style="width: 100%" class="btn btn-soft-primary mt-2">Select image</button>
                        -->
                    </template>
                    <template v-else>
                        <button @click="version=version1.version; selectedVersion=version1; versionSelected();" style="width: 100%" class="btn btn-soft-primary ms-1 me-1">Set image</button>
                    </template>
                    <h5 :id="version1.version" class="">
                        {{ version1.version }}
                        <!--
                        <i v-if="findVersionData(version1.version)" @click="deleteFile(findVersionData(version1.version), index)" class="mdi mdi-delete text-muted vertical-middle cursor-pointer"></i>
                        -->
                    </h5>
                    <p class="text-muted d-block mb-2">{{ version1.description }}</p>
                </div>
            </template>  
        </div>
    </div>
</div>
</template>

<script>
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
import uploadImage from "../inputs/upload-image.vue";
import VueScrollTo from "vue-scrollto";

export default {
    props: {
        prevalue: {},
        imageKey: "",
        warning: false,
        imageVersion: null,
    },
    components: {
        VueCropper,
        uploadImage,
        VueScrollTo,
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
            id: null,
            jpg: false,
            cropBoxData: {},
            imgData: {},
            compression: 100,
            parrentImage: null,
            version: "original",
            width_ratio: null,
            height_ratio: null,
            versionData: null,
            parentMode: this.$parent.$parent.mode != null ? true : false,
            date: null,
            size: null,
            extension: null,
            user: {},
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
            this.setupPrevalue();

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
                        this.disable();
                    }, 600);
                };

                this.$forceUpdate();
            }

            setTimeout(() => {
                this.version = this.prevalue.version;
                this.selectedVersion = this.findVersion(this.version);
                this.versionSelected();
                // var element = document.getElementById(this.version);
                // VueScrollTo.scrollTo(element, 500, {
                //     container: "#versions",
                // });

                if (this.version != 'original' || this.version != 'Original' || this.version == null) {
                    this.disable();
                }
                
            }, 1000);
        }
    },
    methods: {
        setupPrevalue() {
            if (this.prevalue.parrent) {
                this.parrentImage = this.prevalue.parrent;
                // this.version = this.prevalue.version;
            } else {
                this.parrentImage = this.prevalue;
                // this.version = 'original';
            }

            this.version = 'original';

            this.imgSrc = '/uploads' + this.parrentImage.path;
            this.uploadedVersions = this.parrentImage.subfiles;
            this.originalFile = this.prevalue;

            this.imgname = this.parrentImage ? this.parrentImage.name : '';
            this.alttext = this.parrentImage.alt_text ? this.parrentImage.alt_text : '';
            this.link = this.parrentImage.link ? this.parrentImage.link : '';
            this.id = this.parrentImage.id ? this.parrentImage.id : null;
            this.date = this.versionData ? this.versionData.created_at : this.parrentImage.created_at;
            this.size = this.versionData ? this.versionData.size : this.parrentImage.size;
            this.user = this.versionData ? this.versionData.user : this.parrentImage.user;
            this.extension = this.versionData ? this.versionData.extension : this.parrentImage.extension;
        },
        confirmSelection(image) {
            if (image == null) {
                image = this.parrentImage;
            }
            if (this.$parent.$parent.mode != null ) {
                this.$parent.$parent.updatedMediaImage(image);
                // this.$modal.hide('gallery-modal');
                this.$toast.success('New image selected!');
            }
        },
        disable() {
            this.$refs.cropper.disable();
        },
        getUrl() {
            if (this.versionData) {
                return this.versionData.url + '?key=' + this.imageKey;
            }
            if (this.parrentImage) {
                return this.parrentImage.url + '?key=' + this.imageKey;
            }
            return null;
        },
        versionSelected() {
            if (this.selectedVersion) {
                this.$refs.cropper.enable();
                var image_width, image_height;

                var img = new Image();
                img.onload = () => {
                    image_width = img.width;
                    image_height = img.height;
                    this.setCropBox(image_width, image_height);
                }
                img.src = this.parrentImage.url;

                this.versionData = this.findVersionData(this.selectedVersion.version);
                this.imgname = this.versionData ? this.versionData.name : "";
                this.alttext = this.versionData ? this.versionData.alt_text : "";
                this.link = this.versionData ? this.versionData.link : "";
                this.id = this.versionData ? this.versionData.id : null;
                this.date = this.versionData ? this.versionData.created_at : '';
                this.size = this.versionData ? this.versionData.size : '';
                this.user = this.versionData ? this.versionData.user : '';
                this.extension = this.versionData ? this.versionData.extension : '';
            } else {
                this.date = this.parrentImage.created_at ? this.parrentImage.created_at : '';
                this.size = this.parrentImage.size ? this.parrentImage.size : '';
                this.user = this.parrentImage.user ? this.parrentImage.user : '';
                this.extension = this.parrentImage.extension ? this.parrentImage.extension : '';
            }

            if (this.imgname == "") {
                var tmp = this.parrentImage.name.split(".");
                var extension = tmp[tmp.length - 1];
                this.imgname = tmp[0] + "-" + this.version + "." + extension;
            }
        },
        resetData() {
            this.imgname = this.versionData ? this.versionData.name : this.parrentImage.name;
            this.alttext = this.versionData ? this.versionData.alt_text : this.parrentImage.alt_text;
            this.link = this.versionData ? this.versionData.link : this.parrentImage.link;
            this.jpg = false;
        },
        close() {
            this.$modal.hide('edit-image-modal');
        },
        setCropBox(image_width, image_height) {
            var canvas_height = this.$refs.cropper.getCanvasData().height;
            var canvas_width = this.$refs.cropper.getCanvasData().width;

            var container_height = this.$refs.cropper.getContainerData().height;
            var container_width = this.$refs.cropper.getContainerData().width;

            this.width_ratio = canvas_width / image_width;
            this.height_ratio = canvas_height / image_height;

            this.$refs.cropper.setAspectRatio(this.selectedVersion.w / this.selectedVersion.h);

            this.$set(this.cropBoxData, 'width', this.selectedVersion.w * this.width_ratio);
            this.$set(this.cropBoxData, 'height', this.selectedVersion.h * this.height_ratio);
            this.$set(this.cropBoxData, 'left', ((container_width - (this.selectedVersion.w * this.width_ratio))/2));
            this.$set(this.cropBoxData, 'top', ((container_height - (this.selectedVersion.h * this.height_ratio))/2));
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
            this.cropBoxData = JSON.parse(
                JSON.stringify(this.$refs.cropper.getCropBoxData())
            );

            this.cropBoxData.left = (this.$refs.cropper.getCropBoxData().left - this.$refs.cropper.getCanvasData().left);
            this.cropBoxData.top = (this.$refs.cropper.getCropBoxData().top - this.$refs.cropper.getCanvasData().top);
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
        findVersion(version1) {
            var return_value = null;
            this.versions.forEach(function(version2){
                if (version2.version == version1) {
                    return_value = version2;
                }
            });
            return return_value;
        },
        findVersionData(version){
            var return_value = null;
            if (this.prevalue.subfiles) {
                this.prevalue.subfiles.forEach(function(image){
                    if (image.version == version) {
                        return_value = image;
                    }
                });
            }
            if(this.prevalue.siblings) {
                this.prevalue.siblings.forEach(function(image){
                    if (image.version == version) {
                        return_value = image;
                    }
                });
            }
            return return_value;
        },
        deleteFile(file, index) {
            file.parent = file.parent_id;
            this.$parent.$parent.deleteFile(file);
        },
        updateUploadedVersions() {
            this.uploadedVersions = this.parrentImage.subfiles;
        },
        formatSize(size){
            if (!size) {
                return '';
            }
            if (size < 1000000) {
                return parseFloat(size * 0.001).toFixed(1) + " kB";
            } else {
                return parseFloat(size * 0.000001).toFixed(1) + " MB";
            }
        },
        matchVersions(version) {
            if (this.imageVersion) {
                if (version == this.imageVersion) {
                    return true;
                }
                return false;
            }
            return true;
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

.btn-soft-success {
    color: #1abc9c!important;
    background-color: rgba(26, 188, 156, 0.18)!important;
    border-color: rgba(26, 188, 156, 0.12)!important;
}
.btn-soft-success:hover {
    color: #fff!important;
    background-color: #1abc9c!important;
}
.btn-soft-primary {
    color: #6658dd!important;
    background-color: rgba(102, 88, 221, 0.18)!important;
    border-color: rgba(102, 88, 221, 0.12)!important;
}
.btn-soft-primary:hover {
    color: #fff!important;
    background-color: #6658dd!important;
}

.horizontal-scroll>.col-12>div {
    height: 210px;
    width: 200px;
    flex-shrink: 0;
    text-align: center;
}
.horizontal-scroll>.col-12 {
    flex-direction: row;
    display: flex;
    overflow-x: scroll;
}
.horizontal-scroll img {
    height: 100px;
}
.cropper-outer {
    height: 100%;
    display: flex;
    flex-direction: column;
}
.cropper-data {
    flex: 1;
}
.cropper-data>.col-8 {
    text-align: center;
}
.image-preview {
    max-height: 650px;
    width: 100%;
    object-fit: contain;
}

.invisible-input {
    background: transparent;
    outline: none;
    border: 0px;
    box-shadow: none;
}
.cropper-image-name {
    font-size: 23px;
    width: 100%;
}
.invisible {
    height: 0px;
}
</style>