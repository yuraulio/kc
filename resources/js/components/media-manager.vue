<template>
<div class="row">
    <modal name="create-folder-modal" :resizable="true" height="auto" :adaptive="true">

    </modal>
    <modal name="upload-media-modal" :resizable="true" height="auto" :adaptive="true">

    </modal>
   <!-- Right Sidebar -->
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <!-- Left sidebar -->
            <div class="inbox-leftbar">
               <div class="btn-group d-block mb-2">
                  <button type="button" class="btn btn-success w-100 waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-plus"></i> Create New</button>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="#" @click.prevent="$modal.show('create-folder-modal')"><i class="mdi mdi-folder-plus-outline me-1"></i> Folder</a>
                     <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-file-plus-outline me-1"></i> File</a>
                     <a class="dropdown-item" href="#"><i class="mdi mdi-file-document me-1"></i> Document</a> -->
                     <a class="dropdown-item" href="#" @click.prevent="$modal.show('upload-media-modal')"><i class="mdi mdi-upload me-1"></i> Upload</a>
                  </div>
               </div>
               <div class="mail-list mt-3">
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-folder-outline font-18 align-middle me-2"></i>My Folders</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-file-outline font-18 align-middle me-2"></i>My Files</a>
                  <!-- <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-google-drive font-18 align-middle me-2"></i>Google Drive</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-dropbox font-18 align-middle me-2"></i>Dropbox</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-share-variant font-18 align-middle me-2"></i>Share with me</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Recent</a>
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-star-outline font-18 align-middle me-2"></i>Starred</a> -->
                  <a href="#" class="list-group-item border-0 font-14"><i class="mdi mdi-delete font-18 align-middle me-2"></i>Deleted Files</a>
               </div>
               <div class="mt-5">
                  <h4><span class="badge rounded-pill p-1 px-2 badge-soft-secondary">FREE</span></h4>
                  <h6 class="text-uppercase mt-3">Storage</h6>
                  <div class="progress my-2 progress-sm">
                     <div class="progress-bar progress-lg bg-success" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <p class="text-muted font-12 mb-0">7.02 GB (46%) of 15 GB used</p>
               </div>
            </div>
            <!-- End Left sidebar -->
            <div class="inbox-rightbar">
               <div class="d-md-flex justify-content-between align-items-center">
                  <form class="search-bar">
                     <div class="position-relative">
                        <input type="text" class="form-control form-control-light" placeholder="Search files...">
                        <span class="mdi mdi-magnify"></span>
                     </div>
                  </form>
                  <div class="mt-2 mt-md-0">
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-format-list-bulleted"></i></button>
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-view-grid"></i></button>
                     <button type="submit" class="btn btn-sm btn-white"><i class="mdi mdi-information-outline"></i></button>
                  </div>
               </div>
               <div class="mt-3">
                  <h5 class="mb-2">Quick Access</h5>
                  <div class="row mx-n1 g-0">
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-folder-zip font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">Ubold-sketch-design.zip</a>
                                    <p class="mb-0 font-13">2.3 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-folder font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">Compile Version</a>
                                    <p class="mb-0 font-13">87.2 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-soft-primary text-primary rounded">
                                       <i class="mdi mdi-folder-zip font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">admin-dashboard.zip</a>
                                    <p class="mb-0 font-13">45.1 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-file-pdf-outline font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">Documentation.pdf</a>
                                    <p class="mb-0 font-13">7.5 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-file-pdf-outline font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">License-details.pdf</a>
                                    <p class="mb-0 font-13">784 KB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-folder-account font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">Purchase Verification</a>
                                    <p class="mb-0 font-13">2.2 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                     <div class="col-xl-3 col-lg-6">
                        <div class="card m-1 shadow-none border">
                           <div class="p-2">
                              <div class="row align-items-center">
                                 <div class="col-auto pe-0">
                                    <div class="avatar-sm">
                                       <span class="avatar-title bg-light text-secondary rounded">
                                       <i class="mdi mdi-folder-account font-18"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <a href="javascript:void(0);" class="text-muted fw-normal">Ubold Integrations</a>
                                    <p class="mb-0 font-13">874 MB</p>
                                 </div>
                              </div>
                              <!-- end row -->
                           </div>
                           <!-- end .p-2-->
                        </div>
                        <!-- end col -->
                     </div>
                     <!-- end col-->
                  </div>
                  <!-- end row-->
               </div>
               <!-- end .mt-3-->
               <div class="mt-3">
                  <h5 class="mb-3">Recent</h5>
                  <div class="table-responsive">
                     <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                           <tr>
                              <th class="border-0">Name</th>
                              <th class="border-0">Last Modified</th>
                              <th class="border-0">Size</th>
                              <th class="border-0">Owner</th>
                              <th class="border-0">Members</th>
                              <th class="border-0" style="width: 80px;">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder icon-dual">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                 </svg>
                                 <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">App Design &amp; Development</a></span>
                              </td>
                              <td>
                                 <p class="mb-0">Jan 03, 2020</p>
                                 <span class="font-12">by Andrew</span>
                              </td>
                              <td>128 MB</td>
                              <td>
                                 Danielle Thompson
                              </td>
                              <td id="tooltips-container">
                                 <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mat Helme">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Michael Zenaty">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="James Anderson">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Username">
                                    </a>
                                 </div>
                              </td>
                              <td>
                                 <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-share-variant me-2 text-muted vertical-middle"></i>Share</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file icon-dual">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                 </svg>
                                 <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">Ubold-sketch-design.zip</a></span>
                              </td>
                              <td>
                                 <p class="mb-0">Feb 13, 2020</p>
                                 <span class="font-12">by Coderthemes</span>
                              </td>
                              <td>521 MB</td>
                              <td>
                                 Coder Themes
                              </td>
                              <td id="tooltips-container1">
                                 <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mat Helme">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Michael Zenaty">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-container="#tooltips-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="James Anderson">
                                    </a>
                                 </div>
                              </td>
                              <td>
                                 <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-share-variant me-2 text-muted vertical-middle"></i>Share</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text icon-dual">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                 </svg>
                                 <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">Annualreport.pdf</a></span>
                              </td>
                              <td>
                                 <p class="mb-0">Dec 18, 2019</p>
                                 <span class="font-12">by Alejandro</span>
                              </td>
                              <td>7.2 MB</td>
                              <td>
                                 Gary Coley
                              </td>
                              <td>
                                 <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mat Helme">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Michael Zenaty">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="James Anderson">
                                    </a>
                                 </div>
                              </td>
                              <td>
                                 <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-share-variant me-2 text-muted vertical-middle"></i>Share</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder icon-dual">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                 </svg>
                                 <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">Wireframes</a></span>
                              </td>
                              <td>
                                 <p class="mb-0">Nov 25, 2019</p>
                                 <span class="font-12">by Dunkle</span>
                              </td>
                              <td>54.2 MB</td>
                              <td>
                                 Jasper Rigg
                              </td>
                              <td>
                                 <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mat Helme">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Michael Zenaty">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="James Anderson">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Username">
                                    </a>
                                 </div>
                              </td>
                              <td>
                                 <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-share-variant me-2 text-muted vertical-middle"></i>Share</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text icon-dual">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                 </svg>
                                 <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">Documentation.docs</a></span>
                              </td>
                              <td>
                                 <p class="mb-0">Feb 9, 2020</p>
                                 <span class="font-12">by Justin</span>
                              </td>
                              <td>8.3 MB</td>
                              <td>
                                 Cooper Sharwood
                              </td>
                              <td>
                                 <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mat Helme">
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Michael Zenaty">
                                    </a>
                                 </div>
                              </td>
                              <td>
                                 <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-share-variant me-2 text-muted vertical-middle"></i>Share</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-link me-2 text-muted vertical-middle"></i>Get Sharable Link</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Rename</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download</a>
                                       <a class="dropdown-item" href="#"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- end .mt-3-->
            </div>
            <!-- end inbox-rightbar-->
            <div class="clearfix"></div>
         </div>
      </div>
      <!-- end card -->
   </div>
   <!-- end Col -->
</div>
</template>
