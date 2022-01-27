<template>
    <div class="mt-3">
        <h5 class="mb-3">Recent Files</h5>
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
                <tr v-for="file in mediaFiles" :key="file.id">
                    <td>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder icon-dual">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg> -->
                        <img :src="file.url" alt="image" class="img-fluid avatar-sm rounded mt-2">
                        <span class="ms-2 fw-normal"><a href="javascript: void(0);" class="text-reset">{{ file.name }}</a></span>
                    </td>
                    <td>
                        <p class="mb-0">{{ (new Date(file.created_at)).toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " ") }}</p>
                    </td>
                    <td>{{ parseFloat(file.size * 0.000001).toFixed(1) }} MB</td>
                    <td>
                        <span v-if="file.user">
                            {{ file.user.firstname }}
                            {{ file.user.lastname }}
                    </span>
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
            </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        mediaFiles: {}
    },
}
</script>
