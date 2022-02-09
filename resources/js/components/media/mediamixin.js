var mediaMixin = {
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
        imageAdded($event) {

            console.log($event, this.selectedFolder)
            this.currentImage = $event;
            var formData = new FormData();
            var imagefile = $event;
            console.log(this.$refs)
            formData.append('imgname', this.$refs.crpr.imgname);
            formData.append('alttext', this.$refs.crpr.alttext);
            if (this.$refs.crpr.prevalue) {
                formData.append('edited', this.$refs.crpr.prevalue.id);
            }
            formData.append('original_file', this.$refs.crpr.originalFile);
            if (this.selectedFolder) {
                formData.append('directory', this.selectedFolder.id);
            }
            console.log('imgfile', imagefile)
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
                        this.mediaFiles.push(element);
                        this.$refs.crpr.uploadedVersions.push(element);
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
            this.$modal.show('upload-media-modal');
        },
        deleteFile($event) {
            Swal.fire({
                title: 'Are you sure?',
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
                                this.mediaFiles.splice(_.findIndex(this.mediaFiles, { 'id': $event.id }), 1);
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
