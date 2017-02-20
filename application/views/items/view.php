<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home'); ?>">Home</a></li>
    <li><a href="<?php echo site_url('/items/location/'.$item['location_id']); ?>"><?php echo $item['location']; ?></a></li>
    <li><a href="<?php echo site_url('/items/detail/'.$item['location_id'].'/'.$item['category_id']); ?>"><?php echo $item['category'].' collection'; ?></a></li>
    <li class="active"><?php echo $title; ?></li>
</ul>

<h2><?php echo $item['category']; ?></h2>
<div id="buttons">
    <a href="<?php echo site_url('/items/create/'.$item['id']); ?>" class="btn btn-primary">Edit</a>
    <a id="buttonmodal" class="btn btn-danger">Delete</a>
</div>
<div id="datacontainer" class="clearfix">
    <?php if (!empty($item['image'])): ?>
    <div id="image">
        <img src="<?php echo site_url($item['image']); ?>" alt="item picture">
    </div>
    <?php else: ?>
        <style> #data { width: 100%;  } </style>
    <?php endif; ?>
    <div id="data">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ID:</td>
                    <td><?php echo $item['id']; ?></td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td><?php echo $item['location']; ?></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td><?php echo $item['category']; ?></td>
                </tr>
                <tr>
                    <td>Created on:</td>
                    <td><?php echo $item['created_on']; ?></td>
                </tr>
                <?php foreach (array_keys($item['attributes']) as $attributekey): ?>
                <tr>
                    <td><?php echo $attributekey; ?>:</td>
                    <td><?php echo $item['attributes'][$attributekey]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!--dialog delete-->
<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Delete item</h4>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to delete this item?<br />
                    This action cannot be reversed.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="<?php echo site_url('/items/remove/'.$item['id']); ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>