<h2><?php echo $item['itemtype']; ?></h2>
<div id="buttons">
    <a href="<?php echo site_url('/items/create/'.$item['id']); ?>" class="btn btn-primary">Edit</a>
    <a id="delete" class="btn btn-danger">Delete</a>
</div>
<div class="jumbotron clearfix" id="datacontainer">
    <div id="labels">
        <p>ID:</p><br />
        <p>Location:</p>
    </div>
    <div id="data">
        <p><?php echo $item['id']; ?></p><br />
        <p><?php echo $item['location']; ?></p>
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