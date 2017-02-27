<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="users">


    <!-- left -->
    <div id="left">

        <!-- search -->
        <div id="search">
            <input class="form-control" id="searchbox" type="text" placeholder="Search...">
        </div>

        <!-- filter options -->
        <div id="filter">
            <ul class="nav nav-pills">
                <li class="active"><a class="filterlist" href="#" data-id="0">All <span class="badge"><?=count($users); ?></span></a></li>
                <?php foreach ($roles as $role): ?>
                <li><a class="filterlist" href="#" data-id="<?=$role['id']; ?>"><?=$role['name']; ?> <span class="badge"><?=$role['count']; ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- user selection -->
        <div id="userselect">
            <ul>
                <?php foreach ($users as $user): ?>
                    <li class="users" search="<?=$user['firstname'].$user['lastname']; ?>" data-id="<?=$user['role_id']; ?>" data-firstname="<?=$user['firstname']; ?>" data-lastname="<?=$user['lastname']; ?>">
                        <?php $id = uniqid(); ?>
                        <input type="radio" value="<?=$user['id']; ?>" name="user" id="<?=$id; ?>"/>
                        <label for="<?=$id; ?>">
                            <?php echo $user['lastname'].' '.$user['firstname']; ?>
                            <?php switch($user['role_id']):
                                case 1: ?>
                                    <span class="label label-default"><?=$user['role']; ?></span>
                                    <?php break; ?>

                                <?php case 2: ?>
                                    <span class="label label-primary"><?=$user['role']; ?></span>
                                    <?php break; ?>

                                <?php case 3: ?>
                                    <span class="label label-success"><?=$user['role']; ?></span>
                                    <?php break; ?>

                                <?php case 4: ?>
                                    <span class="label label-warning"><?=$user['role']; ?></span>
                                    <?php break; ?>

                                <?php endswitch; ?>
                        </label>
                    </li>
                <?php endforeach; ?>
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
                    <option value="<?=$role['id']; ?>"><?=$role['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- submit button -->
            <div id="submit">
                <button type="submit" class="btn btn-primary" disabled="disabled">Save</button>
            </div>
        <?=form_close(); ?>
    </div>

</div>



</div> <!-- remove later -->