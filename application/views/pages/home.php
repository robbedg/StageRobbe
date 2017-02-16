<h2><?php echo $title; ?></h2>

<!-- searchbar -->
<div id="searchform">
    <input class="form-control search" type="text" name="search" id="focusedInput" placeholder="Search..." />
</div>

<!--table -->
<table class="table table-striped table-hover " id="listingpage">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <!--Loop all locatons-->
        <?php foreach ($locations as $location): ?>
            <tr id="row_<?php echo $location['name']; ?>" class="clickable-row" data-href="<?php echo site_url('items/location/'.$location['id']); ?>">
                <td><?php echo $location['id'] ?></td>
                <td><?php echo $location['name'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>