<div id="locations">

    <!-- errors -->
    <div class="alert alert-danger hidden" id="errors">
        <!-- print errors -->
    </div>

    <?php $this->load->view('pages/index', $locations); ?>

    <!-- modal -->
    <div class="modal" id="modal-delete-location">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Locatie Verwijderen</h4>
                </div>
                <div class="modal-body">
                    <p>Bent u zeker dat u deze locatie wilt verwijderen?</p>
                    <p>Alle items in deze locatie worden ook permanent verwijderd.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-submit" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                </div>
            </div>
        </div>
    </div>

</div>