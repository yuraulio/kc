import _ from "lodash";

var mediaMixin = {
    data() {
        return {
            versions: [
                {
                  "w": 470,
                  "h": 470,
                  "q": 60,
                  "fit": "crop",
                  "version": "instructors-testimonials",
                  "description": "Applies to : Our Instructor Page (Footer) & Event -> Instructors"
                },
                {
                  "w": 542,
                  "h": 291,
                  "q": 60,
                  "fit": "crop",
                  "version": "event-card",
                  "description": "Applies to : Homepage Events list"
                },
                {
                  "w": 470,
                  "h": 470,
                  "q": 60,
                  "fit": "crop",
                  "version": "users",
                  "description": "Applies to : Testimonial square image"
                },
                {
                  "w": 2880,
                  "h": 1248,
                  "q": 60,
                  "fit": "crop",
                  "version": "header-image",
                  "description": "Applies to: Event header carousel (Main event page)"
                },
                {
                  "w": 90,
                  "h": 90,
                  "q": 60,
                  "fit": "crop",
                  "version": "instructors-small",
                  "description": "Applies to : Event -> Topics (syllabus-block)"
                },
                {
                  "w": 300,
                  "h": 300,
                  "q": 60,
                  "fit": "crop",
                  "description": "feed-image",
                  "version": "feed-image"
                },
                {
                  "w": 1920,
                  "h": 832,
                  "q": 60,
                  "fit": "crop",
                  "version": "social-media-sharing",
                  "description": "Applies to: Social media sharing default image"
                }
              ]
        }
    },
    methods: {
        updatedMediaImage(img) {
            console.log('updatedmedia', img)
            this.$emit('updatedimg', img);
        },
        openFile(file, ref) {
            this.opImage = file;
            this.$modal.show('gallery-modal', file);
        },
        collapse(item) {
            item.children.forEach((el) => {
                this.uncolapsed.push(el.id);
            })
        },
        uncollapse(item) {
            item.children.forEach((el) => {
                this.uncolapsed.splice(this.uncolapsed.indexOf(el.id), 1);
            })
        },
        selectedFolders($event) {
            this.filesView = false;
            this.selectedFolder = $event;
        },
        uploadRegFile() {
            var formData = new FormData();
            var imagefile = this.regFile;
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            if (imagefile) {
                this.loading = true;
                formData.append("file", imagefile);
                axios.post('/api/media_manager/upload_reg_file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    console.log(response.data)
                    //this.selectedFolder = null;
                    this.$toast.success('Uploaded Successfully!');
                    //this.$modal.hide('upload-media-modal');
                    response.data.data.forEach((element) => {
                        this.mediaFiles.push(element);
                    })
                    this.$modal.hide('upload-file-modal');
                    console.log(response)
                    this.loading = false;
                    this.regFile = null;
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                })
            }
        },
        uploadImgFile() {
            var formData = new FormData();
            var imagefile = this.regFile;
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            if (imagefile) {
                this.loading = true;
                formData.append("file", imagefile);
                axios.post('/api/media_manager/upload_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    this.$toast.success('Uploaded Successfully!');
                    response.data.data.forEach((element) => {
                        this.mediaFiles.push(element);
                    })
                    this.$modal.hide('upload-media-modal');
                    this.loading = false;
                    this.regFile = null;

                    this.selectedFile = response.data.data[0];
                    this.$modal.show('edit-image-modal');
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                })
            }
        },
        imageAdded($event) {
            this.currentImage = $event;
            var formData = new FormData();
            var imagefile = $event;
            console.log(this.$refs)
            formData.append('imgname', this.$refs.crpr.imgname);
            formData.append('alttext', this.$refs.crpr.alttext);
            formData.append('compression', this.$refs.crpr.compression);
            formData.append('parrent_id', this.$refs.crpr.parrentImage.id ? this.$refs.crpr.parrentImage.id : null);
            if (this.$refs.crpr.prevalue) {
                formData.append('edited', this.$refs.crpr.prevalue.id);
            }
            formData.append('original_file', this.$refs.crpr.originalFile);
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            if (imagefile) {
                this.$refs.crpr.isUploading = true;
                formData.append("file", imagefile);
                axios.post('/api/media_manager/upload_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    console.log(response.data)
                    //this.selectedFolder = null;
                    this.$toast.success('Uploaded Successfully!');
                    //this.$modal.hide('upload-media-modal');
                    response.data.data.forEach((element) => {
                        //this.mediaFiles.push(element);
                        this.$refs.crpr.uploadedVersions.push(element);
                    })
                    response.data.original.forEach((element) => {
                        if (!_.find(this.mediaFiles, { id: element.id })) {
                            this.mediaFiles.unshift(element);
                        }
                    })
                    console.log(response)
                    this.$refs.crpr.isUploading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.$refs.crpr.isUploading = false;
                })
            }
        },
        imageEdit($event) {
            var id = this.selectedFile.id;
            this.currentImage = $event;
            var formData = new FormData();
            var imagefile = $event;
            formData.append('imgname', this.$refs.crpr.imgname);
            formData.append('alttext', this.$refs.crpr.alttext);
            formData.append('compression', this.$refs.crpr.compression);
            formData.append('version', this.$refs.crpr.version);
            formData.append('id', id);
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            if (imagefile) {
                this.$refs.crpr.isUploading = true;
                formData.append("file", imagefile);
                axios.post('/api/media_manager/edit_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    this.$toast.success('Uploaded Successfully!');
                    var element = response.data.data;
                    var index = this.mediaFiles.findIndex(function(file) {
                        return file.id == id;
                    });
                    console.log("index", index);
                    this.mediaFiles[index] = element;
                    this.$refs.crpr.isUploading = false;
                    this.$modal.hide('edit-image-modal');
                })
                .catch((error) => {
                    console.log(error)
                    this.$refs.crpr.isUploading = false;
                })
            }
        },
        addFolder() {
            this.errors = null;
            this.loading = true;
            axios
                .post('/api/media_manager',
                    {
                        name: this.folderName,
                    }
                )
                .then((response) => {
                    if (response.status == 201 || response.status == 200) {
                        this.$toast.success('Created Successfully!')
                    }
                    this.mediaFolders.push(response.data.data)
                    this.folderName = '';
                    this.loading = false;
                    this.$modal.hide('create-folder-modal')
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
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
                    }
                })
                .then((response) => {
                    console.log(response.data);
                    if (!folderId) {
                        this.mediaFolders = response.data.data;
                    }

                    this.inMediaFolders = response.data.data;

                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });

            this.getFiles(folderId);
        },
        getFiles(folderId) {
            this.errors = null;
            this.loading = true;
            axios
                .get('/api/media_manager/files', {
                    params: {
                        folder_id: folderId,
                        filter: this.searchFilter,
                        parent: this.onlyParent
                    }
                })
                .then((response) => {
                    console.log(response.data);
                    this.mediaFiles = response.data.data;
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
        },
        userSelectedFiles($event) {
            this.selectedFile = $event;
            this.$modal.show('edit-image-modal');
        },
        deleteFile($event) {
            var pagesText = "";
            var pages = $event.pages;
            if (pages.length) {
                pagesText = "This image is used on pages:\n";
                pages.forEach(function(page){
                    console.log(page);
                    pagesText = pagesText + "\n" + page.title;
                });
            }
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
                                if (_.findIndex(this.mediaFiles, { 'id': $event.id }) > -1) {
                                    this.mediaFiles.splice(_.findIndex(this.mediaFiles, { 'id': $event.id }), 1);
                                } else {
                                    this.mediaFiles.forEach((element) => {
                                        element.subfiles.splice(_.findIndex(element.subfiles, { 'id': $event.id }), 1);
                                    })
                                }

                            }
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        'Item has been deleted.',
                        'success'
                    )
                }
            })
        }
    }
}

export default mediaMixin;
