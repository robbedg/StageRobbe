<!-- validatipon errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissible alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=validation_errors(); ?>
</div>
<?php endif; ?>

<!-- title -->
<h2><?=$title; ?></h2>

<!-- navs tabs-->
<ul class="nav nav-tabs">
    <li class="active"><a href="#users" data-toggle="tab">Users</a></li>
    <li class=""><a href="#deleted-items" data-toggle="tab">Deleted items</a></li>
    <li class=""><a href="#locations" data-toggle="tab">Locations</a></li>
    <li class=""><a href="#categories" data-toggle="tab">Categories</a></li>
</ul>

<div id="tabs" class="tab-content">