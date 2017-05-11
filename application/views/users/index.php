<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?=site_url('home'); ?>"><span class="fa fa-home"></span></a></li>
    <li class="active"><?=$title; ?></li>
</ul>

<!-- title -->
<h2><?=$title; ?></h2>

<!-- hidden fields -->
<input type="hidden" id="user_id" value="<?=$user_id; ?>">

<div id="buttons">
    <a href="#" class="btn btn-primary" id="show-passwd-box" data-toggle="tooltip" data-original-title="Wachtwoord Veranderen"><span class="fa fa-key"></span></a>
</div>

<!-- info -->
<div id="info">
    <h3>Info</h3>
    <table class="table table-striped table-hover">
        <tbody>
            <tr>
                <td>Databank ID:</td>
                <td id="db_id"></td>
            </tr>
            <tr>
                <td>Gebruiker ID:</td>
                <td id="uid"></td>
            </tr>
            <tr>
                <td>Voornaam:</td>
                <td id="firstname"></td>
            </tr>
            <tr>
                <td>Achternaam:</td>
                <td id="lastname"></td>
            </tr>
            <tr>
                <td>Rol:</td>
                <td id="role"></td>
            </tr>
        </tbody>
    </table>

</div>

<!-- Active loans -->
<div id="active-loans">
    <h3>Reservaties</h3>
    <a href="<?=site_url('loans/view/user/'.$user_id); ?>" class="btn btn-primary" id="view-all" data-toggle="tooltip" data-original-title="Volledige Geschiedenis"><span class="fa fa-list-alt"></span></a>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item ID</th>
                <th>Locatie</th>
                <th>Categorie</th>
                <th>Van</th>
                <th>Tot</th>
            </tr>
        </thead>
        <tbody>
            <!-- AJAX data -->
        </tbody>
    </table>
</div>

<!-- Box -->
<div class="modal" id="new-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                <input id="valid" type="hidden" value="false">
                <div class="form-group">
                    <label class="control-label" for="password-1">New password</label>
                    <input class="form-control" id="password-1" type="password" placeholder="Password...">
                </div>
                <div class="form-group">
                    <label class="control-label" for="password-2">Repeat password</label>
                    <input class="form-control" id="password-2" type="password" placeholder="Password...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-close"></span></button>
                <button type="button" class="btn btn-success disabled" id="pwd-submit"><span class="fa fa-save"></span></button>
            </div>
        </div>
    </div>
</div>