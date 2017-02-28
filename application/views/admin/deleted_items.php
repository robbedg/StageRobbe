<div class="tab-pane fade" id="deleted-items">
    <div id="table">
        <table class="table table-striped table-hover" id="table-deleted">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Created on</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deleted_items as $item): ?>
                <tr>
                    <td><?=$item['id']; ?></td>
                    <td><?=$item['location']; ?></td>
                    <td><?=$item['category']; ?></td>
                    <td><?=(new DateTime($item['created_on']))->format('d/m/Y H:i'); ?></td>
                    <td class="align-right">
                        <a href="#" class="btn btn-success btn-xs">Restore</a>
                        <a href="#" class="btn btn-danger btn-xs">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>