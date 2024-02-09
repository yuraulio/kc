<template>
  <div class="example-avatar" style="max-height: 400px; width: 100%; overflow: hidden">
    <div v-show="$refs[keyput] && $refs[keyput].dropActive" class="drop-active">
      <h3>Drop files to upload</h3>
    </div>
    <div class="avatar-upload" v-show="!edit">
      <div class="text-center">
        <label :name="'avatar' + keyput" style="width: 100%">
          <form
            method="post"
            class="dropzone dz-clickable"
            id="myAwesomeDropzone"
            data-plugin="dropzone"
            data-previews-container="#file-previews"
            data-upload-preview-template="#uploadPreviewTemplate"
          >
            <div class="dz-message needsclick" style="margin: 1rem 0">
              <i class="h1 text-muted dripicons-cloud-upload"></i>
              <div class="text-center">
                <file-upload
                  extensions="gif,jpg,jpeg,png,webp"
                  accept="image/png,image/gif,image/jpeg,image/webp"
                  :name="'avatar' + keyput"
                  :id="'avatar' + keyput"
                  post-action="/upload/post"
                  :drop="!edit"
                  v-model="files[keyput]"
                  @input-filter="inputFilter"
                  @input-file="inputFile"
                  :ref="keyput"
                >
                </file-upload>
                <span class="font-16">Drop files here or click to upload</span>
              </div>
            </div>
          </form>
        </label>
      </div>
    </div>

    <div class="avatar-edit" v-if="(files[keyput] != null && files[keyput].length && edit) || value">
      <div class="avatar-edit-image" v-if="(files[keyput].length && files[keyput][0]) || value">
        <img style="min-height: 200px" ref="editImage" :src="value ? value : files[keyput][0].url" />
      </div>
      <div class="text-center p-4" style="margin-top: -100px">
        <button
          type="button"
          @click.prevent="
            $set(files, keyput, []);
            $refs[keyput].clear;
            value = null;
          "
          class="btn btn-danger waves-effect waves-light float-right"
        >
          <i class="mdi mdi-close"></i> Cancel
        </button>
        <!--<button type="submit" class="btn btn-primary" @click.prevent="editSave">Save</button>-->
      </div>
    </div>
  </div>
</template>

<script>
import FileUpload from 'vue-upload-component';
export default {
  props: {
    keyput: {},
    prevalue: {},
    direct: {
      default: true,
    },
  },
  components: {
    FileUpload,
  },

  data() {
    return {
      files: [],
      edit: false,
      value: null,
    };
  },

  mounted() {
    if (this.prevalue) {
      this.edit = true;
      this.$set(this.files, this.keyput, [
        {
          url: this.prevalue,
        },
      ]);
    }

    //this.value = this.prevalue;
  },

  methods: {
    inputFile(newFile, oldFile, prevent) {
      if (newFile) {
        var formData = new FormData();
        var imagefile = newFile;
        console.log('imgfile', imagefile);
        if (imagefile.file && this.direct) {
          formData.append('file', imagefile.file);
          axios
            .post('/api/pages/upload_image', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            })
            .then((response) => {
              this.$set(this.files, this.keyput, [newFile]);
              this.edit = true;
              this.$forceUpdate();
              this.$emit('updatedimage', response.data['url']);
            })
            .catch((error) => {});
        } else {
          this.$set(this.files, this.keyput, [newFile]);
          this.edit = true;
          this.$emit('inputed', newFile);
        }
      } else {
        this.edit = false;
      }
    },

    inputFilter(newFile, oldFile, prevent) {
      if (newFile && !oldFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          this.alert('Your choice is not a picture');
          return prevent();
        }
      }

      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.url = '';
        let URL = window.URL || window.webkitURL;
        if (URL && URL.createObjectURL) {
          newFile.url = URL.createObjectURL(newFile.file);
        }
      }
    },
  },
};
</script>

<style>
.example-avatar .avatar-upload .rounded-circle {
  width: 200px;
  height: 200px;
}

.example-avatar .text-center .btn {
  margin: 0 0.5rem;
}

.example-avatar .avatar-edit-image {
  align-content: center;
  max-width: 100%;
  max-height: 400px;
  object-fit: scale-down;
}

.example-avatar .avatar-edit-image img {
  width: 100%;
  /* or any custom size */
  height: 100%;
  object-fit: contain;
}

.example-avatar .drop-active {
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  position: fixed;
  z-index: 9999;
  opacity: 0.6;
  text-align: center;
  background: #000;
}

.example-avatar .drop-active h3 {
  margin: -0.5em 0 0;
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  font-size: 40px;
  color: #fff;
  padding: 0;
}
</style>
