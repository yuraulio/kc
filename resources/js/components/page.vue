<template>
    <div class="col-12">
        <div class="card project-box">
            <div class="card-body">
                <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle card-drop arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Delete</a>
                    </div>
                </div> <!-- end dropdown -->
                <!-- Title-->
                <h4 class="mt-0"><a href="project-detail.html" class="text-dark">Custom Software Development</a></h4>
                <p class="text-muted text-uppercase"><i class="mdi mdi-account-circle"></i> <small>Apple Navigations</small></p>
                <div class="badge bg-soft-secondary text-secondary mb-3">Ongoing</div>
                <!-- Desc-->
                <p class="text-muted font-13 mb-3 sp-line-2">You need to be sure there isn't anything embarrassing hidden in the middle of text...<a href="javascript:void(0);" class="fw-bold text-muted">view more</a>
                </p>
                <!-- Task info-->
                <p class="mb-1">
                    <span class="pe-2 text-nowrap mb-2 d-inline-block">
                        <i class="mdi mdi-format-list-bulleted-type text-muted"></i>
                        <b>95</b> Tasks
                    </span>
                    <span class="text-nowrap mb-2 d-inline-block">
                        <i class="mdi mdi-comment-multiple-outline text-muted"></i>
                        <b>1.3k</b> Comments
                    </span>
                </p>
                <!-- Progress-->
                <p class="mb-2 fw-medium">Task completed: <span class="float-end">70/95</span></p>
                <div class="progress mb-1" style="height: 7px;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;">
                    </div><!-- /.progress-bar .progress-bar-danger -->
                </div><!-- /.progress .no-rounded -->
            </div>
        </div> <!-- end card box-->
    </div>
</template>
