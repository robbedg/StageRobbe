<!-- Validation errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissable alert-danger" id="errordiv">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=validation_errors(); ?>
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
<h2><?=$title; ?><?=(!empty($item) ? ': '.$item['data']['id'] : ''); ?></h2>

<!-- Button picture -->
<div id="buttons">
    <?php if (!empty($item)): ?>
        <button type="button" id="buttonmodal" class="btn btn-primary btn-lg"><span class="fa fa-photo"></span></button>
    <?php else: ?>
        <button type="button" id="buttonmodal" class="btn btn-primary btn-lg disabled" data-toggle="tooltip" data-placement="bottom" title="Item must be created first." data-original-title=""><span class="fa fa-photo"></span></button>
    <?php endif; ?>
</div>
<!-- open form -->
<?=form_open('/items/create/', array('id' => 'form')); ?>

<!-- choose name -->
<div class="form-group" id="name-group">
    <label for="name" class="control-label">Name</label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Naam..."
        value="<?=(!empty($item) ? $item['data']['name'] : ''); ?>"
    >
</div>

<!-- select itemtype -->
<div class="form-group">
<label for="category" class="control-label">Category</label>
<select name="category" class="form-control" id="select">
    <!-- setting values -->
    <?php foreach ($categories['data'] as $category): ?>
    <option
            value="<?=$category['id']; ?>"
            <?php
                if (!empty($item)) {
                    if ($item['data']['category'] === $category['name']) {
                        echo 'selected="selected"';
                    }
                }
            ?>
    >
        <?=$category['name']; ?>
    </option>
    <?php endforeach; ?>
</select>
</div>

<!-- select location -->
<div class="form-group">
<label for="location" class="control-label">Location</label>
<select name="location" class="form-control" id="select">
    <!-- setting values -->
    <?php foreach ($locations['data'] as $location): ?>
    <option
            value="<?=$location['id']; ?>"
            <?php
                if (!empty($item)) {
                    if ($item['data']['location'] === $location['name']) {
                        echo 'selected="selected"';
                    }
                }
            ?>
    >
        <?=$location['name']; ?></option>
    <?php endforeach; ?>
</select>
</div>

<!-- extra fields -->
<div class="form-group" id="extra">
    <?php if (!empty($item['data']['attributes'])): ?>
    <?php foreach (array_keys($item['data']['attributes']) as $attribute): ?>

    <?php $identifier = uniqid(); ?>
    <div class="form-group" id="extra_<?=$identifier; ?>">
        <label class="control-label">Attribute</label><br />
        <input type="text" id="focused-input" class="form-control" name="label[]" value="<?=$attribute; ?>" required unique="true" identifier="<?=$identifier; ?>" />
        <input type="text" id="focused-input" class="form-control" name="value[]" value="<?=$item['data']['attributes'][$attribute]; ?>" required />
        <button type="button" class="extra-button-remove btn btn-danger" id="extra-button-remove_<?=$identifier; ?>"><span class="fa fa-close"></span></button>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- add field -->
<div class="form-group" id="extra-button-append">
<button type="button" class="btn btn-success"><span class="fa fa-plus"></span></button>
</div>

<!-- hidden field for id-->
<input type="hidden"
       name="id"
       value="<?php
            if (!empty($item)) {
                echo $item['data']['id'];
            }
        ?>"
/>

<!-- submit -->
<div class="form-group" id="buttonsbottom">
    <a href="<?=(empty($item) ? site_url('home') : site_url('items/view/'.$item['data']['id'])); ?>" class="btn btn-default btn-lg">
        <?=(empty($item) ? '<span class="fa fa-ban"></span>' : '<span class="fa fa-arrow-left"></span>'); ?>
    </a>
    <a href="#" class="btn btn-primary btn-lg" id="submit">
        <?=(empty($item) ? '<span class="fa fa-arrow-right"></span>' : '<span class="fa fa-save"></span>'); ?>
    </a>
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
                <?=form_open_multipart('upload/do_upload/'.$item['data']['id'], Array('id' => 'dropzone', 'class' => 'dropzone needsclick dz-clickable')); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>