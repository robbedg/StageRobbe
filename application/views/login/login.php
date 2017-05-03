<html>
<head>
    <title>Inventory</title>

    <link rel="stylesheet" href="<?=base_url('css/styles.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('css/login.css'); ?>">
</head>
<body>
    <div id="login-wrapper">
        <h1>Inventory</h1>
        <h2>Log In</h2>
        <?php echo validation_errors(); ?>
        <?php echo form_open('man-login'); ?>
        <label for="username" class="control-label">Username:</label>
        <input type="text" id="username" class="form-control" name="username"/>
        <br/>
        <label for="password" class="control-label">Password</label>
        <input type="password" id="password" class="form-control" name="password"/>
        <br/>
        <input type="submit" value="Login" class="btn btn-primary"/>
        <?php if ($registration): ?>
        <a href="<?=site_url('register'); ?>" class="btn btn-link">Register</a>
        <?php endif; ?>
    </div>
</form>
</body>
<script src="<?=base_url('js/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?=base_url('js/bootstrap.min.js'); ?>"></script>
<script src="<?=base_url('js/scripts.js'); ?>"></script>
</html>