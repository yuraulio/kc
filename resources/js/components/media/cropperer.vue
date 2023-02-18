<template>
<div class="cropper-outer p-2">
    <div class="row cropper-data">
        <div class="col-sm-8">
            <div :class="version == 'original' ? 'no-cropper' : ''">
                <div v-show="imgSrc" :key="imgSrc ? imgSrc : 'emp'" class="img-cropper" style>
                    <vue-cropper
                    ref="cropper"
                    :checkCrossOrigin="false"
                    :src="imgSrc"
                    preview=".preview"
                    @zoom.prevent
                    @cropend="onMoveCropBox"
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
        <div class="col-sm-4 image-data">
            <div class="row mb-2">
                <div class="col-lg-12 d-grid">
                    <span class="cropper-image-name">{{ imgname }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image title:</label>
                </div>
                <div class="col">
                    <input v-model="imgname" type="text" class="form-control invisible-input text-end" placeholder="Enter file name"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image alternative text:</label>
                </div>
                <div class="col">
                    <input v-model="alttext" v-on:change="changeHandler" type="text" name="alttext" class="form-control invisible-input text-end" placeholder="Enter alt text"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image link:</label>
                </div>
                <div class="col">
                    <input v-model="link" v-on:change="changeHandler" type="text" class="form-control invisible-input text-end" name="link" placeholder="Enter link"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image version name:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ version }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image size:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ formatSize(size) }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Image dimensions:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ width ? width + " x " + height : '' }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Date uploaded:</label>
                </div>
                <div class="col">
                    <span class="form-control invisible-input text-end">{{ date }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <label class="form-label">Uploaded from:</label>
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
                    <button @click="reset();" class="btn btn-soft-info btn-block w-100 mt-2">
                        Reset
                    </button>
                </div>
                <div class="col-4">
                    <button @click="close()" class="btn btn-soft-secondary btn-block w-100 mt-2">
                        Cancel
                    </button>
                </div>
                <div class="col-12">
                    <button v-if="findVersionData(version)" @click="deleteFile(findVersionData(version))" class="btn btn-soft-danger btn-block w-100 mt-2">Delete version</button>
                </div>
                <!-- <div class="col-12">
                    <button v-if="parentMode" @click="confirmSelection(findVersionData(version))" class="btn btn-soft-primary btn-block w-100 mt-2">Use</button>
                </div> -->
            </div>
        </div>
    </div>
    <div id="versions" class="row horizontal-scroll">
        <div class="col-12">
            <div class="p-1">
                <img @click="disable(); version='original'; selectedVersion=null; imgname=parrentImage.name; alttext=parrentImage.alt_text; link=parrentImage.link; id=parrentImage.id; versionData=null; versionSelected();" crossorigin="anonymous" :src="parrentImage ? ('/uploads/' + parrentImage.path) : ''" alt="image" class="img-fluid rounded" :style="version == 'original' ? 'border: 4px solid #1abc9c;' : 'border: 4px solid #f3f7f9;'"/>
                <h5>Original image</h5>
            </div>
            <!-- <template v-for="(version1, index) in versions" v-if="matchVersions(version1.version)"> -->
            <template v-for="(version1, index) in versions">
                <div class="p-1">
                    <template v-if="findVersionData(version1.version) != null">
                        <img @click="version=version1.version; selectedVersion=version1; versionSelected();" crossorigin="anonymous" :src="'/uploads/' + findVersionData(version1.version).path + '?key=' + imageKey" alt="image" class="img-fluid rounded" :style="version == version1.version ? 'border: 4px solid #1abc9c;' : 'border: 4px solid #f3f7f9;'" />
                        <!--
                        <button v-if="parentMode" @click="confirmSelection(findVersionData(version1.version))" style="width: 100%" class="btn btn-soft-primary mt-2">Select image</button>
                        -->
                    </template>
                    <template v-else>
                        <button @click="version=version1.version; selectedVersion=version1; versionSelected();" style="width: 100%" class="btn btn-soft-primary ms-1 me-1">Select version</button>
                    </template>
                    <h5 :id="version1.version" class="">
                        {{ version1.title }}
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
        imageVersion: null
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
            uploadedVersionsSiblings: [],
            versionsForUpdate: {},
            tempVersionsForUpdate: {},
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
            height: null,
            width: null,
            extension: null,
            user: {},
            versions: [{
                    w: 470,
                    h: 470,
                    q: 60,
                    fit: "crop",
                    version: "instructors-testimonials",
                    title: "Portrait image",
                    description: "The half body image of instructors.",
                },
                {
                    w: 542,
                    h: 291,
                    q: 60,
                    fit: "crop",
                    version: "event-card",
                    title: "Home page boxes",
                    description: "The image of courses on our home page.",
                },
                {
                    w: 470,
                    h: 470,
                    q: 60,
                    fit: "crop",
                    version: "users",
                    title: "Testimonial image",
                    description: "The image of a user in testimonials.",
                },
                {
                    w: 2880,
                    h: 1248,
                    q: 60,
                    fit: "crop",
                    version: "header-image",
                    title: "Top image",
                    description: "The image on top of a page.",
                },
                {
                    w: 90,
                    h: 90,
                    q: 60,
                    fit: "crop",
                    version: "instructors-small",
                    title: "Lessons image",
                    description: "The image of an instructor next to a lesson.",
                },
                {
                    w: 300,
                    h: 300,
                    q: 60,
                    fit: "crop",
                    title: "Advertising image",
                    version: "feed-image",
                    description: "The image we send to dynamic ad creatives via feed.",
                },
                {
                    w: 1920,
                    h: 832,
                    q: 60,
                    fit: "crop",
                    version: "social-media-sharing",
                    title: "Social media posts image",
                    description: "The image visible on social media posts.",
                },
                {
                    w: 680,
                    h: 320,
                    q: 60,
                    fit: "crop",
                    version: "blog-content",
                    title: "Blog top image",
                    description: "The image on top of a blog post.",
                },
                {
                    w: 343,
                    h: 193,
                    q: 60,
                    fit: "crop",
                    version: "blog-featured",
                    title: "Blog boxes",
                    description: "The image of blog article on /blog.",
                },
            ]

        };
    },
    mounted() {
        console.log('first loaded: ', this.$parent.$parent.firstLoadedData)
        console.log('selected files: ', this.$parent.$parent.selectedFile)
        console.log('Image Version: ',this.imageVersion)
        console.log('Parent mode: ', this.parentMode)
        console.log('version for update:', this.versionsForUpdate)
        if (this.prevalue) {
            this.setupPrevalue(true);


            if (typeof FileReader === "function") {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.imgSrc = event.target.result;
                    this.imgSrc.setAttribute('crossorigin', 'anonymous');


                    // rebuild cropperjs with the updated source
                    this.$refs.cropper.replace(this.imgSrc);

                    this.$nextTick(() => {
                        this.getData();
                        this.getCropBoxData();
                        this.setCropBoxData();

                    });
                };
                // console.log('i am here please')
                // console.log(this)
                this.versionsForUpdate['original'] = {
                    'imgname': this.parrentImage.name,
                    'version': 'original',
                    'parent_id': this.$refs.cropper.$parent.parrentImage.id,
                    //'crop_data': cropData,
                    // 'width_ratio': this.width_ratio,
                    // 'height_ratio': this.height_ratio,
                    'id': this.parrentImage.id,
                    'jpg': this.jpg,
                    'instance': this.$refs.cropper,
                    'alttext': this.parrentImage.alttext != 'null' ? this.parrentImage.alttext : '',
                    'link': this.parrentImage.link != 'null' ? this.parrentImage.link : '',
                    'hasDeleted': false
                }

                // console.log('from preve version original: ')
                // console.log(this.versionsForUpdate['original'])

                this.$forceUpdate();
            }

            setTimeout(() => {
                this.version = this.prevalue.version;
                //console.log('VERSION:: ', this.version)
                this.selectedVersion = this.findVersion(this.version);
                this.versionSelected();
                if (this.version == 'original' || this.version == 'Original' || this.version == null) {
                    this.disable();
                }

            }, 600);
        }
    },
    methods: {
        test(folderId, response = null){

            this.$parent.$parent.getFiles(folderId)

            // if(response != null && this.$parent.imageVersion && response.data.data.version == this.imageVersion){
            //     // this.$parent.imageVersionResponseData = response.data.data
            //     this.updatedMediaImage(response.data.data)
            //     return false;

            // }

            this.setupPrevalue()
        },
        setupPrevalue(from_function = false) {

            if(this.prevalue.parrent == null){
                this.parrentImage = this.prevalue;
            }else{
                this.parrentImage = this.prevalue.parrent;
            }

            if(this.$parent.$parent.firstLoadedData.parrent == null){
                if((this.$parent.$parent.firstLoadedData.id != this.parrentImage.id && from_function)){

                    this.confirmSelection(this.parrentImage)
                }
            }else{
                if((this.$parent.$parent.firstLoadedData.parrent.id != this.parrentImage.id && from_function)){

                    this.confirmSelection(this.parrentImage)
                }
            }


            this.version = this.imageVersion ? this.imageVersion : 'original';

            this.imgSrc = '/uploads' + this.parrentImage.path;
            this.uploadedVersions = this.parrentImage.subfiles;
            this.uploadedVersionsSiblings = this.parrentImage.siblings;
            console.log('THIS IS A UPDATED SUBFILES?????')
            console.log(this.uploadedVersions)
            this.originalFile = this.prevalue;


            if(this.version == 'original'){
                this.imgname = this.parrentImage ? this.parrentImage.name : '';
                this.alttext = this.parrentImage.alt_text ? this.parrentImage.alt_text : '';
                this.link = this.parrentImage.link ? this.parrentImage.link : '';
                this.id = this.parrentImage.id ? this.parrentImage.id : null;
                this.date = this.versionData ? this.versionData.created_at : this.parrentImage.created_at;
            }else{
                // console.log('not original')
                // console.log(this)

                this.imgname = this.originalFile ? this.originalFile.name : '';
                this.alttext = this.originalFile.alt_text ? this.originalFile.alt_text : '';
                this.link = this.originalFile.link ? this.originalFile.link : '';
                this.id = this.originalFile.id ? this.originalFile.id : null;
                this.date = this.versionData ? this.versionData.created_at : this.originalFile.created_at;
            }


            // this.size = this.versionData ? this.versionData.size : this.parrentImage.size;
            // this.height = this.versionData ? this.versionData.height : this.parrentImage.height;
            // this.width = this.versionData ? this.versionData.width : this.parrentImage.width;
            this.user = this.versionData ? this.versionData.user : this.parrentImage.user;
            this.extension = this.versionData ? this.versionData.extension : this.parrentImage.extension;

            // if(from_function){
            //     //console.log(this.prevalue)
            //     let sublings = this.prevalue.subfiles

            //     let imageSave = this.prevalue
            //     console.log('PRE VALUE')
            //     console.log(this.prevalue)
            //     sublings.forEach(value => {

            //         if(value.version == 'header-image'){
            //             imageSave = value
            //         }
            //     })

            //     this.confirmSelection(imageSave)

            //     //this.prevalue

            // }
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
            this.$set(this.cropBoxData, 'width', 0);
            this.$set(this.cropBoxData, 'height', 0);
            this.$set(this.cropBoxData, 'left', 0);
            this.$set(this.cropBoxData, 'top', 0);
            this.setCropBoxData();
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
            console.log('version selected: ')

            console.log('selected version: ',this.selectedVersion)

            console.log('versionsForUpdate: ', this.versionsForUpdate)

            if (this.selectedVersion) {
                this.$refs.cropper.enable();
                var image_width, image_height;

                //console.log('parent image',this.parrentImage)
                var img = new Image();
                img.onload = () => {
                    image_width = img.width;
                    image_height = img.height;
                    this.$nextTick(() => {

                        this.setCropBox(image_width, image_height);

                    });
                }

                img.src = this.parrentImage.url;

                this.versionData = this.findVersionData(this.selectedVersion.version);
                this.imgname = this.versionData ? this.versionData.name : "";
                this.alttext = this.versionData ? this.versionData.alt_text : "";
                this.link = this.versionData ? this.versionData.link : "";
                this.id = this.versionData ? this.versionData.id : null;
                this.date = this.versionData ? this.versionData.created_at : '';
                this.size = this.versionData ? this.versionData.size : '';
                this.height = this.versionData ? this.versionData.height : '';
                this.width = this.versionData ? this.versionData.width : '';
                this.user = this.versionData ? this.versionData.user : '';
                this.extension = this.versionData ? this.versionData.extension : '';
            } else {
                this.date = this.parrentImage.created_at ? this.parrentImage.created_at : '';
                this.size = this.parrentImage.size ? this.parrentImage.size : '';
                this.height = this.parrentImage.height ? this.parrentImage.height : '';
                this.width = this.parrentImage.width ? this.parrentImage.width : '';
                this.user = this.parrentImage.user ? this.parrentImage.user : '';
                this.extension = this.parrentImage.extension ? this.parrentImage.extension : '';
            }

            if (this.imgname == "") {
                var tmp = this.parrentImage.name.split(".");
                var extension = tmp[tmp.length - 1];
                this.imgname = tmp[0] + "-" + this.version + "." + extension;
            }

            if (this.selectedVersion) {

                this.getCropBoxData()

                if(this.versionData){
                    //console.log('here')
                    if(this.versionsForUpdate[this.selectedVersion.version] === undefined){
                        this.versionsForUpdate[this.selectedVersion.version] = {
                            'imgname': this.imgname,
                            'version': this.selectedVersion.version,
                            'parent_id': this.$refs.cropper.$parent.parrentImage.id,
                            //'crop_data': cropData,
                            // 'width_ratio': this.width_ratio,
                            // 'height_ratio': this.height_ratio,
                            'id': this.id,
                            'jpg': this.jpg,
                            'instance': this.$refs.cropper,
                            'alttext': this.alttext != 'null' ? this.alttext : '',
                            'link': this.link != 'null' ? this.link : '',
                            'hasDeleted': false
                        }
                    }else if(this.versionsForUpdate[this.selectedVersion.version]){
                        //console.log('on load from dirty data alt and link')
                        this.alttext = this.versionsForUpdate[this.selectedVersion.version].alttext
                        this.link = this.versionsForUpdate[this.selectedVersion.version].link
                    }
                }




            }else{
                if(this.versionsForUpdate['original'] === undefined){
                    this.versionsForUpdate['original'] = {
                        'imgname': this.imgname,
                        'version': 'original',
                        'parent_id': this.$refs.cropper.$parent.parrentImage.id,
                        'id': this.id,
                        'jpg': this.jpg,
                        'instance': this.$refs.cropper,
                        'alttext': this.alttext != 'null' ? this.alttext : '',
                        'link': this.link != 'null' ? this.link : '',
                        'hasDeleted': false
                    }
                }else{
                    // this.alttext = this.versionsForUpdate['original'].alttext
                    // this.link = this.versionsForUpdate['original'].link
                }

            }


        },
        changeHandler(event){
            let input = event.target.name
            if(this.selectedVersion){
                this.versionsForUpdate[this.selectedVersion.version][input] = (event.target.value !== undefined && event.target.value != null) ? event.target.value : ''
            }else{
                this.versionsForUpdate['original'][input] = (event.target.value !== undefined && event.target.value != null) ? event.target.value : ''
            }

        },
        onMoveCropBox(){
            this.getCropBoxData()

            let currVersion = this.selectedVersion.version



            let cropData = {}

            cropData['height'] = this.cropBoxData.height
            cropData['width'] = this.cropBoxData.width
            cropData['left'] = this.cropBoxData.left
            cropData['top'] = this.cropBoxData.top

            if(!this.versionsForUpdate[currVersion]){
                this.versionsForUpdate[currVersion] = {}
            }

            this.versionsForUpdate[currVersion].imgname = this.imgname
            this.versionsForUpdate[currVersion].version = currVersion
            this.versionsForUpdate[currVersion].parent_id = this.$refs.cropper.$parent.parrentImage.id
            this.versionsForUpdate[currVersion].crop_data = cropData
            this.versionsForUpdate[currVersion].width_ratio = this.width_ratio
            this.versionsForUpdate[currVersion].height_ratio = this.height_ratio
            this.versionsForUpdate[currVersion].id = this.id
            this.versionsForUpdate[currVersion].jpg = this.jpg
            this.versionsForUpdate[currVersion].instance = this.$refs.cropper
            this.versionsForUpdate[currVersion].hasDeleted = false

            console.log('from on Move Crop Box Data: ', this.versionsForUpdate)

        },
        resetData() {
            this.imgname = this.versionData ? this.versionData.name : this.parrentImage.name;
            this.alttext = this.versionData ? this.versionData.alt_text : this.parrentImage.alt_text;
            this.link = this.versionData ? this.versionData.link : this.parrentImage.link;
            this.jpg = false;

            if(this.versionsForUpdate[this.selectedVersion.version]){

                this.versionsForUpdate[this.selectedVersion.version].imgname = this.imgname
                this.versionsForUpdate[this.selectedVersion.version].alttext = this.alttext
                this.versionsForUpdate[this.selectedVersion.version].link = this.link
                this.versionsForUpdate[this.selectedVersion.version].jpg = this.jpg

                delete this.versionsForUpdate[this.selectedVersion.version].crop_data

                this.onMoveCropBox()
            }

        },
        close() {
            this.$modal.hide('edit-image-modal');
        },
        setCropBox(image_width, image_height) {
            console.log('from set crop box data')
            console.log(this.versionsForUpdate)



            var canvas_height = this.$refs.cropper.getCanvasData().height;
            var canvas_width = this.$refs.cropper.getCanvasData().width;

            var container_height = this.$refs.cropper.getContainerData().height;
            var container_width = this.$refs.cropper.getContainerData().width;

            this.width_ratio = canvas_width / image_width;
            this.height_ratio = canvas_height / image_height;

            //this.$refs.cropper.setAspectRatio(this.selectedVersion.w / this.selectedVersion.h);



            if(this.versionsForUpdate[this.selectedVersion.version] && this.versionsForUpdate[this.selectedVersion.version].crop_data !== undefined && !this.versionsForUpdate[this.selectedVersion.version].hasDeleted){

                console.log('1111')
                //console.log('selected version is ->>>>',this.selectedVersion.version)


                let data = this.versionsForUpdate[this.selectedVersion.version].crop_data;

                //this.$refs.cropper.setAspectRatio(data.width / data.height);

                let crop_height = data.height * (1 / this.height_ratio)
                let crop_width = data.width * (1 / this.width_ratio)
                let height_offset = data.top * (1 / this.height_ratio)
                let width_offset = data.left * (1 / this.width_ratio)

                this.$set(this.cropBoxData, 'width', crop_width * this.width_ratio);
                this.$set(this.cropBoxData, 'height', crop_height * this.height_ratio);
                this.$set(this.cropBoxData, 'left', (((container_width - canvas_width)/2) + (width_offset * this.width_ratio)));
                this.$set(this.cropBoxData, 'top', (((container_height - canvas_height)/2) + (height_offset * this.width_ratio)));

                this.setCropBoxData();



                return 0;
            }


            //console.log('versionData is now available: ',this.versionData)
            // if (this.versionData && this.versionData.crop_data) {

            //     console.log('versionData: ', this.versionData)
            //     console.log('uploadedVersions: ', this.uploadedVersions)

            //     if (typeof this.versionData.crop_data === "string") {
            //         this.versionData.crop_data = JSON.parse(this.versionData.crop_data);
            //     }
            //     this.$set(this.cropBoxData, 'width', this.versionData.crop_data.crop_width * this.width_ratio);
            //     this.$set(this.cropBoxData, 'height', this.versionData.crop_data.crop_height * this.height_ratio);
            //     this.$set(this.cropBoxData, 'left', (((container_width - canvas_width)/2) + (this.versionData.crop_data.width_offset * this.width_ratio)));
            //     this.$set(this.cropBoxData, 'top', (((container_height - canvas_height)/2) + (this.versionData.crop_data.height_offset * this.width_ratio)));
            // } else {
            //     this.$set(this.cropBoxData, 'width', this.selectedVersion.w * this.width_ratio);
            //     this.$set(this.cropBoxData, 'height', this.selectedVersion.h * this.height_ratio);
            //     this.$set(this.cropBoxData, 'left', ((container_width - (this.selectedVersion.w * this.width_ratio))/2));
            //     this.$set(this.cropBoxData, 'top', ((container_height - (this.selectedVersion.h * this.height_ratio))/2));
            // }
            if (this.uploadedVersions) {


                let data = this.findVersionPavlos(this.selectedVersion.version)

                console.log('DATA: ', data)
                console.log('this.selectedVersion :', this.selectedVersion)
                console.log('THIS: ', this)
                console.log('this.versionData: ',this.versionData)
                console.log('this.uploadedVersions: ', this.uploadedVersions)

                // console.log('versionData: ', this.versionData)
                // console.log('uploadedVersions: ', this.uploadedVersions)

                if(data.crop_data == null){

                    this.$set(this.cropBoxData, 'width', this.selectedVersion.w * this.width_ratio);
                    this.$set(this.cropBoxData, 'height', this.selectedVersion.h * this.height_ratio);
                    this.$set(this.cropBoxData, 'left', ((container_width - (this.selectedVersion.w * this.width_ratio))/2));
                    this.$set(this.cropBoxData, 'top', ((container_height - (this.selectedVersion.h * this.height_ratio))/2));

                    alert('has no crop box data')

                }else{

                    if (typeof data.crop_data === "string") {
                        data.crop_data = JSON.parse(data.crop_data);
                    }
                    this.$set(this.cropBoxData, 'width', data.crop_data.crop_width * this.width_ratio);
                    this.$set(this.cropBoxData, 'height', data.crop_data.crop_height * this.height_ratio);
                    this.$set(this.cropBoxData, 'left', (((container_width - canvas_width)/2) + (data.crop_data.width_offset * this.width_ratio)));
                    this.$set(this.cropBoxData, 'top', (((container_height - canvas_height)/2) + (data.crop_data.height_offset * this.width_ratio)));
                    console.log('2222',data)

                }

               
            } else {
                console.log('ELSE')
                this.$set(this.cropBoxData, 'width', this.selectedVersion.w * this.width_ratio);
                this.$set(this.cropBoxData, 'height', this.selectedVersion.h * this.height_ratio);
                this.$set(this.cropBoxData, 'left', ((container_width - (this.selectedVersion.w * this.width_ratio))/2));
                this.$set(this.cropBoxData, 'top', ((container_height - (this.selectedVersion.h * this.height_ratio))/2));
            }




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

            if(this.versionData == null && this.selectedVersion && this.versionsForUpdate[this.selectedVersion.version] === undefined){
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

            }

            // for each gia oles tiw ekdoseis

            let versions = this.versionsForUpdate

            this.tempVersionsForUpdate = this.versionsForUpdate;

           //console.log('version for update')
            //console.log(versions)

            //let folderId = versions['original'].instance.$parent.originalFile.folder_id



           Object.values(versions).forEach(value => {

                let cropper = value.instance

                // update crop data
                if(cropper && value.crop_data !== undefined){
                    //console.log('update all infos')
                    cropper.getCroppedCanvas({
                        width: value.crop_data.width,
                        height: value.crop_data.height,
                    }).toBlob(
                        (blob) => {
                            // blob.version = this.version;
                            this.$emit(event, value);
                        },
                        "image/jpeg",
                        this.compression / 100
                    );
                }else{
                    //console.log('update only alt text')
                    //update only link and alt text
                    this.$emit(event, value);
                }

            })





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
            this.resetData();
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
        findVersionPavlos(selected_version){
            console.log('functions find version Pavlos START')
            var return_value = null;

            console.log('UPLOADED VERSION SIBLINGS: ', this.uploadedVersionsSiblings)
            console.log('UPLOADED VERSION :: ', this.uploadedVersions)
            this.uploadedVersionsSiblings.forEach(function(value){
                if (value.version == selected_version) {
                    return_value = value;
                }
            })

            this.uploadedVersions.forEach(function(value){
                if (value.version == selected_version) {
                    return_value = value;
                }
            })

            console.log('end function')

            return return_value;
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

            if(this.versionsForUpdate.length != 0 && this.versionsForUpdate[this.selectedVersion.version]){
                delete this.versionsForUpdate[this.selectedVersion.version];

                if(this.versionsForUpdate[this.selectedVersion.version]){
                    this.versionsForUpdate[this.selectedVersion.version].hasDeleted = true
                }


            }
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
        console.log('destroy')
        this.imgSrc = null;
        //this.$parent.$parent.selectedFile = null;
        //this.versionsForUpdate = null
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
.btn-soft-warning {
    color: #f7b84b!important;
    background-color: rgba(247, 184, 75, 0.18)!important;
    border-color: rgba(247, 184, 75, 0.12)!important;
}
.btn-soft-warning:hover {
    color: #fff!important;
    background-color: #f7b84b!important;
}
.btn-soft-info {
    color: #4fc6e1!important;
    background-color: rgba(79, 198, 225, 0.18)!important;
    border-color: rgba(79, 198, 225, 0.12)!important;
}
.btn-soft-info:hover {
    color: #fff!important;
    background-color: #4fc6e1!important;
}
.btn-soft-secondary {
    color: #6c757d!important;
    background-color: rgba(108, 117, 125, 0.18)!important;
    border-color: rgba(108, 117, 125, 0.12)!important;
}
.btn-soft-secondary:hover {
    color: #fff!important;
    background-color: #6c757d!important;
}
.btn-soft-danger {
    color: #f1556c!important;
    background-color: rgba(241, 85, 108, 0.18)!important;
    border-color: rgba(241, 85, 108, 0.12)!important;
}
.btn-soft-danger:hover {
    color: #fff!important;
    background-color: #f1556c!important;
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
    border: 0px!important;
    box-shadow: none;
}
.cropper-image-name {
    font-size: 23px;
    width: 100%;
}
.invisible {
    height: 0px;
}

.image-data label {
    margin-bottom: 0px;
}
.image-data>.row>div {
    align-items: center;
    display: flex;
}
.image-data>.row {
    min-height: 35px;
}
</style>

<style>
.no-cropper .cropper-drag-box {
    display: none;
}
</style>
