<h2><?php echo $title; ?></h2>

<table class="table table-striped table-hover ">
    <thread>
        <tr>
            <th>#</th>
            <th>Location</th>
        </tr>
    </thread>
    <tbody>
        <?php foreach ($locations as $location): ?>
            <tr class="clickable-row" data-href="<?php echo site_url('items/location/'.$location['id']); ?>">
                <td><?php echo $location['id'] ?></td>
                <td><?php echo $location['name'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
    <a href="<?php echo site_url('locations/create'); ?>">New Location</a><br />
    <a href="<?php echo site_url('items/create'); ?>">New Item</a><br />
    <a href="<?php echo site_url('itemtypes/create'); ?>">New Itemtype</a>
</p>