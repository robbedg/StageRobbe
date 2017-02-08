<h2>Items</h2>

<table class="table table-striped table-hover ">
    <thread>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Location</th>
        </tr>
    </thread>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr class="clickable-row" data-href="<?php echo site_url('items/'.$item['id']); ?>">
            <td><?php echo $item['id'] ?></td>
            <td><?php echo $item['itemtype'] ?></td>
            <td><?php echo $item['location'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
