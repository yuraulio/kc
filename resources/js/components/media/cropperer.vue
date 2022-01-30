<template>
  <div>



    <div class="content">
      <section class="cropper-area">
        <div v-if="imgSrc" class="img-cropper">
          <vue-cropper
            ref="cropper"
            :aspect-ratio="16 / 9"
            :src="imgSrc"
            preview=".preview"
          />
        </div>
        <label v-else :name="'image'" style="width: 100%">
            <form method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                <div class="dz-message needsclick" style="margin: 1rem 0;">
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
        <div class="actions">
          <a
            href="#"
            role="button"
            @click.prevent="zoom(0.2)"
          >
            Zoom In
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="zoom(-0.2)"
          >
            Zoom Out
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="move(-10, 0)"
          >
            Move Left
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="move(10, 0)"
          >
            Move Right
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="move(0, -10)"
          >
            Move Up
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="move(0, 10)"
          >
            Move Down
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="rotate(90)"
          >
            Rotate +90deg
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="rotate(-90)"
          >
            Rotate -90deg
          </a>
          <a
            ref="flipX"
            href="#"
            role="button"
            @click.prevent="flipX"
          >
            Flip X
          </a>
          <a
            ref="flipY"
            href="#"
            role="button"
            @click.prevent="flipY"
          >
            Flip Y
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="cropImage"
          >
            Crop
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="reset"
          >
            Reset
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="getData"
          >
            Get Data
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="setData"
          >
            Set Data
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="getCropBoxData"
          >
            Get CropBox Data
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="setCropBoxData"
          >
            Set CropBox Data
          </a>
          <a
            href="#"
            role="button"
            @click.prevent="showFileChooser"
          >
            Upload Image
          </a>
        </div>

        <textarea v-model="data" />
      </section>
      <section class="preview-area">
        <p>Preview</p>
        <div class="preview" />
        <p>Cropped Image</p>
        <div class="cropped-image">
          <img
            v-if="cropImg"
            :src="cropImg"
            alt="Cropped Image"
          />
          <div v-else class="crop-placeholder" />
        </div>

        <div class="mb-3 ">
            <label class="form-label">Name</label>
            <input v-model="imgname" type="text" class="form-control">
        </div>

        <button @click="upload" class="btn btn-primary btn-block mt-1">Upload</button>
      </section>
    </div>
  </div>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import uploadImage from '../inputs/upload-image.vue';
import Button from '../../../assets/vendor/MediaManager/js/components/globalSearch/button.vue';

export default {
    props: {
        prevalue: {}
    },
  components: {
    VueCropper,
    uploadImage,
    Button
  },
  data() {
    return {
      imgSrc: null,
      cropImg: '',
      data: null,
      imgname: ''
    };
  },
  mounted() {
      if (this.prevalue) {
          this.imgSrc = this.prevalue;
      }
  },
  methods: {
      upload() {
          this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
            this.$emit('upload', blob);
          });
      },
      imageAdded($event) {
          this.imgSrc = $event.url
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
      let scale = dom.getAttribute('data-scale');
      scale = scale ? -scale : -1;
      this.$refs.cropper.scaleX(scale);
      dom.setAttribute('data-scale', scale);
    },
    flipY() {
      const dom = this.$refs.flipY;
      let scale = dom.getAttribute('data-scale');
      scale = scale ? -scale : -1;
      this.$refs.cropper.scaleY(scale);
      dom.setAttribute('data-scale', scale);
    },
    getCropBoxData() {
      this.data = JSON.stringify(this.$refs.cropper.getCropBoxData(), null, 4);
    },
    getData() {
      this.data = JSON.stringify(this.$refs.cropper.getData(), null, 4);
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
      if (!this.data) return;
      this.$refs.cropper.setCropBoxData(JSON.parse(this.data));
    },
    setData() {
      if (!this.data) return;
      this.$refs.cropper.setData(JSON.parse(this.data));
    },
    setImage(e) {
      const file = e.target.files[0];
      if (file.type.indexOf('image/') === -1) {
        alert('Please select an image file');
        return;
      }
      if (typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.imgSrc = event.target.result;
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert('Sorry, FileReader API not supported');
      }
    },
    showFileChooser() {
      this.$refs.input.click();
    },
    zoom(percent) {
      this.$refs.cropper.relativeZoom(percent);
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
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
