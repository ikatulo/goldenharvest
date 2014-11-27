<!DOCTYPE html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <?php if(golden_get_option('golden_favicon')):?>
    <link rel="shortcut icon" href="<?php echo golden_get_option( 'golden_favicon' ); ?>" type="image/x-icon">
    <link rel="icon" href="<?php echo golden_get_option( 'golden_favicon' ); ?>" type="image/x-icon">



    <?php endif;?>

    <title><?php
        /*
         * Print the <title> tag based on what is being viewed.
         */
        global $page, $paged;

        wp_title( '|', true, 'right' );

        // Add the blog name.
        bloginfo( 'name' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";

        // Add a page number if necessary:
        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf( __( 'Page %s', 'golden' ), max( $paged, $page ) );

        ?>
    </title>

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory');?>/css/redmond/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory');?>/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo bloginfo('template_directory');?>/css/magnific-popup.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo bloginfo('template_directory');?>/js/select2/select2.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo bloginfo('template_directory');?>/css/fullcalendar.min.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo bloginfo('template_directory');?>/css/jquery-ui-timepicker-addon.min.css" />
    <link rel="stylesheet" type="text/css" media="print" href="<?php echo bloginfo('template_directory');?>/css/fullcalendar.print.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />


    <link href="<?php echo bloginfo('template_directory');?>/signature/assets/jquery.signaturepad.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js" type="text/javascript"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js" type="text/javascript"></script>
    <![endif]-->
    <script src="<?php echo get_template_directory_uri();?>/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/jquery.debounce.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/jquery.ui.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/gmap3.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/jquery.ui.custom.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/jquery.form.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/select2/select2.min.js"></script>

    
    <script src="<?php echo get_template_directory_uri();?>/js/fullcalendar.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/jquery-ui-timepicker-addon.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/accounting.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/myjs/myapp.js"></script>

    <script type="text/javascript">
        var themeDir = "<?php echo get_template_directory_uri(); ?>";
        var baseURL = "<?php echo get_bloginfo('url');?>";
    </script>
    
    <?php

    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );

    if(golden_get_option('golden_header_code')):
        echo golden_get_option('golden_header_code');
    endif; 


    wp_head();
    ?>
</head>

<body <?php body_class();?>>
    <?php if(is_user_logged_in()):?>

    <?php 
        global $current_user;
    ?>

    <div id="notification">
        <div id="notif-wrapper" class="container">
            <div class="col-md-2">
                <h1>Golden Harvest</h1>
            </div>
            <div class="col-md-10">
                <p></p>
            </div>
        </div>
    </div>

    <div class="header">

        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="<?php echo bloginfo('url');?>/home">Golden Harvest</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo bloginfo('url');?>/home"><span class="glyphicon glyphicon-dashboard"></span> Home</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> HR Matters <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo bloginfo('url');?>/staff/">All Staff</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/staff/add">Add Staff</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo bloginfo('url');?>/teams/">All Team</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/teams/add">Add Team</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Quotations <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo bloginfo('url');?>/quotations/">All Quotations</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/quotations/add/">Add Quotation</a></li>
                            <!--<li class="divider"></li>
                            <li><a href="<?php echo bloginfo('url');?>/invoices/">Invoice</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/emails/">Send Email</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-calendar"></span> Calendar <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo bloginfo('url');?>/events/">Overview</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/events/add/">Add Event</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Customers <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo bloginfo('url');?>/customers/">All Customers</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/customers/add/">Add Customer</a></li>
                            <!--<li class="divider"></li>
                            <li><a href="<?php echo bloginfo('url');?>/contract-expiration/">Contact Expiration</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Others <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo bloginfo('url');?>/vehicles/">Vehicle</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/vehicles/add/">Add Vehicle</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo bloginfo('url');?>/trackers/">Today's Tracker</a></li>
                            <li><a href="<?php echo bloginfo('url');?>/trackers/histories">Track Histories</a></li>
                            <!--<li class="divider"></li>
                            <li><a href="<?php echo bloginfo('url');?>/settings/">Settings</a></li>-->
                        </ul>
                    </li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo $current_user->first_name.' '.$current_user->last_name;?> <span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo bloginfo('url');?>/profile/">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo wp_logout_url('/');?>">Logout</a></li>
                      </ul>
                    </li>
                  </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
    <?php endif;?>