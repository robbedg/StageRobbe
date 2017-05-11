<div id="deleted-items">
    <?php $this->load->view('pages/index', $deleted_items); ?>

    <!-- modals -->
    <!-- delete -->
    <div class="modal" id="modal-delete-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Item Verwijderen</h4>
                </div>
                <div class="modal-body">
                    <p>Bent u zeker dat u dit item wilt verwijderen?</p>
                    <p>Het item wordt permanent verwijderd.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-submit-delete" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- restore -->
    <div class="modal" id="modal-restore-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Item Terugzetten</h4>
                </div>
                <div class="modal-body">
                    <p>Bent u zeker dat u dit item wilt terugzetten?</p>
                    <p>Het item wordt op zijn orginele plaats teruggezet.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-submit-restore" class="btn btn-success"><span class="fa fa-undo"></span></button>
                </div>
            </div>
        </div>
    </div>

</div>