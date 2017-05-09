<!-- Breadcrum -->
<?php if (!empty($breadcrum)): ?>
<ul class="breadcrumb">
    <?php foreach ($breadcrum['items'] as $item): ?>
        <li><a href="<?=$item['href']; ?>"><?=$item['name']; ?></a></li>
    <?php endforeach; ?>
    <li class="active"><?=$breadcrum['active']; ?></li>
</ul>
<?php endif; ?>

<!-- Title -->
<?php if (!empty($title)): ?>
<h2><?=$title; ?></h2>
<?php endif; ?>

<!-- hidden fields -->
<?php if (!empty($hiddenfields)): ?>
<?php foreach ($hiddenfields as $field): ?>
    <input type="hidden" id="<?=$field['id']; ?>" value="<?=$field['value']; ?>" />
<?php endforeach; ?>
<?php endif; ?>

<!-- searchbar -->
<div id="searchform">
    <label for="search" class="control-label">Zoek</label>
    <input class="form-control search" type="text" name="search" id="search" placeholder="Zoeken..." />
</div>

<!-- sort on -->
<div id="sort">
    <label for="sortinput" class="control-label">Sorteren</label>
    <select class="form-control" id="sortinput">
        <?php foreach ($head as $label): ?>
        <option value="<?=$label['db']; ?>" order="asc"><?=$label['name']; ?> 0 &#10140; Z</option>
        <option value="<?=$label['db']; ?>" order="desc"><?=$label['name']; ?> Z &#10140; 0</option>
        <?php endforeach; ?>
    </select>
</div>

<!-- amount -->
<div id="amount">
    <label for="amountselect" class="control-label">Aantal</label>
    <input class="form-control" type="number" id="amountselect" min="10" max="100" step="10" value="20">
</div>

<!--table -->
<table class="table table-striped table-hover " id="listingpage">
    <thead>
    <tr>
        <?php foreach ($head as $label): ?>
            <th><?=$label['name']; ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <!--ajax -->
    </tbody>
</table>

<!-- Paging -->
<div id="pagination">
    <ul class="pagination">
        <li><a class="basic" href="#" id="previous">&laquo;</a></li>
        <li class="active" id="page_1"><a class="clickable-page" href="#">1</a></li>
        <li><a class="basic" href="#" id="next">&raquo;</a></li>
    </ul>
</div>