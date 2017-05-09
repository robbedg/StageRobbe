<!-- validatipon errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissible alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=validation_errors(); ?>
</div>
<?php endif; ?>

<!-- title -->
<h2><?=$title; ?></h2>

<!-- nav tabs-->
<ul class="nav nav-tabs">
    <li class="<?php if ($active === 'general') echo 'active'; ?>"><a href="<?=site_url('admin/general'); ?>">General</a></li>
    <li class="<?php if ($active === 'users') echo 'active'; ?>"><a href="<?=site_url('admin'); ?>">Users</a></li>
    <li class="<?php if ($active === 'reported-items') echo 'active'; ?>"><a href="<?=site_url('admin/reported-items'); ?>">Gerapporteerde Items</a></li>
    <li class="<?php if ($active === 'deleted-items') echo 'active'; ?>"><a href="<?=site_url('admin/deleted-items'); ?>">Deleted Items</a></li>
    <li class="<?php if ($active === 'locations') echo 'active'; ?> <?php if ($database_lock) echo 'disabled'; ?>"><a href="<?= $database_lock ? '#' : site_url('admin/locations'); ?>">Locations</a></li>
    <li class="<?php if ($active === 'categories') echo 'active'; ?> <?php if ($database_lock) echo 'disabled'; ?>"><a href="<?= $database_lock ? '#' : site_url('admin/categories'); ?>">Categories</a></li>
    <li class="<?php if ($active === 'statistics') echo 'active'; ?>"><a href="<?=site_url('admin/statistics'); ?>">Statistics</a></li>
</ul>

<div id="tabs" class="tab-content">