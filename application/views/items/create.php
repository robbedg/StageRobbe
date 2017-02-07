<h2><?php echo $title; ?></h2>
<?php echo validation_errors(); ?>

<?php echo form_open(); ?>

<label for="itemtype">Itemtype</label>
<select name="itemtype">
    <?php foreach ($itemtypes as $itemtype): ?>
    <option value="<?php echo $itemtype['id']; ?>"><?php echo $itemtype['name']; ?></option>
    <?php endforeach; ?>
</select><br />

<label for="location">Location</label>
<select name="location">
    <?php foreach ($locations as $location): ?>
    <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
    <?php endforeach; ?>
</select>

<input type="submit" name="submit" value="New Item" />

</form>
