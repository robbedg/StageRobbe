<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="users">


    <!-- left -->
    <div id="left">

        <!-- search -->
        <div id="search">
            <input class="form-control" id="search" type="text" placeholder="Search...">
        </div>

        <!-- filter options -->
        <div id="filter">
            <ul class="nav nav-pills">
                <li class="active"><a href="#">All <span class="badge">42</span></a></li>
                <li><a href="#">Viewers <span class="badge">1</span></a></li>
                <li><a href="#">Editors <span class="badge">3</span></a></li>
                <li><a href="#">Managers <span class="badge">3</span></a></li>
                <li><a href="#">Administrators <span class="badge">3</span></a></li>
            </ul>
        </div>

        <!-- user selection -->
        <div id="userselect">
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <?php $id = uniqid(); ?>
                        <input type="radio" value="<?php echo $user['id']; ?>" name="user" id="<?php echo $id; ?>"/>
                        <label for="<?php echo $id; ?>">
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

        <!-- role selection -->
        <div id="roleselect">
            <label for="select" class="control-label">Assign New Role</label>
            <select class="form-control" id="select" disabled="disabled">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
    </div>

</div>



</div> <!-- remove later -->