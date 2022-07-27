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
                this.uncollapse(el);
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
            if (imagefile && this.move_file_to) {
                this.upload_error = null;
                this.loading = true;
                formData.append("file", imagefile);
                formData.append('directory', this.move_file_to.id);
                axios.post('/api/media_manager/upload_reg_file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    //this.selectedFolder = null;
                    this.$toast.success('Uploaded Successfully!');
                    //this.$modal.hide('upload-media-modal');
                    response.data.data.forEach((element) => {
                        this.mediaFiles.push(element);
                    })
                    this.$modal.hide('upload-file-modal');
                    this.loading = false;
                    this.regFile = null;
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                })
            } else {
                this.upload_error = "Pick file or folder.";
            }
        },
        uploadImgFile() {
            var formData = new FormData();
            var imagefile = this.regFile;

            if (imagefile && this.move_file_to) {
                this.loading = true;
                formData.append("file", imagefile);
                formData.append("alt_text", this.alt_text);
                formData.append("link", this.link);
                formData.append("jpg", this.jpg);
                formData.append('directory', this.move_file_to.id);
                axios.post('/api/media_manager/upload_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    this.$toast.success('Uploaded Successfully! Images will be minified in up to two minutes.');
                    response.data.data.forEach((element) => {
                        this.mediaFiles.push(element);
                    })
                    this.$modal.hide('upload-media-modal');
                    this.loading = false;
                    this.regFile = null;

                    this.selectedFile = response.data.data[0];
                    this.getFiles(response.data.data[0].folder_id);
                    this.warning = true;

                    if (imagefile.type == "image/jpeg" || imagefile.type == "image/png") {
                        this.$modal.show('edit-image-modal');
                    } else {
                        this.opImage = this.selectedFile;
                        this.$modal.show('gallery-modal');
                    }

                    this.jpg = false;
                    this.alt_text = "";
                    this.link = "";
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                    this.$toast.error(error.response.data.message);
                })
            } else {
                this.upload_error = "Pick file or folder.";
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
                axios.post('/api/media_manager/folder/edit', formData)
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
                    console.log(error)
                    this.loading = false;
                })
            }
        },
        openMoveModal(file){
            this.move_file_to = null;
            this.file_to_move = file;
            this.$modal.show('file_move_modal');
        },
        moveFile(){
            if (this.file_to_move && this.move_file_to) {
                var formData = new FormData();
                formData.append('file', JSON.stringify(this.file_to_move));
                formData.append('folder', JSON.stringify(this.move_file_to));
                this.loading = true;
                axios.post('/api/media_manager/file/move', formData)
                .then((response) => {
                    if (response.status == 200) {
                        this.$toast.success('Moved Successfully!');
                        _.remove(this.mediaFiles, {
                            id: this.file_to_move.id
                        });
                        this.$modal.hide('file_move_modal');
                    }
                    this.loading = false;
                    this.move_file_to = null;
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
            formData.append('imgname', this.$refs.crpr.imgname);
            formData.append('alttext', this.$refs.crpr.alttext);
            formData.append('compression', this.$refs.crpr.compression);
            formData.append('parrent_id', this.$refs.crpr.parrentImage.id ? this.$refs.crpr.parrentImage.id : null);
            if (this.$refs.crpr.prevalue) {
                formData.append('edited', this.$refs.crpr.prevalue.id);
            }
            formData.append('original_file', this.$refs.crpr.originalFile);
            formData.append('directory', this.move_file_to.id);
            if (imagefile) {
                this.$refs.crpr.isUploading = true;
                formData.append("file", imagefile);
                axios.post('/api/media_manager/upload_image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
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
                    // console.log(response)
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
            var formData = new FormData();
            formData.append('imgname', this.$refs.crpr.imgname);
            formData.append('alttext', this.$refs.crpr.alttext);
            formData.append('link', this.$refs.crpr.link);
            formData.append('jpg', this.$refs.crpr.jpg);
            formData.append('version', this.$refs.crpr.version);
            formData.append('parent_id', this.$refs.crpr.parrentImage.id);
            formData.append('crop_data', JSON.stringify(this.$refs.crpr.cropBoxData));
            formData.append('width_ratio', this.$refs.crpr.width_ratio);
            formData.append('height_ratio', this.$refs.crpr.height_ratio);
            formData.append('directory', this.selectedFile.folder_id);
            formData.append('id', this.selectedFile.id);
            this.$refs.crpr.isUploading = true;
            axios.post('/api/media_manager/edit_image', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                this.$toast.success('Uploaded Successfully!');
                this.getFiles(response.data.data.folder_id);
                this.$refs.crpr.isUploading = false;
                this.imageKey = Math.random().toString().substr(2, 8);
                // this.$modal.hide('edit-image-modal');

                this.$refs.crpr.imgname = this.$refs.crpr.parrentImage.name;
                this.$refs.crpr.alttext = this.$refs.crpr.parrentImage.alttext;
                this.$refs.crpr.link = this.$refs.crpr.parrentImage.link;
                this.$refs.crpr.jpg = false;
                this.$refs.crpr.version = 'original';
            })
            .catch((error) => {
                console.log("edit error", error.response.data.message);
                this.$refs.crpr.isUploading = false;
                this.$toast.error("Failed to update. " + error.response.data.message);
            })
        },
        addFolder() {
            this.errors = null;

            if (this.folderName && this.move_file_to) {
            this.loading = true;
            axios
                .post('/api/media_manager',
                    {
                        name: this.folderName,
                        directory: this.move_file_to.id,
                    }
                )
                .then((response) => {
                    if (response.status == 201 || response.status == 200) {
                        this.$toast.success('Created Successfully!')
                    }
                    this.getFolders();
                    this.folderName = '';
                    this.loading = false;
                    this.$modal.hide('create-folder-modal');
                    this.uncollapse(this.mediaFolders[0]);
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
            } else {
                this.folder_error = "Enter folder name od pick a parent folder.";
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
                                Swal.fire(
                                    'Deleted!',
                                    'Folder has been deleted.',
                                    'success'
                                );
                                this.selectedFolder = null;
                            }
                            this.getFolders();
                            this.getFiles();
                        })
                        .catch((error) => {
                            Swal.fire(
                                'Deleted!',
                                'Folder was not deleted.',
                                'error'
                            )
                            this.getFolders();
                            this.getFiles();

                            console.log(error)
                            this.errors = error.response.data.errors;
                            this.loading = false;
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
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
                    if (!folderId) {
                        this.mediaFolders = response.data.data;
                        this.collapse(this.mediaFolders[0]);
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
                    this.mediaFiles = response.data.data;
                    this.loading = false;
                    this.updateSelectedFile();
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
        },
        userSelectedFiles($event) {
            this.selectedFile = $event;
            this.warning = false;
            this.$modal.show('edit-image-modal');
        },
        deleteFile($event) {
            var pagesText = "";
            var pages_count = $event.pages_count;
            if (pages_count) {
                pagesText = pagesText + "This image is used on " + pages_count + " pages.";
            }
            if ($event.parrent == null) {
                pagesText = pagesText + "This is an original image, this action will delete all its subimages that exist.";
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
                                this.getFiles($event.folder_id);
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
        },
        updateSelectedFile() {
            if (this.selectedFile) {
                var oldFile = this.selectedFile;
                var index = this.mediaFiles.findIndex(function(file) {
                    return file.id == oldFile.id;
                });
                if (this.mediaFiles[index]) {
                    this.selectedFile = this.mediaFiles[index];
                }
            }
        }
    }
}

export default mediaMixin;
