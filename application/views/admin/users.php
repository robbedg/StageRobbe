<div class="tab-pane fade active in" id="users">
    <!-- top -->
    <div id="top">
        <!-- search -->
        <div id="searchusersbox">
            <input class="form-control" id="searchusers" type="text" placeholder="Zoeken...">
        </div>

        <!-- filter options -->
        <div id="filter">
            <ul class="nav nav-pills">
                <li id="all" class="active"><a class="filterlist" href="#">Alle <span class="badge"></span></a></li>
                <?php foreach ($roles as $role): ?>
                    <li><a class="filterlist" href="#" data-id="<?=$role['id']; ?>"><?=$role['name']; ?> <span class="badge"></span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- left -->
    <div id="left">
        <!-- user selection -->
        <div id="userselect">
            <ul>
                <!-- AJAX data-->
            </ul>
        </div>
    </div>

    <!-- Right -->
    <div id="right">

        <!-- open form -->
        <?=form_open('admin', array('id' => 'form')); ?>

            <!-- hidden field with userid -->
            <div id="userid">
                <input type="hidden" name="userid">
            </div>

            <!-- edit first name -->
            <div id="firstname">
                <label for="editFirstname" class="control-label">Voornaam</label>
                <input id="editFirstname" type="text" class="form-control" name="firstname" disabled="disabled">
            </div>

            <!-- edit last name -->
            <div id="lastname">
                <label for="editLastname" class="control-label">Familienaam</label>
                <input id="editLastname" type="text" class="form-control" name="lastname" disabled="disabled">
            </div>

            <!-- role selection -->
            <div id="roleselect">
                <label for="selectRole" class="control-label">Rol</label>
                <select class="form-control" id="selectRole" disabled="disabled" name="role">
                    <?php foreach ($roles as $role): ?>
                        <?php if ((intval($role['id']) < intval($_SESSION['role_id'])) || (intval($_SESSION['role_id']) === 4)): ?>
                            <option value="<?=$role['id']; ?>"><?=$role['name']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- submit button -->
            <div id="submit">
                <button id="to-profile" class="btn btn-primary btn-lg" disabled="disabled" data-toggle="tooltip" data-original-title="Bekijk Profiel"><span class="fa fa-user"></span></button>
                <button id="delete-user" class="btn btn-danger btn-lg" disabled="disabled" data-toggle="tooltip" data-original-title="Verwijderen"><span class="fa fa-trash"></span></button>
                <button type="submit" class="btn btn-primary btn-lg" disabled="disabled" data-toggle="tooltip" data-original-title="Opslaan"><span class="fa fa-save"></span></button>
            </div>
        <?=form_close(); ?>
    </div>

    <!-- delete message -->
    <div class="modal" id="delete-message">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Verwijder Gebruiker</h4>
                </div>
                <div class="modal-body">
                    <!-- Error msg -->
                    <div class="alert alert-danger hidden" id="msg-error">
                        <p>U kan deze gebruiker niet verwijderen.</p>
                    </div>
                    <!-- msg text -->
                    <p>Wilt u deze gebruiker verwijderen?</p>
                    <p>Dit kan niet ongedaan gemaakt worden.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete-user-btn"><span class="fa fa-trash"></span></button>
                </div>
            </div>
        </div>
    </div>


</div>