<h2><?php echo $title; ?></h2>
<p>
    <?php echo validation_errors(); ?>
</p>

<?php echo form_open(); ?>

<label for="location">Location</label>
<select name="location">
    <?php foreach ($locations as $location): ?>
    <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
    <?php endforeach; ?>
</select>

<input type="submit" name="submit" value="Select location" />

</form>

<p>
    <a href="<?php echo site_url('locations/create'); ?>">New Location</a><br />
    <a href="<?php echo site_url('items/create'); ?>">New Item</a><br />
    <a href="<?php echo site_url('itemtypes/create'); ?>">New Itemtype</a>
</p>