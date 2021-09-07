<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Courses</h5>
                        <span class="h2 font-weight-bold mb-0"><?= $data['total_courses']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Live Courses</h5>
                        <span class="h2 font-weight-bold mb-0"><?= $data['live_courses']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Completed Courses</h5>
                        <span class="h2 font-weight-bold mb-0"><?= $data['completed_courses']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/event/layouts/cards.blade.php ENDPATH**/ ?>