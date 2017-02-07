<h2>Items</h2>

<?php foreach ($items as $item): ?>

    <h3><?php echo $item['itemtype']; ?></h3>
    <div class="main">
        <p>
            Location:<br />
            <?php echo($item['location']); ?>
        </p>
    </div>
    <p><a href="<?php echo site_url('items/'.$item['id']); ?>">View item</a></p>
<?php endforeach; ?>