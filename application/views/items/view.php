<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?=site_url('home'); ?>">Home</a></li>
    <li><a href="<?=site_url('/categories/'.$item['data']['location_id']); ?>"><?=$item['data']['location']; ?></a></li>
    <li><a href="<?=site_url('/items/'.$item['data']['location_id'].'/'.$item['data']['category_id']); ?>"><?=$item['data']['category'].' collection'; ?></a></li>
    <li class="active"><?=$title; ?></li>
</ul>

<!-- title -->
<h2><?=$item['data']['category']; ?></h2>

<!-- hidden fields -->
<input type="hidden" id="user_id" value="<?=$_SESSION['id']; ?>">
<input type="hidden" id="item_id" value="<?=$item['data']['id']; ?>">
<input type="hidden" id="role_id" value="<?=$_SESSION['role_id']; ?>">

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
        <!-- qr code -->
        <div id="qrcode">
            <!-- generated qr code -->
        </div>
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
                    <td><?=$item['data']['created_on']; ?></td>
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

<!-- Loan errors -->
<div class="alert alert-dismissible alert-danger hidden" id="loan-errors">
    <button type="button" class="close">&times;</button>
    <ul id="loan-error-list">
        <!-- ajax data -->
    </ul>
</div>

<!-- Loan -->
<div id="loan" class="clearfix">
    <h3>Use item</h3>

    <!-- from -->
    <div class="input-group date" id ="datetimepicker_from">
        <label for="from" class="control-label">From:</label>
        <div class="input-group">
            <input type="text" class="form-control" id="from" />
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>

    <!-- until -->
    <div class="input-group date" id ="datetimepicker_until">
        <label for="until" class="control-label">Until:</label>
        <div class="input-group">
            <input type="text" class="form-control" />
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>

    <!-- button -->
    <div id="loan_button">
        <a href="#" class="btn btn-primary">Loan</a>
    </div>
</div>

<!-- availability -->
<div id="availability" class="clearfix">
    <h3>Availability</h3>
    <a class="btn btn-primary" href="<?=site_url('loans/view/item/'.$item['data']['id']); ?>">View all</a>
    <table class="table table-striped table-hover" id="availability-table">
        <thead>
            <tr>
                <th>UID:</th>
                <th>Name:</th>
                <th>From:</th>
                <th>Until:</th>
            </tr>
        </thead>
        <tbody>
            <!-- AJAX data -->
        </tbody>
    </table>
</div>

<!-- usernotes -->
<div id="usernotes" class="clearfix">
    <h3>Comments</h3>

    <!-- new note -->
    <div id="newnote">
        <div class="form-group">
            <div>
                <textarea class="form-control" id="textArea" name="comment" placeholder="Write comment..."></textarea>
                <span class="help-block" id="count">1024</span>
            </div>
        </div>
        <div class="form-group">
            <div class="button clearfix">
                <button type="button" class="btn btn-primary" id="submit-new-note">Submit</button>
            </div>
        </div>
    </div>

    <!-- existing notes -->
    <div id="notes">
        <!-- existing usernotes AJAX -->
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
                <a href="<?=site_url('/items/remove/'.$item['data']['id']); ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>