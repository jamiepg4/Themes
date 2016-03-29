<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2016 PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Minimal Theme
| Filename: minimal/templates/home.php
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

/**
 * Class home
 * Extends to use all MinimalTheme properties here
 * This gives your home template a superior boost over the specific theme functions
 */
class home_template extends MinimalTheme {

    /**
     * This implements the same function name and parameters of global/home.php file
     * @param $info
     */
    public static function display_home($info) {
        ?>
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

        <?php
        foreach($info as $db_id => $content) {
            $colwidth = $content['colwidth'];
            opentable($content['blockTitle']);
            if ($colwidth) {
                $classes = "col-xs-".$colwidth." col-sm-".$colwidth." col-md-".$colwidth." col-lg-".$colwidth." content";
                echo "<div class='row'>";
                foreach($content['data'] as $data) {
                    echo "<div class='".$classes." clearfix'>";
                    echo "<h3><a href='".$data['url']."'>".$data['title']."</a></h3>";
                    echo "<p class='small m-b-10'>".$data['meta']."</p>";
                    echo "<p class='overflow-hide'>".fusion_first_words($data['content'], 100)."</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>".$content['norecord']."</p>\n";
            }
            closetable();
        }
        ?>

        <!-- ========== ABOUT SECTION ========== -->
        <?php self::opentable(fusion_get_locale("about_me_title", THEME_LOCALE)); ?>
        <?php echo fusion_get_locale("about_me", THEME_LOCALE) ?>
        <?php self::closetable(); ?>

        <!-- ========== CAROUSEL SECTION ========== -->
        <?php self::opentable(fusion_get_locale("project_title", THEME_LOCALE)) ?>
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
        <?php self::opentable(fusion_get_locale("contact_me_title", THEME_LOCALE)); ?>
        <p><?php echo fusion_get_settings("siteemail") ?></p>
        <p>
            <button type="button" class="btn btn-warning">
                <?php echo fusion_get_locale("contact_me_now", THEME_LOCALE); ?>
            </button>
        </p>
        <?php self::closetable(); ?>

        <?php
    }

}

/**
 * Declaring display_home() function again to override the stock home function
 * @param $info
 */
function display_home($info) {
    home_template::display_home($info);
}