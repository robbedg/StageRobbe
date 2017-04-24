<div class="tab-pane fade active in" id="users">
    <!-- top -->
    <div id="top">
        <!-- search -->
        <div id="searchusersbox">
            <input class="form-control" id="searchusers" type="text" placeholder="Search...">
        </div>

        <!-- filter options -->
        <div id="filter">
            <ul class="nav nav-pills">
                <li id="all" class="active"><a class="filterlist" href="#">All <span class="badge"></span></a></li>
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
                <label for="editFirstname" class="control-label">First name:</label>
                <input id="editFirstname" type="text" class="form-control" name="firstname" disabled="disabled">
            </div>

            <!-- edit last name -->
            <div id="lastname">
                <label for="editLastname" class="control-label">Last name:</label>
                <input id="editLastname" type="text" class="form-control" name="lastname" disabled="disabled">
            </div>

            <!-- role selection -->
            <div id="roleselect">
                <label for="selectRole" class="control-label">Assign New Role</label>
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
                <button type="submit" class="btn btn-primary btn-lg" disabled="disabled"><span class="fa fa-save"></span></button>
            </div>
        <?=form_close(); ?>
    </div>

</div>