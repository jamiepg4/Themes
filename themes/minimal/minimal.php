<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2016 PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Minimal Theme
| Filename: minimal.php
| Version: 1.00
| Author: Blacktie.co, PHP-Fusion Inc.
| Site: http://www.blacktie.co
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/


class MinimalTheme {

    public function __construct() {
        add_to_head("
        <!-- Custom styles for this template -->
        <link href='".THEME."assets/css/main.css' rel='stylesheet'>
        <link rel='stylesheet' href='".THEME."assets/css/font-awesome.min.css'>
        <script src='".THEME."assets/js/modernizr.custom.js'></script>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src='".THEME."assets/js/html5shiv.js'></script>
          <script src='".THEME."assets/js/respond.min.js'></script>
        <![endif]-->
        ");

        if (!defined("THEME_BODY")) {
            define("THEME_BODY", "<body data-spy='scroll' data-offset='0' data-target='#theMenu'>");
        }

        if (!defined("THEME_BULLET")) {
            define("THEME_BULLET", "&middot;");
        }

        add_to_footer("
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src='".THEME."assets/js/classie.js'></script>
        <script src='".THEME."assets/js/bootstrap.min.js'></script>
        <script src='".THEME."assets/js/smoothscroll.js'></script>
        <script src='".THEME."assets/js/main.js'></script>
        ");

        self::$start_page = self::generate_currentStart();

    }

    private static $start_page = "";

    private static function generate_currentStart() {
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


    private function display_menu() {



        $data = dbquery_tree_full(DB_SITE_LINKS, "link_id", "link_cat", "WHERE link_position >= 2".(multilang_table("SL") ? "
        AND link_language='".LANGUAGE."'" : "")." AND ".groupaccess('link_visibility')." ORDER BY link_cat, link_order");

        function display_sublinks($data, $id = 0) {

            $res = & $res;

            if (!empty($data)) {

                $cur_is_start = START_PAGE == fusion_get_settings("opening_page") || self::$start_page == fusion_get_settings("opening_page") ? true : false;

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

        if (START_PAGE == fusion_get_settings("opening_page") || self::$start_page == fusion_get_settings("opening_page")) {
            ?>
            <a href="#home" class="smoothScroll">Home</a>
            <a href="#about" class="smoothScroll">About</a>
            <a href="#portfolio" class="smoothScroll">Portfolio</a>
            <a href="#contact" class="smoothScroll">Contact</a>
            <?php
        }
        echo display_sublinks($data);
    }

    private function home_template() {
        ?>
        <!-- ========== ABOUT SECTION ========== -->
        <?php self::opentable("About Me"); ?>
        <p>A full time theme crafter based in Madrid, Spain. I love designing beautiful, clean and user-friendly interfaces for websites.</p>
        <p>My passion is turning good ideas and products into eye-catching sites.</p>
        <p>Sometimes I blog about design and web trends. Also I share links and my thoughts on <a href="http://twitter.com/BlackTie_co">Twitter</a>. Need a free handsome bootstrap theme? <a href="http://blacktie.co">Done!</a></p>
        <p>I'm available for freelance jobs. Contact me now.</p>
        <p><button type="button" class="btn btn-warning">I HAVE A FREELANCE JOB</button></p>
        <?php self::closetable(); ?>

        <!-- ========== CAROUSEL SECTION ========== -->
        <?php self::opentable("Some Projects") ?>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active centered">
                    <img class="img-responsive" src="<?php echo THEME."assets/img/c1.png" ?>" alt="">
                </div>
                <div class="item centered">
                    <img class="img-responsive" src="<?php echo THEME."assets/img/c2.png" ?>" alt="">
                </div>
                <div class="item centered">
                    <img class="img-responsive" src="<?php echo THEME."assets/img/c3.png" ?>" alt="">
                </div>
            </div>
            <br>
            <br>
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>
        </div>
        <?php self::closetable(); ?>

        <!-- ========== CONTACT SECTION ========== -->
        <?php self::opentable("Contact Me"); ?>
            <p>Some Avenue, 987<br/>Madrid, Spain<br/>+34 8984-4343</p>
            <p>iam@awesomemail.com</p>
            <p><button type="button" class="btn btn-warning">YEAH! CONTACT ME NOW!</button></p>
        <?php self::closetable(); ?>
        <?php
    }

    public static function getId($title) {
        return ucfirst(strtolower(str_replace("_", " ", $title)));
    }

    public static function opentable($title, $class = "") {
        $id_label = self::getId($title);
        ?>
        <section id="<?php echo $id_label ?>" name="<?php echo $id_label ?>"></section>
        <div id="f">
            <div class="container">
                <div class="row">
                    <h3><?php echo $title ?></h3>
                    <p class="centered"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></p>
                    <div class="col-lg-6 col-lg-offset-3">
        <?php
    }

    public static function closetable() {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php
    }

    public function render_page() {
        ?>
        <!-- Menu -->
        <nav class="menu" id="theMenu">
            <div class="menu-wrap">
                <h1 class="logo"><a href="index.html#home"><?php echo fusion_get_settings("sitename") ?></a></h1>
                <i class="fa fa-remove menu-close"></i>
                <?php $this->display_menu(); ?>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-dribbble"></i></a>
                <a href="#"><i class="fa fa-envelope"></i></a>
            </div>
            <!-- Menu button -->
            <div id="menuToggle"><i class="fa fa-reorder"></i></div>
        </nav>

        <!-- ========== HEADER SECTION ========== -->
        <section id="home" name="home"></section>
        <div id="headerwrap">
            <div class="container">
                <div class="logo">
                    <img src="<?php echo THEME."assets/img/logo.png" ?>" alt="<?php echo fusion_get_settings("sitename") ?>"/>
                </div>
                <br>
                <div class="row">
                    <h1><?php echo fusion_get_settings("sitename") ?></h1>
                    <br>
                    <h3><?php echo fusion_get_settings("siteintro") ?></h3>
                    <br>
                    <br>
                    <div class="col-lg-6 col-lg-offset-3">
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /headerwrap -->

        <?php self::home_template(); ?>

        <!-- ========== COPYRIGHT SECTION ======== -->
        <section id="copyright">
            <div id="f">
                <div class="container text-center">
                    <?php echo stripslashes(strip_tags(fusion_get_settings("footer"))) ?>
                    <?php echo showcopyright(); ?>
                    <?php echo "<p>Origin theme courtesy of Blacktie.co</p>"; ?>
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
            </div>
        </section>

        <?php
    }

}