<div id="locations">
    <div id="locations-cont">
        <table class="table table-striped table-hover" id="table-locations">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations['data'] as $location): ?>
                <?php $id = uniqid(); ?>
                <tr>
                    <td><?=$location['id']; ?></td>
                    <td>
                        <input type="text" class="form-control input-sm" value="<?=$location['name']; ?>" id="<?=$id; ?>">
                    </td>
                    <td class="align-right">
                        <a href="#" data-id="<?=$location['id']; ?>" data-function="save" identifier="<?=$id; ?>" class="btn btn-primary btn-sm">Save</a>
                        <a href="#" data-id="<?=$location['id']; ?>" data-function="delete" identifier="<?=$id; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>