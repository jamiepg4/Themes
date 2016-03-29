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

require_once THEME."templates/home.php";
require_once THEME."templates/articles.php";

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
        <script src='".THEME."assets/js/classie.js'></script>
        <script src='".THEME."assets/js/bootstrap.min.js'></script>
        <script src='".THEME."assets/js/smoothscroll.js'></script>
        <script src='".THEME."assets/js/main.js'></script>
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

        $id_label = self::getId(strip_tags($title));

        self::$menu_cache[$id_label] = $title;

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

    /**
     * Implements closetable() function.
     */
    public static function closetable() {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php
    }

    /**
     * This implements render_page() function as core
     * @param string $license
     */
    public function render_page($license = "") {
        ?>
        <!-- Menu -->
        <nav class="menu" id="theMenu">
            <div class="menu-wrap">
                <h1 class="logo"><a href="index.html#home"><?php echo fusion_get_settings("sitename") ?></a></h1>
                <i class="fa fa-remove menu-close"></i>
                <?php $this->display_menu(); ?>
                <?php if (iMEMBER) : ?>

                    <?php if (iADMIN) :
                        global $aidlink;
                        ?>
                        <a href="<?php echo ADMIN.$aidlink; ?>"><i class="fa fa-lg fa-dashboard"></i></a>

                    <?php endif; ?>

                    <a href="<?php echo FUSION_SELF."?logout=yes" ?>"><i class="fa fa-lg fa-key"></i></a>

                <?php else : ?>

                    <a href="<?php echo BASEDIR."login.php" ?>"><i class="fa fa-lg fa-key"></i></a>

                <?php endif; ?>
                <a href="#"><i class="fa fa-dribbble"></i></a>
                <?php echo hide_email(fusion_get_settings("siteemail"), "<i class=\"fa fa-lg fa-envelope\"></i>") ?>

            </div>
            <!-- Menu button -->
            <div id="menuToggle"><i class="fa fa-reorder"></i></div>
        </nav>

        <!-- ========== BODY SECTION ========== -->
        <?php echo CONTENT; ?>

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