<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?=site_url('home'); ?>"><span class="fa fa-home"></span></a></li>
    <li><a href="<?=site_url('/categories/'.$item['data']['location_id']); ?>">Locatie: <?=$item['data']['location']; ?></a></li>
    <li><a href="<?=site_url('/items/'.$item['data']['location_id'].'/'.$item['data']['category_id']); ?>"><?='Categorie: '.$item['data']['category']; ?></a></li>
    <li class="active"><?=$title; ?></li>
</ul>

<!-- title -->
<h2><?=$item['data']['name']; ?></h2>

<!-- hidden fields -->
<input type="hidden" id="user_id" value="<?=$_SESSION['id']; ?>">
<input type="hidden" id="role_id" value="<?=$_SESSION['role_id']; ?>">

<input type="hidden" id="issue" value="<?=$item['data']['issue']; ?>">
<input type="hidden" id="item_id" value="<?=$item['data']['id']; ?>">
<input type="hidden" id="location" value="<?=$item['data']['location']; ?>">
<input type="hidden" id="category" value="<?=$item['data']['category']; ?>">

<!-- buttons -->
<div id="buttons">
    <?php if ($item['data']['issue'] === '0' || authorization_check($this,2)): ?>
    <a href="#" class="btn btn-warning" id="open-report" data-toggle="tooltip" data-original-title="Rapporteren"><span class="fa fa-flag"></span></a>
    <?php endif; ?>
    <a href="#" class="btn btn-primary" id="print-label" data-toggle="tooltip" data-original-title="Printen"><span class="fa fa-print"></span></a>
    <?php if (authorization_check($this, 2) && !$database_lock): ?>
    <a href="<?=site_url('/items/create/'.$item['data']['id']); ?>" class="btn btn-primary" data-toggle="tooltip" data-original-title="Bewerken"><span class="fa fa-edit"></span></a>
    <a id="buttonmodal" class="btn btn-danger" data-toggle="tooltip" data-original-title="Verwijderen"><span class="fa fa-trash"></span></a>
    <?php endif; ?>
</div>

<!-- data -->
<div id="datacontainer" class="clearfix">
    <?php if (!empty($item['data']['image'])): ?>
    <div id="image">
        <img src="<?=site_url($item['data']['image']); ?>" alt="item picture">
    </div>
    <?php else: ?>
        <style> #data { width: 100%;  } </style>
    <?php endif; ?>
    <div id="data">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Eigenschap</th>
                    <th>Waarde</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>QR-code:</td>
                    <td>
                        <!-- qr code -->
                        <div id="qrcode">
                            <!-- generated qr code -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ID:</td>
                    <td><?=$item['data']['id']; ?></td>
                </tr>
                <tr>
                    <td>Naam:</td>
                    <td id="item-name"><?=$item['data']['name']; ?></td>
                </tr>
                <tr>
                    <td>Locatie:</td>
                    <td><?=$item['data']['location']; ?></td>
                </tr>
                <tr>
                    <td>Categorie:</td>
                    <td><?=$item['data']['category']; ?></td>
                </tr>
                <tr>
                    <td>Aangemaakt op:</td>
                    <td><?=$item['data']['created_on']; ?></td>
                </tr>
                <?php foreach (array_keys($item['data']['attributes']) as $attributekey): ?>
                <tr>
                    <td><?=$attributekey; ?>:</td>
                    <td><?=$item['data']['attributes'][$attributekey]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Loan errors -->
<div class="alert alert-dismissible alert-danger hidden" id="loan-errors">
    <button type="button" class="close">&times;</button>
    <ul id="loan-error-list">
        <!-- ajax data -->
    </ul>
</div>

<!-- Loan -->
<div id="loan" class="clearfix">
    <h3>Reserveer Item</h3>

    <!-- from -->
    <div class="input-group date" id ="datetimepicker_from">
        <label for="from" class="control-label">Van</label>
        <div class="input-group">
            <input type="text" class="form-control" id="from" />
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>

    <!-- until -->
    <div class="input-group date" id ="datetimepicker_until">
        <label for="until" class="control-label">Tot</label>
        <div class="input-group">
            <input type="text" class="form-control" />
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>

    <!-- button -->
    <div id="loan_button">
        <a href="#" class="btn btn-primary" data-toggle="tooltip" data-original-title="Reserveer"><span class="fa fa-arrow-right"></span></a>
    </div>
</div>

<!-- availability -->
<div id="availability" class="clearfix">
    <h3>Beschikbaarheid</h3>
    <a class="btn btn-primary" data-toggle="tooltip" data-original-title="Volledige Geschiedenis" href="<?=site_url('loans/view/item/'.$item['data']['id']); ?>"><span class="fa fa-list-alt"></span></a>
    <!-- timeline -->
    <div id="timeline">
        <!-- Chart -->
    </div>
    <!-- Selected -->
    <div id="selected-timeline">
        <p>
            <!-- data -->
        </p>
    </div>
</div>

<!-- usernotes -->
<div id="usernotes" class="clearfix">
    <h3>Reacties</h3>

    <!-- new note -->
    <div id="newnote">
        <div class="form-group">
            <div>
                <textarea class="form-control" id="textArea" name="comment" placeholder="Schrijf reactie..."></textarea>
                <span class="help-block" id="count">1024</span>
            </div>
        </div>
        <div class="form-group">
            <div class="button clearfix">
                <button type="button" class="btn btn-primary" id="submit-new-note" data-toggle="tooltip" data-original-title="Verzenden"><span class="fa fa-send"></span></button>
            </div>
        </div>
    </div>

    <!-- existing notes -->
    <div id="notes">
        <!-- existing usernotes AJAX -->
    </div>
</div>

<!--dialog delete-->
<div class="modal" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Verwijder Item</h4>
            </div>
            <div class="modal-body">
                <p>Wilt u dit item verwijderen?</p>
                <p>Deze actie kan enkel ongedaan gamaakt worden door een administrator.</p>
            </div>
            <div class="modal-footer">
                <a href="<?=site_url('/items/remove/'.$item['data']['id']); ?>" class="btn btn-danger"><span class="fa fa-trash"></span></a>
            </div>
        </div>
    </div>
</div>

<!--dialog report -->
<div class="modal" id="report-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Rapporteer Probleem</h4>
            </div>
            <div class="modal-body">
                <?php if ($item['data']['issue'] === '0'): ?>
                <p>Wilt u dit item rapporteren?</p>
                <p>Laat ook een reactie achter met uitleg over het probleem.</p>
                <?php else: ?>
                <p>Is het probleem opgelost?</p>
                <p>Het item wordt terug normaal weergeven.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?php if ($item['data']['issue'] ==='0'): ?>
                <a href="#" class="btn btn-warning" id="report"><span class="fa fa-flag"></span></a>
                <?php else: ?>
                <a href="#" class="btn btn-success" id="report"><span class="fa fa-check"></span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>