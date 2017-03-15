<!-- breadcrum -->
<ul class="breadcrumb">
    <li><a href="<?=site_url('home'); ?>">Home</a></li>
    <li class="active"><?=$title; ?></li>
</ul>

<!-- title -->
<h2><?=$title; ?></h2>

<!-- hidden fields -->
<input type="hidden" id="user_id" value="<?=$user_id; ?>">

<!-- info -->
<div id="info">
    <h3>Info</h3>
    <table class="table table-striped table-hover">
        <tbody>
            <tr>
                <td>Database ID:</td>
                <td id="db_id"></td>
            </tr>
            <tr>
                <td>User ID:</td>
                <td id="uid"></td>
            </tr>
            <tr>
                <td>Firstname:</td>
                <td id="firstname"></td>
            </tr>
            <tr>
                <td>Lastname:</td>
                <td id="lastname"></td>
            </tr>
            <tr>
                <td>Role:</td>
                <td id="role"></td>
            </tr>
        </tbody>
    </table>

</div>

<!-- Active loans -->
<div id="active-loans">
    <h3>Items in use</h3>
    <a href="<?=site_url('loans/view/user/'.$user_id); ?>" class="btn btn-primary" id="view-all">View all</a>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item ID</th>
                <th>(ID) Location</th>
                <th>(ID) Category</th>
                <th>From</th>
                <th>Until</th>
            </tr>
        </thead>
        <tbody>
            <!-- AJAX data -->
        </tbody>
    </table>
</div>