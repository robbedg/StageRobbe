<h2><?php echo $item['itemtype']; ?></h2>
<div id="buttons">
    <a href="#" class="btn btn-primary">Edit</a>
    <a href="#" class="btn btn-danger">Delete</a>
</div>
<div class="jumbotron clearfix" id="datacontainer">
    <div id="labels">
        <p>ID:</p><br />
        <p>Location:</p>
    </div>
    <div id="data">
        <p><?php echo $item['id']; ?></p><br />
        <p><?php echo $item['location']; ?></p>
    </div>
</div>