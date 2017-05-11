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
    <h2>Registreer</h2>
    <?php echo validation_errors(); ?>
    <?php echo form_open('register'); ?>
    <label for="username" class="control-label">Gebruikersnaam</label>
    <input type="text" id="username" class="form-control" name="username" />
    <br />
    <label for="firstname" class="control-label">Voornaam</label>
    <input type="text" id="firstname" class="form-control" name="firstname" />
    <br />
    <label for="lastname" class="control-label">Familienaam</label>
    <input type="text" id="lastname" class="form-control" name="lastname" />
    <br />
    <label for="password" class="control-label">Wachtwoord</label>
    <input type="password" id="password" class="form-control" name="password" />
    <br />
    <input type="submit" value="Registreer" class="btn btn-primary" />
    <a href="<?=site_url('man-login'); ?>" class="btn btn-link">Log In</a>
</div>
</form>
</body>
<script src="<?=base_url('js/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?=base_url('js/bootstrap.min.js'); ?>"></script>
<script src="<?=base_url('js/scripts.js'); ?>"></script>
</html>