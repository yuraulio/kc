import _ from 'lodash';
import api from '../../panel_app/api';
import { isNewAdmin } from '../routes/helpers';

var mediaMixin = {
  data() {
    return {
      tempDelete: null,
      firstLoadedData: [],
      versions: [
        {
          w: 470,
          h: 470,
          q: 60,
          fit: 'crop',
          version: 'instructors-testimonials',
          description: 'Applies to : Our Instructor Page (Footer) & Event -> Instructors',
        },
        {
          w: 542,
          h: 291,
          q: 60,
          fit: 'crop',
          version: 'event-card',
          description: 'Applies to : Homepage Events list',
        },
        {
          w: 470,
          h: 470,
          q: 60,
          fit: 'crop',
          version: 'users',
          description: 'Applies to : Testimonial square image',
        },
        {
          w: 2880,
          h: 1248,
          q: 60,
          fit: 'crop',
          version: 'header-image',
          description: 'Applies to: Event header carousel (Main event page)',
        },
        {
          w: 1080,
          h: 1080,
          q: 60,
          fit: 'crop',
          description: 'feed-image',
          version: 'feed-image',
        },
        {
          w: 1920,
          h: 832,
          q: 60,
          fit: 'crop',
          version: 'social-media-sharing',
          description: 'Applies to: Social media sharing default image',
        },
      ],
    };
  },
  methods: {
    updatedMediaImage(img) {
      this.$emit('updatedimg', img);
    },
    openFile(file, ref) {
      this.opImage = file;
      this.$modal.show('gallery-modal', file);
    },
    collapse(item) {
      item.children.forEach((el) => {
        this.uncolapsed.push(el.id);
      });
    },
    uncollapse(item) {
      item.children.forEach((el) => {
        this.uncollapse(el);
        this.uncolapsed.splice(this.uncolapsed.indexOf(el.id), 1);
      });
    },
    selectedFolders($event) {
      this.filesView = false;
      this.selectedFolder = $event;
    },
    uploadImgFile() {
      var formData = new FormData();
      var imagefile = this.regFile;

      if (imagefile && this.move_file_to) {
        this.loading = true;
        formData.append('file', imagefile);
        formData.append('alt_text', this.alt_text);
        formData.append('link', this.link);
        formData.append('jpg', this.jpg);
        formData.append('folder_id', this.move_file_to.id);
        api.post(
          'media.upload',
          formData,
          (data) => {
            this.$toast.success('Uploaded Successfully! Images will be minified in up to two minutes.');
            data.data.forEach((element) => {
              this.mediaFiles.push(element);
            });
            this.$modal.hide('upload-media-modal');
            this.loading = false;
            this.regFile = null;

            this.selectedFile = data.data[0];
            this.getFiles(data.data[0].folder_id);
            this.warning = true;

            if (imagefile.type === 'image/jpeg' || imagefile.type === 'image/png' || imagefile.type === 'image/webp') {
              this.$modal.show('edit-image-modal');
            } else {
              this.opImage = this.selectedFile;
              this.$modal.show('gallery-modal');
            }

            this.jpg = false;
            this.alt_text = '';
            this.link = '';
          },
          (error) => {
            console.log(error);
            this.loading = false;
            this.$toast.error(error.response.data.message);
          }
        );
      } else {
        this.upload_error = 'Pick file or folder.';
      }
    },
    renameFolderModal(folder) {
      this.folder_edit_name = folder.name;
      this.folder_edit_id = folder.id;
      this.folder_edit_directory = folder.parent_id;
      this.$modal.show('edit-folder-modal');
    },
    renameFolder() {
      if (this.folder_edit_name && this.folder_edit_id) {
        var formData = new FormData();
        formData.append('name', this.folder_edit_name);
        formData.append('id', this.folder_edit_id);
        formData.append('directory', this.folder_edit_directory);
        this.loading = true;
        axios
          .post('/api/media_manager/folder/edit', formData)
          .then((response) => {
            if (response.status == 200) {
              this.$toast.success('Edited Successfully!');

              this.getFolders();
              this.getFiles();
              this.$modal.hide('edit-folder-modal');
            }
            this.loading = false;
          })
          .catch((error) => {
            console.log(error);
            this.loading = false;
          });
      }
    },
    openMoveModal(file) {
      this.move_file_to = null;
      this.file_to_move = file;
      this.$modal.show('file_move_modal');
    },
    moveFile() {
      if (this.file_to_move && this.move_file_to) {
        var formData = new FormData();
        formData.append('file', JSON.stringify(this.file_to_move));
        formData.append('folder', JSON.stringify(this.move_file_to));
        this.loading = true;
        axios
          .post('/api/media_manager/file/move', formData)
          .then((response) => {
            if (response.status == 200) {
              this.$toast.success('Moved Successfully!');
              _.remove(this.mediaFiles, {
                id: this.file_to_move.id,
              });
              this.$modal.hide('file_move_modal');
            }
            this.loading = false;
            this.move_file_to = null;
          })
          .catch((error) => {
            console.log(error);
            this.loading = false;
          });
      }
    },
    imageAdded($event) {
      this.$toast.error('Upload attachment function does not supported. Please use Document Upload!');
    },
    onImageEditResponse(response, value) {
      this.$toast.success(`${value?.version} - Uploaded Successfully!`);

      if (this.$refs.crpr !== undefined) {
        this.$refs.crpr.isUploading = false;
      }

      this.imageKey = Math.random().toString().substr(2, 8);
      // this.$modal.hide('edit-image-modal');
      if (this.$refs.crpr && response.data.data.version === 'original') {
        var image = response.data.data;
        this.$refs.crpr.imgname = image.name;
        this.$refs.crpr.alttext = image.alt_text;
        this.$refs.crpr.link = image.link;
        this.$refs.crpr.size = image.size;
        this.$refs.crpr.height = image.height;
        this.$refs.crpr.width = image.width;
      } else {
        if (this.$refs.crpr !== undefined) {
          this.$refs.crpr.imgname = this.$refs.crpr.parrentImage.name;
          this.$refs.crpr.alttext = this.$refs.crpr.parrentImage.alttext;
          this.$refs.crpr.link = this.$refs.crpr.parrentImage.link;
          this.$refs.crpr.size = this.$refs.crpr.parrentImage.size;
          this.$refs.crpr.height = this.$refs.crpr.parrentImage.height;
          this.$refs.crpr.width = this.$refs.crpr.parrentImage.width;
        }
      }

      let version = null;
      if (value != null && value.imgname) {
        version = value.version;
      } else {
        version = this.$refs.crpr.version;
      }

      // console.log('VERSION: ', version)
      // console.log('1111: ',this.$refs.crpr.forUpdate)

      if (response) {
        if (this.$refs.crpr !== undefined) {
          //console.log('inside reposnse: version: ', version)
          //console.log('inside reposnse: forUpdate1 latest version: ', Object.keys(this.$refs.crpr.forUpdate1)[Object.keys(this.$refs.crpr.forUpdate1).length-1])
          //console.log('PRE CALL: ', Object.keys(this.$refs.crpr.forUpdate1)[Object.keys(this.$refs.crpr.forUpdate1).length-1] == version)
          if (Object.keys(this.$refs.crpr.forUpdate1)[Object.keys(this.$refs.crpr.forUpdate1).length - 1] == version) {
            //console.log('CALL GET FILES')
            this.getFiles(response.data.data.folder_id, true);

            //return false;
          }
        }

        //console.log('last item response :', version)

        delete this.$refs.crpr.forUpdate[version];

        //console.log('new ', this.$refs.crpr.forUpdate)

        this.selectedFile = response.data.data;

        if (this.$parent.imageVersion && version == this.$parent.imageVersion) {
          // this.$parent.imageVersionResponseData = response.data.data

          if (isNewAdmin()) {
            //this.$refs.crpr.confirmSelection(response.data.data)
          }
        }
      }

      if (this.$refs.crpr !== undefined) {
        this.$refs.crpr.jpg = false;
        this.$refs.crpr.version = 'original';
        this.$refs.crpr.disable();
        this.$refs.crpr.versionData = null;

        if (
          this.$parent.imageVersion == null &&
          this.$refs.crpr.selectedVersion != null &&
          this.$refs.crpr.selectedVersion.version == version
        ) {
          this.$refs.crpr.confirmSelection(response.data.data);
        } else if (this.$parent.imageVersion == null && this.$refs.crpr.selectedVersion == null) {
          this.$refs.crpr.confirmSelection(response.data.data);
        }
      }

      if (!isNewAdmin()) {
        if (version != null && version != 'original') {
          if (this.$refs.crpr) {
            delete this.$refs.crpr.versionsForUpdate[version];
          }
        }
      }
    },
    async imageEdit($event) {
      const self = this,
        value = $event && $event.imgname ? $event : this.$refs.crpr;
      // edit image version
      if (this.$refs.crpr.forUpdate[value.version] === undefined) {
        //console.log('why: ', value.version)
        return false;
      }
      this.$refs.crpr.isUploading = true;
      api.post(
        'media.edit',
        {
          name: value.imgname,
          alt_text: value.alttext,
          link: value.link,
          version: value.version,
          parent_id: value.parrentImage?.id ?? value.parent_id ?? null,
          crop_data: value.crop_data ?? value.cropBoxData ?? (value.getCropBoxData ? value.getCropBoxData() : null),
          width_ratio: value.width_ratio,
          height_ratio: value.height_ratio,
          folder_id: this.selectedFile.folder_id,
          id: value.id,
        },
        (data, response) => {
          self.onImageEditResponse(response, value);
        },
        () => {
          this.$toast.error(`${value.version} - Uploaded Failed!`);
          this.$refs.crpr.isUploading = false;
        }
      );
    },
    addFolder() {
      this.errors = null;

      if (this.folderName && this.move_file_to) {
        this.loading = true;
        axios
          .post('/api/media_manager', {
            name: this.folderName,
            directory: this.move_file_to.id,
          })
          .then((response) => {
            if (response.status == 201 || response.status == 200) {
              this.$toast.success('Created Successfully!');
            }
            this.getFolders();
            this.folderName = '';
            this.loading = false;
            this.$modal.hide('create-folder-modal');
            this.uncollapse(this.mediaFolders[0]);
          })
          .catch((error) => {
            console.log(error);
            this.errors = error.response.data.errors;
            this.loading = false;
          });
      } else {
        this.folder_error = 'Enter folder name od pick a parent folder.';
      }
    },
    deleteFolder(folder) {
      Swal.fire({
        title: 'Are you sure?\n ',
        text: "You won't be able to revert this! This will delete all images in this folder and it's subfolders. Delete folder?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        buttonsStyling: false,
        customClass: {
          cancelButton: 'btn btn-soft-secondary',
          confirmButton: 'btn btn-soft-danger',
        },
        preConfirm: () => {
          return axios
            .delete('/api/media_manager/folder/' + folder.id)
            .then((response) => {
              if (response.status == 200) {
                Swal.fire('Deleted!', 'Folder has been deleted.', 'success');
                this.selectedFolder = null;
              }
              this.getFolders();
              this.getFiles();
            })
            .catch((error) => {
              Swal.fire('Deleted!', 'Folder was not deleted.', 'error');
              this.getFolders();
              this.getFiles();

              console.log(error);
              this.errors = error.response.data.errors;
              this.loading = false;
            });
        },
        allowOutsideClick: () => !Swal.isLoading(),
      });
    },
    getFolders(folderId) {
      this.filesView = false;
      this.folderId = folderId;
      this.errors = null;
      this.loading = true;
      if (folderId) {
        this.selectedFolder = _.find(this.mediaFolders, { id: folderId });
      }
      axios
        .get('/api/media_manager', {
          params: {
            folder_id: folderId,
            //filter: this.searchFilter
          },
        })
        .then((response) => {
          if (!folderId) {
            this.mediaFolders = response.data.data;
            this.collapse(this.mediaFolders[0]);
          }

          this.inMediaFolders = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.errors = error.response.data.errors;
          this.loading = false;
        });

      this.getFiles(folderId);
    },
    getFiles(folderId, from_save_btn = false) {
      //console.log('from get files')

      this.errors = null;
      this.loading = true;
      axios
        .get('/api/media_manager/files', {
          params: {
            folder_id: folderId,
            filter: this.searchFilter,
            parent: this.onlyParent,
          },
        })
        .then((response) => {
          this.mediaFiles = response.data.data;
          this.loading = false;
          this.updateSelectedFile(from_save_btn);
        })
        .catch((error) => {
          //console.log('here is an error')

          if (error.response) {
            // client received an error response (5xx, 4xx)
            //console.log(error.response)
          } else if (error.request) {
            this.getFiles(folderId, from_save_btn);

            // client never received a response, or request never left
            //console.log('after second time call')
          } else {
            console.log(error);
            // anything else
          }
          //return false;
          //   if(error.response !== undefined){
          //     this.errors = error.response.data.errors;
          //   }

          this.loading = false;
        });
    },
    userSelectedFiles($event) {
      if (!this.firstLoadedData.length) {
        this.firstLoadedData = $event;
      }
      this.warning = false;

      if (this.mode && this.withoutImage) {
        this.updatedMediaImage($event);
        return;
      }

      if (this.selectedFile && this.selectedFile.id != $event.id && !isNewAdmin()) {
        this.updatedMediaImage($event);
        //this.$modal.show('edit-image-modal');
      } else if (this.selectedFile == null && this.withoutImage && !isNewAdmin()) {
      } else {
        //alert('not different image')
        this.selectedFile = $event;
        this.$modal.show('edit-image-modal');
      }
    },
    deleteFile($event) {
      // console.log('triggered function delete File')
      // console.log($event)

      this.tempDelete = $event.version;

      var pagesText = '';
      var pages_count = $event.pages_count;
      if (pages_count) {
        pagesText = pagesText + 'This image is used on ' + pages_count + ' pages.';
      }
      if ($event.parrent == null) {
        pagesText = pagesText + 'This is an original image, this action will delete all its subimages that exist.';
      }

      if (isNewAdmin()) {
        Swal.fire({
          title: 'Are you sure?\n ' + pagesText,
          text: "You won't be able to revert this! Delete file?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          showLoaderOnConfirm: true,
          buttonsStyling: false,
          customClass: {
            cancelButton: 'btn btn-soft-secondary',
            confirmButton: 'btn btn-soft-danger',
          },
          preConfirm: () => {
            return axios
              .delete('/api/media_manager/file/' + $event.id)
              .then((response) => {
                if (response.status == 200) {
                  //console.log('test folder: ', $event.folder_id)
                  this.getFiles($event.folder_id);
                }
              })
              .catch((error) => {
                Swal.showValidationMessage(`Request failed: ${error}`);
              });
          },
          allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire('Deleted!', 'Item has been deleted.', 'success');
          }
        });
      } else {
        Swal.fire({
          type: 'warning',
          title: 'Are you sure?\n ' + pagesText,
          text: "You won't be able to revert this! Delete file?",
          showCancelButton: true,
          confirmButtonColor: '#f1556c',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!',
          preConfirm: () => {
            return axios
              .delete('/api/media_manager/file/' + $event.id)
              .then((response) => {
                if (response.status == 200) {
                  //console.log('test folder: ', $event.folder_id)

                  if (this.imageVersion != null && this.imageVersion == $event.version) {
                    this.getFiles($event.folder_id, true);
                  } else if (this.imageVersion != null && this.imageVersion != $event.version) {
                    this.getFiles($event.folder_id);
                  } else {
                    this.getFiles($event.folder_id, true);
                  }
                }
              })
              .catch((error) => {
                Swal.showValidationMessage(`Request failed: ${error}`);
              });
          },
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
          }
        });
      }
    },
    updateSelectedFile(from_save_btn = false) {
      if (this.selectedFile) {
        var oldFile = this.selectedFile;

        let versionImages = null;
        versionImages = oldFile.subfiles;
        let versionImageForUpdateWindow = null;

        if (this.selectedFile.parrent == null) {
          oldFile = this.selectedFile;
        } else {
          oldFile = this.selectedFile.parrent;
        }

        var index = this.mediaFiles.findIndex(function (file) {
          return file.id == oldFile.id;
        });

        if (this.mediaFiles[index]) {
          this.selectedFile = this.mediaFiles[index];
          setTimeout(() => {
            if (from_save_btn) {
              if (this.$refs.crpr !== undefined) {
                this.$refs.crpr.setupPrevalue();
              }
            } else {
              if (this.$refs.crpr !== undefined) {
                this.$refs.crpr.setupPrevalue(true);
              }
            }
          }, 1000);
        }
      }
    },
  },
};

export default mediaMixin;
