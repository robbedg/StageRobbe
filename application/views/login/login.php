<html>
<head>
    <title>Inventaris</title>

    <link rel="stylesheet" href="<?=base_url('css/styles.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('css/login.css'); ?>">
</head>
<body>
    <div id="login-wrapper">
        <h1>Inventaris</h1>
        <h2>Log In</h2>
        <!-- errors -->
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" id="errors">
            <?php foreach ($errors as $error): ?>
                <p><?=$error; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- auto errors -->
        <?php echo validation_errors(); ?>
        <?php echo form_open('man-login'); ?>
        <label for="username" class="control-label">Gebruikersnaam</label>
        <input type="text" id="username" class="form-control" name="username"/>
        <br/>
        <label for="password" class="control-label">Wachtwoord</label>
        <input type="password" id="password" class="form-control" name="password"/>
        <br/>
        <input type="submit" value="Log In" class="btn btn-primary"/>
        <?php if ($registration): ?>
        <a href="<?=site_url('register'); ?>" class="btn btn-link">Registreer</a>
        <?php endif; ?>
    </div>
</form>
</body>
<script src="<?=base_url('js/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?=base_url('js/bootstrap.min.js'); ?>"></script>
<script src="<?=base_url('js/scripts.js'); ?>"></script>
</html>