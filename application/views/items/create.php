<h2><?php echo $title; ?></h2>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>

<!-- select itemtype -->
<label for="itemtype">Itemtype</label>
<select name="itemtype">
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
</select><br />

<!-- select location -->
<label for="location">Location</label>
<select name="location">
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
</select><br />

<!-- hidden field for id-->
<input type="hidden"
       name="id"
       value="<?php
            if (!empty($item)) {
                echo $item['id'];
            }
        ?>"
/>

<input type="submit" name="submit" value="Submit" />

</form>
