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

<?php if (!empty($rows)): ?>

<!-- searchbar -->
<div id="searchform">
    <input class="form-control search" type="text" name="search" id="focusedInput" placeholder="Search..." />
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
    <!--Loop all locations-->
    <?php foreach ($rows as $row): ?>
        <tr id="row_<?php echo uniqid(); ?>" class="clickable-row" data-href="<?php echo $row['href']; ?>" search="<?php echo $row['search']; ?>">
            <?php foreach ($head as $label): ?>
                <td><?php echo $row[$label]; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

<!-- if no data available show message -->
<div class="alert alert-danger">
    <p>No items available.</p>
</div>

<?php endif; ?>