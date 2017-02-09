<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home'); ?>">Home</a></li>
    <li><a href="<?php echo site_url('/items/location/'.$location['id']); ?>"><?php echo $location['name']; ?></a></li>
    <li class="active"><?php echo $title; ?></li>
</ul>

<h2>Items</h2>

<?php if (!empty($items)): ?>
    <table class="table table-striped table-hover ">
        <thead>
        <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Location</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr class="clickable-row" data-href="<?php echo site_url('items/view/'.$item['item_id']); ?>">
                <td><?php echo $item['item_id'] ?></td>
                <td><?php echo $item['itemtype'] ?></td>
                <td><?php echo $item['location'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-danger">
        <p>
            There are no items in this location.
        </p>
    </div>
<?php endif; ?>