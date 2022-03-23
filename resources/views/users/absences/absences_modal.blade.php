 <!-- Create Modal -->
 <div class="modal fade" id="absences-info" tabindex="-1" role="dialog" aria-labelledby="assignUserEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserEventLabel">Absences Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="absences-modal">

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0" >Total Presence Hours</h5>
                                        <span class="h2 font-weight-bold mb-0" id="presence-hours"></span>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Absence Hours</h5>
                                        <span class="h2 font-weight-bold mb-0" id="absence-hours"></span>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Absence(%)</h5>
                                        <span class="h2 font-weight-bold mb-0" id="absence-percent"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            
        </div>
    </div>
</div>