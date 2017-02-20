<html lang="en">
<head>
    <?php if (!empty($styles)): ?>
    <?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="<?php echo $style; ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo site_url('../css/styles.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('../css/bootstrap.min.css'); ?>">
    <title>Inventory</title>
</head>
<nav class="navbar-inverse">
    <div class="container-fluid">
        <!-- mobile menu -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url('home'); ?>">Inventory</a>
        </div>

        <!-- menu items -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">New <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo site_url('items/create'); ?>">New Item</a></li>
                        <li><a href="<?php echo site_url('locations/create'); ?>">New Location</a></li>
                        <li><a href="<?php echo site_url('categories/create'); ?>">New Itemtype</a></li>
                    </ul>
                </li>
            </ul>
        </div>
</nav>
<body>
    <h1><?php echo $title; ?></h1>
    <section id="wrapper">