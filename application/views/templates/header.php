<html lang="en">
<head>
    <link rel="shortcut icon" type="image/ico" href="<?=base_url('favicon.ico').'?'.time(); ?>"/>
    <?php if (!empty($styles)): ?>
    <?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="<?=$style; ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="<?=base_url('css/styles.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?=base_url('font-awesome/css/font-awesome.min.css'); ?>">
    <title>Inventory</title>
</head>
<nav class="navbar-inverse">
    <div class="container-fluid">
        <!-- mobile menu -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=site_url('home'); ?>">Inventory</a>
        </div>

        <!-- menu items -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <!-- Left -->
            <ul class="nav navbar-nav navbar-left">
            <!-- create object link -->
            <?php if (authorization_check($this, 2)): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">New <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=site_url('items/create'); ?>">New Item</a></li>
                        <li><a href="<?=site_url('locations/create'); ?>">New Location</a></li>
                        <li><a href="<?=site_url('categories/create'); ?>">New Category</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <!-- admin panel link -->
                <?php if (authorization_check($this, 3)): ?>
                <li>
                    <a href="<?=site_url('admin'); ?>">Admin panel</a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Right -->
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?=site_url('users/'.$_SESSION['id']); ?>"><?=$_SESSION['firstname'].' '.$_SESSION['lastname'];?> <span class="fa fa-user"></span></a>
                </li>
            </ul>

        </div>
</nav>
<body>
    <h1>Inventaris</h1>
    <section id="wrapper">