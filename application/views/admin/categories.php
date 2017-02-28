<div class="tab-pane fade" id="categories">
    <div id="categories-cont">
        <table class="table table-striped table-hover" id="table-categories">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <?php $id = uniqid(); ?>
                <tr>
                    <td><?=$category['id']; ?></td>
                    <td>
                        <input type="text" class="form-control input-sm" value="<?=$category['name']; ?>" id="<?=$id; ?>">
                    </td>
                    <td class="align-right">
                        <a href="#" data-id="<?=$category['id']; ?>" data-function="save" identifier="<?=$id; ?>" class="btn btn-primary btn-sm">Save</a>
                        <a href="#" data-id="<?=$category['id']; ?>" data-function="delete" identifier="<?=$id; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>