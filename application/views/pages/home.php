<h2><?php echo $title; ?></h2>
<div id="buttons">
    <a class="btn btn-primary" href="<?php echo site_url('locations/create'); ?>">New Location</a>
    <a class="btn btn-primary" href="<?php echo site_url('items/create'); ?>">New Item</a>
    <a class="btn btn-primary" href="<?php echo site_url('itemtypes/create'); ?>">New Itemtype</a>
</div>

<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>ID</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <!--Loop all locatons-->
        <?php foreach ($locations as $location): ?>
            <tr class="clickable-row" data-href="<?php echo site_url('items/location/'.$location['id']); ?>">
                <td><?php echo $location['id'] ?></td>
                <td><?php echo $location['name'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>