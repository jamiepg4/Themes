<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2016 PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Corlate Theme
| Filename: corlate.php
| Version: 1.00
| Author: ShapeBootstrap, PHP-Fusion Inc.
| Site: http://www.http://shapebootstrap.net
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
//require_once THEME."templates/home.php";
//require_once THEME."templates/articles.php";

class CorlateTheme {

    public function __construct() {
        add_to_head("
        <!-- core CSS -->
        <link href='css/animate.min.css' rel='stylesheet'>
        <link href='css/prettyPhoto.css' rel='stylesheet'>
        <link href='css/main.css' rel='stylesheet'>
        <link href='css/responsive.css' rel='stylesheet'>
        <!--[if lt IE 9]>
        <script src='js/html5shiv.js'></script>
        <script src='js/respond.min.js'></script>
        <![endif]-->
        ");

        if (!defined("THEME_BODY")) {
            define("THEME_BODY", "<body class='homepage'>");
        }

        if (!defined("THEME_LOCALE")) {
            if (file_exists(THEME."locale/".LANGUAGE.".php")) {
                define("THEME_LOCALE", THEME."locale/".LANGUAGE.".php");
            } else {
                define("THEME_LOCALE", THEME."locale/English.php");
            }
        }

        if (!defined("THEME_BULLET")) {
            define("THEME_BULLET", "&middot;");
        }

        add_to_footer("
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src='js/bootstrap.min.js'></script>
        <script src='js/jquery.prettyPhoto.js'></script>
        <script src='js/jquery.isotope.min.js'></script>
        <script src='js/main.js'></script>
        <script src='js/wow.min.js'></script>
        ");

        self::$start_page = self::get_currentStart();
    }
    /**
     * To calculate SEO/Non-SEO current page
     * @var string
     */
    private static $start_page = "";
    private static function get_currentStart() {
        if (empty(self::$start_page)) {
            $pageInfo   = pathinfo($_SERVER['SCRIPT_NAME']);
            $site_path  = ltrim(fusion_get_settings("site_path"), "/");
            self::$start_page = $pageInfo['dirname'] !== "/" ? ltrim($pageInfo['dirname'], "/")."/" : "";
            self::$start_page = str_replace($site_path, "", self::$start_page);
            self::$start_page .= $pageInfo['basename'];
            if (fusion_get_settings("site_seo") && defined('IN_PERMALINK') && !isset($_GET['aid'])) {
                global $filepath;
                self::$start_page = $filepath;
            }
        }
        return self::$start_page;
    }
    /**
     * This displays menu which is custom made by original author
     */
    private function display_menu() {

        $data = dbquery_tree_full(DB_SITE_LINKS, "link_id", "link_cat", "WHERE link_position >= 2".(multilang_table("SL") ? "
        AND link_language='".LANGUAGE."'" : "")." AND ".groupaccess('link_visibility')." ORDER BY link_cat, link_order");
        $start_page = self::$start_page;
        function display_sublinks($data, $start_page, $id = 0) {
            $res = & $res;
            if (!empty($data)) {
                $cur_is_start = (START_PAGE == fusion_get_settings("opening_page") || $start_page == fusion_get_settings("opening_page") ? true : false);
                foreach ($data[$id] as $link_id => $link_data) {
                    $is_home = ($link_data['link_url'] == "index.php" || $link_data['link_url'] == fusion_get_settings("opening_page") ? true : false);
                    if ($link_data['link_name'] != "---" && $link_data['link_name'] != "===" && (
                            ($cur_is_start && !$is_home) || (!$cur_is_start && $is_home)
                        )
                    ) {
                        $link_target = ($link_data['link_window'] == "1" ? " target='_blank'" : "");
                        if (preg_match("!^(ht|f)tp(s)?://!i", $link_data['link_url'])) {
                            $itemlink = $link_data['link_url'];
                        } else {
                            $base = BASEDIR;
                            if (!empty($base) && stristr($link_data['link_url'], BASEDIR)) {
                                $itemlink = $link_data['link_url'];
                            } else {
                                $itemlink = BASEDIR.$link_data['link_url'];
                            }
                        }
                        $res .= "<a href='".$itemlink."'".$link_target.">\n";
                        $res .= (!empty($link_data['link_icon']) ? "<i class='".$link_data['link_icon']."'></i>" : "");
                        $res .= $link_data['link_name']."</a>\n";
                    }
                }
            }
            return $res;
        }
        if (START_PAGE == fusion_get_settings("opening_page") || $start_page == fusion_get_settings("opening_page")) {
            ?>
            <a href="#home" class="smoothScroll">Home</a>
            <a href="#<?php echo self::getId("about me")?>" class="smoothScroll">About</a>
            <a href="#<?php echo self::getId("some projects")?>" class="smoothScroll">Portfolio</a>
            <a href="#<?php echo self::getId("contact me")?>" class="smoothScroll">Contact</a>

            <?php
            if (!empty(self::$menu_cache)) {
                foreach(self::$menu_cache as $anchor => $title) : ?>
                    <a href="#<?php echo $anchor ?>" class="smoothScroll"><span class="fa fa-link fa-fw display-inline"></span> <?php echo $title ?></a>
                <?php endforeach;
            }
            echo display_sublinks($data, $start_page);
        } else {
            echo display_sublinks($data, $start_page);
            if (!empty(self::$menu_cache)) {
                foreach(self::$menu_cache as $anchor => $title) : ?>
                    <a href="#<?php echo $anchor ?>" class="smoothScroll"><span class="fa fa-link fa-fw display-inline"></span> <?php echo $title ?></a>
                <?php endforeach;
            }
        }
    }

    /* Converts $title to html compliant ID */
    public static function getId($title) {
        return strtolower(str_replace(" ", "_", $title));
    }

    /**
     * Implements opentable() function
     * @param        $title
     * @param string $class
     */
    private static $menu_cache = array();
    public static function opentable($title, $class = "") {

    }
    /**
     * Implements closetable() function.
     */
    public static function closetable() {
        ?>

        <?php
    }
    /**
     * This implements render_page() function as core
     * @param string $license
     */
    public function render_page($license = "") {
        ?>


        <header id="header">
            <div class="top-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-xs-4">
                            <div class="top-number"><p><i class="fa fa-phone-square"></i>  +0123 456 70 90</p></div>
                        </div>
                        <div class="col-sm-6 col-xs-8">
                            <div class="social">
                                <ul class="social-share">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                </ul>
                                <div class="search">
                                    <form role="form">
                                        <input type="text" class="search-form" autocomplete="off" placeholder="Search">
                                        <i class="fa fa-search"></i>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.container-->
            </div><!--/.top-bar-->

            <nav class="navbar navbar-inverse" role="banner">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.html"><img src="<?php echo THEME."images/logo.png" ?>" alt="logo"></a>
                    </div>

                    <div class="collapse navbar-collapse navbar-right">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="index.html">Home</a></li>
                            <li><a href="about-us.html">About Us</a></li>
                            <li><a href="services.html">Services</a></li>
                            <li><a href="portfolio.html">Portfolio</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="blog-item.html">Blog Single</a></li>
                                    <li><a href="pricing.html">Pricing</a></li>
                                    <li><a href="404.html">404</a></li>
                                    <li><a href="shortcodes.html">Shortcodes</a></li>
                                </ul>
                            </li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="contact-us.html">Contact</a></li>
                        </ul>
                    </div>
                </div><!--/.container-->
            </nav><!--/nav-->

        </header><!--/header-->

        <section id="main-slider" class="no-margin">
            <div class="carousel slide">
                <ol class="carousel-indicators">
                    <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                    <li data-target="#main-slider" data-slide-to="1"></li>
                    <li data-target="#main-slider" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">

                    <div class="item active" style="background-image: url(<?php echo THEME."images/slider/bg1.jpg" ?>)">
                        <div class="container">
                            <div class="row slide-margin">
                                <div class="col-sm-6">
                                    <div class="carousel-content">
                                        <h1 class="animation animated-item-1">Lorem ipsum dolor sit amet consectetur adipisicing elit</h1>
                                        <h2 class="animation animated-item-2">Accusantium doloremque laudantium totam rem aperiam, eaque ipsa...</h2>
                                        <a class="btn-slide animation animated-item-3" href="#">Read More</a>
                                    </div>
                                </div>

                                <div class="col-sm-6 hidden-xs animation animated-item-4">
                                    <div class="slider-img">
                                        <img src="<?php echo THEME."images/slider/img1.png"?>" class="img-responsive">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!--/.item-->

                    <div class="item" style="background-image: url(<?php echo THEME."images/slider/bg2.jpg" ?>)">
                        <div class="container">
                            <div class="row slide-margin">
                                <div class="col-sm-6">
                                    <div class="carousel-content">
                                        <h1 class="animation animated-item-1">Lorem ipsum dolor sit amet consectetur adipisicing elit</h1>
                                        <h2 class="animation animated-item-2">Accusantium doloremque laudantium totam rem aperiam, eaque ipsa...</h2>
                                        <a class="btn-slide animation animated-item-3" href="#">Read More</a>
                                    </div>
                                </div>

                                <div class="col-sm-6 hidden-xs animation animated-item-4">
                                    <div class="slider-img">
                                        <img src="<?php echo THEME."images/slider/img2.png"?>" class="img-responsive">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!--/.item-->

                    <div class="item" style="background-image: url(<?php echo THEME."images/slider/bg3.jpg" ?>)">
                        <div class="container">
                            <div class="row slide-margin">
                                <div class="col-sm-6">
                                    <div class="carousel-content">
                                        <h1 class="animation animated-item-1">Lorem ipsum dolor sit amet consectetur adipisicing elit</h1>
                                        <h2 class="animation animated-item-2">Accusantium doloremque laudantium totam rem aperiam, eaque ipsa...</h2>
                                        <a class="btn-slide animation animated-item-3" href="#">Read More</a>
                                    </div>
                                </div>
                                <div class="col-sm-6 hidden-xs animation animated-item-4">
                                    <div class="slider-img">
                                        <img src="<?php echo THEME."images/slider/img3.png" ?>" class="img-responsive">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--/.item-->
                </div><!--/.carousel-inner-->
            </div><!--/.carousel-->
            <a class="prev hidden-xs" href="#main-slider" data-slide="prev">
                <i class="fa fa-chevron-left"></i>
            </a>
            <a class="next hidden-xs" href="#main-slider" data-slide="next">
                <i class="fa fa-chevron-right"></i>
            </a>
        </section><!--/#main-slider-->


        <section id="feature" >
            <div class="container">
                <div class="center wow fadeInDown">
                    <h2>Features</h2>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
                </div>

                <div class="row">
                    <div class="features">
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-bullhorn"></i>
                                <h2>Fresh and Clean</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->

                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-comments"></i>
                                <h2>Retina ready</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->

                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-cloud-download"></i>
                                <h2>Easy to customize</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->

                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-leaf"></i>
                                <h2>Adipisicing elit</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->

                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-cogs"></i>
                                <h2>Sed do eiusmod</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->

                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-heart"></i>
                                <h2>Labore et dolore</h2>
                                <h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
                            </div>
                        </div><!--/.col-md-4-->
                    </div><!--/.services-->
                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#feature-->

        <section id="recent-works">
            <div class="container">
                <div class="center wow fadeInDown">
                    <h2>Recent Works</h2>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item1.png" ?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme</a> </h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item1.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item2.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme</a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item2.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item3.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme </a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item3.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item4.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme </a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item4.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item5.png"?>"  alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme</a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item5.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item6.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme </a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item6.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item7.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme </a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item7.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="recent-work-wrap">
                            <img class="img-responsive" src="<?php echo THEME."images/portfolio/recent/item8.png"?>" alt="">
                            <div class="overlay">
                                <div class="recent-work-inner">
                                    <h3><a href="#">Business theme </a></h3>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
                                    <a class="preview" href="<?php echo THEME."images/portfolio/full/item8.png"?>" rel="prettyPhoto"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#recent-works-->

        <section id="content">
            <?php echo CONTENT; ?>
        </section>


        <section id="services" class="service-item">
            <div class="container">
                <div class="center wow fadeInDown">
                    <h2>Our Service</h2>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
                </div>

                <div class="row">

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services1.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services2.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services3.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services4.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services5.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="media services-wrap wow fadeInDown">
                            <div class="pull-left">
                                <img class="img-responsive" src="<?php echo THEME."images/services/services6.png"?>">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading">SEO Marketing</h3>
                                <p>Lorem ipsum dolor sit ame consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>
                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#services-->

        <section id="middle">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 wow fadeInDown">
                        <div class="skill">
                            <h2>Our Skills</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                            <div class="progress-wrap">
                                <h3>Graphic Design</h3>
                                <div class="progress">
                                    <div class="progress-bar  color1" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                                        <span class="bar-width">85%</span>
                                    </div>

                                </div>
                            </div>

                            <div class="progress-wrap">
                                <h3>HTML</h3>
                                <div class="progress">
                                    <div class="progress-bar color2" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 95%">
                                        <span class="bar-width">95%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-wrap">
                                <h3>CSS</h3>
                                <div class="progress">
                                    <div class="progress-bar color3" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        <span class="bar-width">80%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-wrap">
                                <h3>Wordpress</h3>
                                <div class="progress">
                                    <div class="progress-bar color4" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
                                        <span class="bar-width">90%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!--/.col-sm-6-->

                    <div class="col-sm-6 wow fadeInDown">
                        <div class="accordion">
                            <h2>Why People like us?</h2>
                            <div class="panel-group" id="accordion1">
                                <div class="panel panel-default">
                                    <div class="panel-heading active">
                                        <h3 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1">
                                                Lorem ipsum dolor sit amet
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="collapseOne1" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="media accordion-inner">
                                                <div class="pull-left">
                                                    <img class="img-responsive" src="<?php echo THEME."images/accordion1.png"?>">
                                                </div>
                                                <div class="media-body">
                                                    <h4>Adipisicing elit</h4>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo1">
                                                Lorem ipsum dolor sit amet
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="collapseTwo1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree1">
                                                Lorem ipsum dolor sit amet
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="collapseThree1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseFour1">
                                                Lorem ipsum dolor sit amet
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="collapseFour1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
                                        </div>
                                    </div>
                                </div>
                            </div><!--/#accordion1-->
                        </div>
                    </div>

                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#middle-->

        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 wow fadeInDown">
                        <div class="tab-wrap">
                            <div class="media">
                                <div class="parrent pull-left">
                                    <ul class="nav nav-tabs nav-stacked">
                                        <li class=""><a href="#tab1" data-toggle="tab" class="analistic-01">Responsive Web Design</a></li>
                                        <li class="active"><a href="#tab2" data-toggle="tab" class="analistic-02">Premium Plugin Included</a></li>
                                        <li class=""><a href="#tab3" data-toggle="tab" class="tehnical">Predefine Layout</a></li>
                                        <li class=""><a href="#tab4" data-toggle="tab" class="tehnical">Our Philosopy</a></li>
                                        <li class=""><a href="#tab5" data-toggle="tab" class="tehnical">What We Do?</a></li>
                                    </ul>
                                </div>

                                <div class="parrent media-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade" id="tab1">
                                            <div class="media">
                                                <div class="pull-left">
                                                    <img class="img-responsive" src="<?php echo THEME."images/tab2.png"?>">
                                                </div>
                                                <div class="media-body">
                                                    <h2>Adipisicing elit</h2>
                                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade active in" id="tab2">
                                            <div class="media">
                                                <div class="pull-left">
                                                    <img class="img-responsive" src="<?php echo THEME."images/tab1.png"?>">
                                                </div>
                                                <div class="media-body">
                                                    <h2>Adipisicing elit</h2>
                                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tab3">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.</p>
                                        </div>

                                        <div class="tab-pane fade" id="tab4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words</p>
                                        </div>

                                        <div class="tab-pane fade" id="tab5">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures,</p>
                                        </div>
                                    </div> <!--/.tab-content-->
                                </div> <!--/.media-body-->
                            </div> <!--/.media-->
                        </div><!--/.tab-wrap-->
                    </div><!--/.col-sm-6-->

                    <div class="col-xs-12 col-sm-4 wow fadeInDown">
                        <div class="testimonial">
                            <h2>Testimonials</h2>
                            <div class="media testimonial-inner">
                                <div class="pull-left">
                                    <img class="img-responsive img-circle" src="<?php echo THEME."images/testimonials1.png"?>">
                                </div>
                                <div class="media-body">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</p>
                                    <span><strong>-John Doe/</strong> Director of corlate.com</span>
                                </div>
                            </div>

                            <div class="media testimonial-inner">
                                <div class="pull-left">
                                    <img class="img-responsive img-circle" src="<?php echo THEME."images/testimonials1.png"?>">
                                </div>
                                <div class="media-body">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</p>
                                    <span><strong>-John Doe/</strong> Director of corlate.com</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#content-->

        <section id="partner">
            <div class="container">
                <div class="center wow fadeInDown">
                    <h2>Our Partners</h2>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
                </div>

                <div class="partners">
                    <ul>
                        <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" src="<?php echo THEME."images/partners/partner1.png"?>"></a></li>
                        <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms" src="<?php echo THEME."images/partners/partner2.png"?>"></a></li>
                        <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms" src="<?php echo THEME."images/partners/partner3.png"?>"></a></li>
                        <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1200ms" src="<?php echo THEME."images/partners/partner4.png"?>"></a></li>
                        <li> <a href="#"><img class="img-responsive wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1500ms" src="<?php echo THEME."images/partners/partner5.png"?>"></a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </section><!--/#partner-->

        <section id="conatcat-info">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="media contact-info wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="pull-left">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="media-body">
                                <h2>Have a question or need a custom quote?</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation +0123 456 70 80</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.container-->
        </section><!--/#conatcat-info-->

        <section id="bottom">
            <div class="container wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="widget">
                            <h3>Company</h3>
                            <ul>
                                <li><a href="#">About us</a></li>
                                <li><a href="#">We are hiring</a></li>
                                <li><a href="#">Meet the team</a></li>
                                <li><a href="#">Copyright</a></li>
                                <li><a href="#">Terms of use</a></li>
                                <li><a href="#">Privacy policy</a></li>
                                <li><a href="#">Contact us</a></li>
                            </ul>
                        </div>
                    </div><!--/.col-md-3-->

                    <div class="col-md-3 col-sm-6">
                        <div class="widget">
                            <h3>Support</h3>
                            <ul>
                                <li><a href="#">Faq</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Forum</a></li>
                                <li><a href="#">Documentation</a></li>
                                <li><a href="#">Refund policy</a></li>
                                <li><a href="#">Ticket system</a></li>
                                <li><a href="#">Billing system</a></li>
                            </ul>
                        </div>
                    </div><!--/.col-md-3-->

                    <div class="col-md-3 col-sm-6">
                        <div class="widget">
                            <h3>Developers</h3>
                            <ul>
                                <li><a href="#">Web Development</a></li>
                                <li><a href="#">SEO Marketing</a></li>
                                <li><a href="#">Theme</a></li>
                                <li><a href="#">Development</a></li>
                                <li><a href="#">Email Marketing</a></li>
                                <li><a href="#">Plugin Development</a></li>
                                <li><a href="#">Article Writing</a></li>
                            </ul>
                        </div>
                    </div><!--/.col-md-3-->

                    <div class="col-md-3 col-sm-6">
                        <div class="widget">
                            <h3>Our Partners</h3>
                            <ul>
                                <li><a href="#">Adipisicing Elit</a></li>
                                <li><a href="#">Eiusmod</a></li>
                                <li><a href="#">Tempor</a></li>
                                <li><a href="#">Veniam</a></li>
                                <li><a href="#">Exercitation</a></li>
                                <li><a href="#">Ullamco</a></li>
                                <li><a href="#">Laboris</a></li>
                            </ul>
                        </div>
                    </div><!--/.col-md-3-->
                </div>
            </div>
        </section><!--/#bottom-->

        <footer id="footer" class="midnight-blue">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        &copy; 2013 <a target="_blank" href="http://shapebootstrap.net/" title="Free PHP-Fusion Themes and HTML templates">ShapeBootstrap</a>. All Rights Reserved.
                        <p>
                            <?php echo stripslashes(strip_tags(fusion_get_settings("footer"))) ?>
                            <?php echo showcopyright(); ?>
                        </p>
                        <?php
                        if (fusion_get_settings("visitorcounter_enabled")) {
                            echo "<p>".showcounter()."</p>\n";
                        }
                        if (fusion_get_settings("rendertime_enabled") == '1' || fusion_get_settings("rendertime_enabled") == '2') {
                            // Make showing of queries and memory usage separate settings
                            echo showrendertime();
                            echo showMemoryUsage();
                        }
                        $footer_errors = showFooterErrors();
                        if (!empty($footer_errors)) {
                            echo "<div>\n".showFooterErrors()."</div>\n";
                        }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <ul class="pull-right">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Faq</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer><!--/#footer-->
        <?php

    }
}