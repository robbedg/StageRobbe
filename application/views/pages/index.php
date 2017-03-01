<!-- Breadcrum -->
<?php if (!empty($breadcrum)): ?>
<ul class="breadcrumb">
    <?php foreach ($breadcrum['items'] as $item): ?>
        <li><a href="<?php echo $item['href']; ?>"><?php echo $item['name']; ?></a></li>
    <?php endforeach; ?>
    <li class="active"><?php echo $breadcrum['active']; ?></li>
</ul>
<?php endif; ?>

<!-- Title -->
<h2><?php echo $title; ?></h2>

<?php //if (!empty($rows)): ?>

<!-- searchbar -->
<div id="searchform">
    <input class="form-control search" type="text" name="search" id="search" placeholder="Search..." />
</div>

<!--table -->
<table class="table table-striped table-hover " id="listingpage">
    <thead>
    <tr>
        <?php foreach ($head as $label): ?>
            <th><?php echo $label; ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <!--ajax -->
    </tbody>
</table>

<!-- Paging -->
    <ul class="pagination">
        <li><a class="basic" href="#" id="previous">&laquo;</a></li>
        <li class="active" id="page_1"><a class="clickable-page" href="#">1</a></li>
        <li><a class="basic" href="#" id="next">&raquo;</a></li>
    </ul>

<?php //else: ?>

<!-- if no data available show message -->
<div class="alert alert-danger">
    <p>No items available.</p>
</div>

<?php //endif; ?>