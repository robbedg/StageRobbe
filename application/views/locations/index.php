<h2>Locations</h2>

<?php foreach ($locations as $location_item): ?>

    <h3><?php echo $location_item['name']; ?></h3>
    <div class="main">
        <?php echo $location_item['id']; ?>
    </div>
    <p><a href="<?php echo site_url('locations/'.$location_item['id']); ?>">View items</a></p>
<?php endforeach; ?>