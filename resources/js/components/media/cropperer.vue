<template>
  <div class="row">
    <div class="col-lg-8">
      <div v-show="imgSrc" :key="imgSrc ? imgSrc.url : 'emp'" class="img-cropper">
        <vue-cropper ref="cropper" :src="imgSrc" preview=".preview" />
      </div>
      <label v-show="imgSrc == null" :name="'image'" style="width: 100%; min-height: 300px">
        <form
          method="post"
          class="dropzone dz-clickable"
          id="myAwesomeDropzone"
          data-plugin="dropzone"
          data-previews-container="#file-previews"
          data-upload-preview-template="#uploadPreviewTemplate"
        >
          <div class="dz-message needsclick" style="margin: 0 auto; min-height: 300px">
            <i class="h1 text-muted dripicons-cloud-upload"></i>
            <div class="text-center">
              <input
                ref="input"
                type="file"
                name="image"
                accept="image/*"
                @change="setImage"
              />
              <span class="font-16">Drop files here or click to upload</span>
            </div>
          </div>
        </form>
      </label>
      <!-- <div v-else>
            <div class="card">
                <upload-image
                    :keyput="'mediaManagera'"
                    :direct="false"
                    :prevalue="imgSrc ?? null"
                    @inputed="imageAdded"
                >
                </upload-image>
            </div>
        </div> -->
      <div class="btn-group mb-2 mt-2">
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="zoom(0.2)"
        >
          <i class="mdi mdi-magnify-plus-outline"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="zoom(-0.2)"
        >
          <i class="mdi mdi-magnify-minus-outline"></i>
        </button>
      </div>
      <div class="btn-group mb-2 mt-2">
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="move(-10, 0)"
        >
          <i class="mdi mdi-arrow-left"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="move(10, 0)"
        >
          <i class="mdi mdi-arrow-right"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="move(0, 10)"
        >
          <i class="mdi mdi-arrow-up"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="move(0, -10)"
        >
          <i class="mdi mdi-arrow-down"></i>
        </button>
      </div>

      <div class="btn-group mb-2 mt-2">
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="rotate(90)"
        >
          <i class="mdi mdi-rotate-right"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="rotate(-90)"
        >
          <i class="mdi mdi-rotate-left"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="flipX"
        >
          <i class="mdi mdi-flip-horizontal"></i>
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="flipY"
        >
          <i class="mdi mdi-flip-vertical"></i>
        </button>
      </div>

      <div class="btn-group mb-2 mt-2" style="float: right">
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="reset"
        >
          Reset
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="cropImage"
        >
          Crop
        </button>
        <button
          type="button"
          class="btn btn-soft-primary"
          @click.prevent="imgSrc = null; showFileChooser"
        >
          Reupload
        </button>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <h4 class="header-title mt-lg-0 my-3">Image Data</h4>

          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="imgData.x"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid1"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid1">X offset</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="imgData.y"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid2"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid2">Y offset</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="imgData.width"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid3"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid3">Width</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="imgData.height"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid4"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid4">Height</label>
              </div>
            </div>
          </div>

          <div class="btn-group mb-2 mt-2">
            <button
              type="button"
              class="btn btn-soft-primary"
              @click.prevent="getData"
            >
              Get Data
            </button>
            <button
              type="button"
              class="btn btn-soft-primary"
              @click.prevent="setData"
            >
              Set Data
            </button>
          </div>
        </div>

        <div class="col-lg-6">
          <h4 class="header-title mt-lg-0 my-3">CropBox Data</h4>

          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="cropBoxData.left"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid11"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid11">Left</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="cropBoxData.top"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid21"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid21">Top</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="cropBoxData.width"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid31"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid31">Width</label>
              </div>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input
                  v-model="cropBoxData.height"
                  type="number"
                  class="form-control"
                  id="floatingInputGrid41"
                  placeholder=""
                  value=""
                />
                <label for="floatingInputGrid41">Height</label>
              </div>
            </div>
          </div>

          <div class="btn-group mb-2 mt-2">
            <button
              type="button"
              class="btn btn-soft-primary"
              @click.prevent="getCropBoxData"
            >
              Get Data
            </button>
            <button
              type="button"
              class="btn btn-soft-primary"
              @click.prevent="setCropBoxData"
            >
              Set Data
            </button>
          </div>
        </div>
      </div>

    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body bg-light" style="max-height: 95vh; overflow: hidden; overflow-y: scroll;">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                            Uploading
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            Versions
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="home1">
                        <div class="col-lg-12 d-grid">
                            <p>Preview</p>
                            <div class="cropped-image preview" />
                            <p>Cropped Image</p>
                            <div class="cropped-image">
                            <img v-if="cropImg" :src="cropImg" alt="Cropped Image" />
                            <div v-else class="crop-placeholder" />
                            </div>

                            <div class="mt-3 mb-3">
                            <label class="form-label">Name</label>
                            <small>(Keep filename same for resizes to be child images)</small>
                            <input v-model="imgname" type="text" class="form-control" />
                            </div>

                            <div class="mb-3">
                            <label class="form-label">Alt Text</label>
                            <input v-model="alttext" type="text" class="form-control" />
                            </div>

                            <button @click="upload" class="btn btn-soft-success btn-block mt-1" :disabled="isUploading">
                            <span v-if="isUploading"><i class="fas fa-spinner fa-spin"></i>  Uploading...</span>
                            <span v-else>Upload</span>
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile1">
                        <div v-for="im in uploadedVersions" class="col-sm-12">
                            <img :src="im.url" alt="image" class="img-fluid rounded">
                            <p class="mb-0">
                                <code>{{im.name}}</code>
                            </p>
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
import Button from "../../../assets/vendor/MediaManager/js/components/globalSearch/button.vue";

export default {
  props: {
    prevalue: {},
  },
  components: {
    VueCropper,
    uploadImage,
    Button,
  },
  data() {
    return {
        isUploading: false,
        originalFile: null,
        uploadedVersions: [],
      imgSrc: null,
      cropImg: "",
      data: null,
      imgname: "",
      alttext: "",
      cropBoxData: {},
      imgData: {},
    };
  },
  mounted() {
    if (this.prevalue) {
        console.log(this.prevalue);
        //this.originalFile = this.prevalue;
      this.imgSrc = this.prevalue.url;
      this.uploadedVersions = this.prevalue.subfiles;
      this.$refs.cropper.replace(this.prevalue.url);
      this.$forceUpdate();
    }
  },
  methods: {
    upload() {
      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        this.$emit("upload", blob);
      });
    },
    imageAdded($event) {
      this.imgSrc = $event.url;
    },
    cropImage() {
      // get image data for post processing, e.g. upload or setting image src
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();

      /*this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData();

        formData.append('croppedImage', blob, 'example.png' );

        // Use `jQuery.ajax` method for example
        $.ajax('/path/to/upload', {
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success() {
            console.log('Upload success');
            },
            error() {
            console.log('Upload error');
            },
        });
        }*/
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
      if (typeof FileReader === "function") {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.imgSrc = event.target.result;
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
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
  },
  beforeDestroy() {
      this.imgSrc = null;
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
