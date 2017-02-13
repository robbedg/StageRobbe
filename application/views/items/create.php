<h2><?php echo $title; ?></h2>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>

<!-- select itemtype -->
<div class="form-group">
<label for="itemtype" class="control-label">Itemtype</label>
<select name="itemtype" class="form-control" id="select">
    <!-- setting values -->
    <?php foreach ($itemtypes as $itemtype): ?>
    <option
            value="<?php echo $itemtype['id']; ?>"
            <?php
                if (!empty($item)) {
                    if ($item['itemtype'] === $itemtype['name']) {
                        echo 'selected="selected"';
                    }
                }
            ?>
    >
        <?php echo $itemtype['name']; ?>
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
    <div class="form-group" id="extra_<?php echo $attribute; ?>">
        <label class="control-label">Attribute</label><br />
        <input type="text" id="focused-input" class="form-control" name="label_<?php echo $attribute; ?>" value="<?php echo $attribute; ?>" />
        <input type="text" id="focused-input" class="form-control" name="value_<?php echo $attribute; ?>" value="<?php echo $item['attributes'][$attribute]; ?>" />
        <button type="button" class="extra-button-remove btn btn-danger btn-sm" id="extra-button-remove_<?php echo $attribute; ?>">Remove</button>
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
<div class="form-group">
<button type="submit" class="btn btn-primary">Submit</button>
</div>

</form>
