<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?=site_url('home'); ?>">Home</a></li>
    <li><a href="<?=site_url('/categories/'.$item['data']['location_id']); ?>"><?=$item['data']['location']; ?></a></li>
    <li><a href="<?=site_url('/items/'.$item['data']['location_id'].'/'.$item['data']['category_id']); ?>"><?=$item['data']['category'].' collection'; ?></a></li>
    <li class="active"><?php echo $title; ?></li>
</ul>

<h2><?=$item['data']['category']; ?></h2>

<!-- buttons -->
<?php if (authorization_check($this, 2)): ?>
<div id="buttons">
    <a href="<?=site_url('/items/create/'.$item['data']['id']); ?>" class="btn btn-primary">Edit</a>
    <a id="buttonmodal" class="btn btn-danger">Delete</a>
</div>
<?php endif; ?>

<!-- data -->
<div id="datacontainer" class="clearfix">
    <?php if (!empty($item['data']['image'])): ?>
    <div id="image">
        <img src="<?=site_url($item['data']['image']); ?>" alt="item picture">
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
                    <td><?=$item['data']['id']; ?></td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td><?=$item['data']['location']; ?></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td><?=$item['data']['category']; ?></td>
                </tr>
                <tr>
                    <td>Created on:</td>
                    <td><?=(new DateTime($item['data']['created_on']))->format('d/m/Y H:i'); ?></td>
                </tr>
                <?php foreach (array_keys($item['data']['attributes']) as $attributekey): ?>
                <tr>
                    <td><?=$attributekey; ?>:</td>
                    <td><?=$item['data']['attributes'][$attributekey]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- usernotes -->
<div id="usernotes" class="clearfix">
    <h3>Comments</h3>

    <!-- new note -->
    <?=form_open('items/view/'.$item['data']['id']); ?>
    <input type="hidden" name="item_id" value="<?=$item['data']['id']; ?>">
    <div class="form-group">
        <div>
            <textarea class="form-control" id="textArea" name="comment" placeholder="Write comment..."></textarea>
            <span class="help-block" id="count">1024</span>
        </div>
    </div>
    <div class="form-group">
        <div class="button clearfix">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

    <?=form_close(); ?>

    <!-- existing usernotes -->
    <?php if (!empty($usernotes)): ?>
    <?php foreach ($usernotes as $usernote): ?>
    <div id="<?=uniqid(); ?>" class="note">
        <strong class="username"><?=strtoupper($usernote['firstname']).' '.strtoupper($usernote['lastname']); ?></strong>

        <!-- only user can delete comment -->
        <?php if (($_SESSION['id'] === $usernote['user_id']) || authorization_check($this, 3)): ?>
        <span class="links">
            <a href="<?=site_url('usernotes/remove/'.$item['data']['id'].'/'.$usernote['id']); ?>">Delete</a>
        </span>
        <?php endif; ?>

        <p>
            <?=$usernote['text']; ?>
        </p>
        <span class="date"><?=(new DateTime($usernote['created_on']))->format('d/m/Y H:i'); ?></span>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
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
                <a href="<?=site_url('/items/remove/'.$item['data']['id']); ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>