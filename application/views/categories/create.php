<!-- validation errors -->
<?php if (!empty(validation_errors())): ?>
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<!-- Title -->
<h2><?php echo $title; ?></h2>

<!-- give name -->
<?php echo form_open('categories/create'); ?>
<div class="form-group">
    <label class="control-label" for="name">Naam</label>
    <input type="input" name="name" class="form-control" id="focusedInput" placeholder="Naam..." />
</div>
<div class=form-group">
    <button type="submit" class="btn btn-primary">Aanmaken</button>
</div>
</form>