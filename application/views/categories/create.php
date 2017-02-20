<h2><?php echo $title; ?></h2>
<!-- validation errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<!-- give name -->
<?php echo form_open('categories/create'); ?>
<div class="form-group">
    <label class="control-label" for="name">Name</label>
    <input type="input" name="name" class="form-control" id="focusedInput" placeholder="Name..." />
</div>
<div class=form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>


</form>
