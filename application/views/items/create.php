<!-- Validation errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissable alert-danger" id="errordiv">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<!-- Upload success message -->
<div class="alert alert-dismissible alert-success hidden" id="successdiv">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p>
        Upload successful.
    </p>
</div>

<!-- title -->
<h2><?php echo $title; ?></h2>

<!-- Button picture -->
<div id="buttons">
    <?php if (!empty($item)): ?>
        <button type="button" id="buttonmodal" class="btn btn-primary">Picture</button>
    <?php else: ?>
        <button type="button" id="buttonmodal" class="btn btn-primary disabled" data-toggle="tooltip" data-placement="bottom" title="Item must be created first." data-original-title="">Picture</button>
    <?php endif; ?>
</div>
<!-- open form -->
<?php echo form_open('/items/create/', array('id' => 'form')); ?>

<!-- select itemtype -->
<div class="form-group">
<label for="category" class="control-label">Category</label>
<select name="category" class="form-control" id="select">
    <!-- setting values -->
    <?php foreach ($categories as $category): ?>
    <option
            value="<?php echo $category['id']; ?>"
            <?php
                if (!empty($item)) {
                    if ($item['category'] === $category['name']) {
                        echo 'selected="selected"';
                    }
                }
            ?>
    >
        <?php echo $category['name']; ?>
    </option>
    <?php endforeach; ?>
</select>
</div>

<!-- select location -->
<div class="form-group">
<label for="location" class="control-label">Location</label>
<select name="location" class="form-control" id="select">
    <!-- setting values -->
    <?php foreach ($locations as $location): ?>
    <option
            value="<?php echo $location['id']; ?>"
            <?php
                if (!empty($item)) {
                    if ($item['location'] === $location['name']) {
                        echo 'selected="selected"';
                    }
                }
            ?>
    >
        <?php echo $location['name']; ?></option>
    <?php endforeach; ?>
</select>
</div>

<!-- extra fields -->
<div class="form-group" id="extra">
    <?php if (!empty($item['attributes'])): ?>
    <?php foreach (array_keys($item['attributes']) as $attribute): ?>

    <?php $identifier = uniqid(); ?>
    <div class="form-group" id="extra_<?php echo $identifier; ?>">
        <label class="control-label">Attribute</label><br />
        <input type="text" id="focused-input" class="form-control" name="label[]" value="<?php echo $attribute; ?>" required unique="true" identifier="<?php echo $identifier; ?>" />
        <input type="text" id="focused-input" class="form-control" name="value[]" value="<?php echo $item['attributes'][$attribute]; ?>" required />
        <button type="button" class="extra-button-remove btn btn-danger btn-sm" id="extra-button-remove_<?php echo $identifier; ?>">Remove</button>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- add field -->
<div class="form-group" id="extra-button-append">
<button type="button" class="btn btn-primary btn-sm">+</button>
</div>

<!-- hidden field for id-->
<input type="hidden"
       name="id"
       value="<?php
            if (!empty($item)) {
                echo $item['id'];
            }
        ?>"
/>

<!-- submit -->
<div class="form-group" id="buttonsbottom">
    <a href="<?php echo (empty($item) ? site_url('home') : site_url('items/view/'.$item['id'])); ?>" class="btn btn-default">
        <?php echo (empty($item) ? 'Cancel' : 'Back'); ?>
    </a>
    <input type="submit" class="btn btn-primary" id="submit" value="<?php echo (empty($item) ? 'Create' : 'Save'); ?>" />
</div>

</form>

<?php if (!empty($item)): ?>
<!-- Add image -->
<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Upload image</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('upload/do_upload/'.$item['id'], Array('id' => 'dropzone', 'class' => 'dropzone needsclick dz-clickable')); ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>