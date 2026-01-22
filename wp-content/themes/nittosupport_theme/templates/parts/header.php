<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE; ?></title>

    <?php wp_head(); ?>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="<?php echo CSS_URL ?>/bootstrap.css" rel="stylesheet">
<link href="<?php echo CSS_URL ?>/global-style.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri() . '/assets/css/media-queries.css' ?>" rel="stylesheet" type="text/css">
<!-- bootstrap select CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() . '/assets/css/bootstrap-select.min.css' ?>">
<!-- Custom css for listing page  -->
<link href="<?php echo get_template_directory_uri() . '/assets/css/jquery.scrolling-tabs.css' ?>" rel="stylesheet">
<!-- Custom css for listing page  -->
<link href="<?php echo get_template_directory_uri() . '/assets/css/custom.css' ?>" rel="stylesheet">
<link rel="icon" href="https://nittosupport.ca/admin_theme/images/favicon.ico" type="image/x-icon">

<body>
    <header class="headerWide">
        <div class="container header">
            <div class="row">
                <div class="col-xs-7 logo">
                    <a href="https://nittosupport.ca/"><img src="https://nittosupport.ca/assets/img/logo.png" alt="Logo" title="Logo" class="img-responsive"></a>
                </div>
                <div class="col-xs-5">
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

                        <div class="collapse navbar-collapse" id="example-navbar-collapse">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'header_menu',
                                'container'      => false,
                                'menu_class'     => 'nav navbar-nav',
                                'fallback_cb'    => false,
                                'walker'         => new Custom_Walker_Nav_Menu(),
                            ]);
                            ?>
                            <ul class="nav navbar-nav">
                                <li>
                                    <?php $logout_url = wp_logout_url();   ?>
                                    <a href="<?php echo $logout_url;    ?>"><img src="https://toyosupport.ca/wp-content/uploads/2024/04/Logout-button-blk.png" alt="Logout" title="Logout" style="width:24px;"></a>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </div>
        </div>
    </header>