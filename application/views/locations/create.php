<h2><?php echo $title; ?></h2>
<?php echo validation_errors(); ?>

<?php echo form_open('locations/create'); ?>

<label for="name">Name</label>
<input type="input" name="name" /><br />

<input type="submit" name="submit" value="New Location" />

</form>
