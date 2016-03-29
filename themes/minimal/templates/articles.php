<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2016 PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Minimal Theme
| Filename: minimal/templates/articles.php
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

class article_template extends MinimalTheme {

    // Implements the article main template
    public static function render_articles_main($info) {

        $locale = fusion_get_locale();
        ?>
        <section id="home" name="home"></section>
        <div id="headerwrap">
            <div class="container">
                <div class="logo">
                    <img src="<?php echo THEME."assets/img/logo.png" ?>" alt="<?php echo fusion_get_settings("sitename") ?>"/>
                </div>
                <br>
                <div class="row">
                    <h1><?php echo $locale['400'] ?></h1>
                    <br>
                    <div class="col-lg-6 col-lg-offset-3">
                        <?php echo render_breadcrumbs(); ?>
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /headerwrap -->
        <?php

        echo "<!--pre_article_idx-->\n";
        opentable($locale['400']);
        if (isset($info['articles']['item'])) {
            $counter = 0;
            $columns = 2;
            echo "<div class='row m-b-20'>\n";
            foreach ($info['articles']['item'] as $data) {
                if ($counter != 0 && ($counter%$columns == 0)) {
                    echo "</div>\n<div class='row'>\n";
                }
                echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n";
                echo "<!--article_idx_cat_name-->\n";
                echo "<h3>
                        <a href='".INFUSIONS."articles/articles.php?cat_id=".$data['article_cat_id']."'>
					        <strong>".$data['article_cat_name']."</a></strong>
					    </a>
                    </h3>
					<span class='badge'><i class='fa fa-folder'></i> ".$data['article_sub_count']."</span>
					<span class='badge'><i class='fa fa-file-o'></i> ".$data['article_count']."</span>";
                echo ($data['article_cat_description'] != "") ? "<p>".parse_textarea($data['article_cat_description'], FALSE, FALSE)."</p>" : "";
                echo "</div>\n";
                $counter++;
            }
            echo "</div>\n";
        } else {
            echo "<div style='text-align:center'><br />\n".$locale['401']."<br /><br />\n</div>\n";
        }
        closetable();
        echo "<!--sub_article_idx-->\n";
    }

    // Implements the article category page
    public static function render_articles_category($info) {
        $locale = fusion_get_locale();
        ?>
        <section id="home" name="home"></section>
        <div id="headerwrap">
            <div class="container">
                <div class="logo">
                    <img src="<?php echo THEME."assets/img/logo.png" ?>" alt="<?php echo fusion_get_settings("sitename") ?>"/>
                </div>
                <br>
                <div class="row">
                    <h1><?php echo $locale['400'] ?></h1>
                    <br>
                    <div class="col-lg-6 col-lg-offset-3">
                        <?php echo render_breadcrumbs(); ?>
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /headerwrap -->
        <?php
        if (isset($info['articles']['category'])) {
            $data = $info['articles']['category'];
            echo "<!--pre_article_cat-->";
            opentable($locale['400'].": ".$data['article_cat_name']);
            if (!empty($info['articles']['child_categories'])) {
                $counter = 0;
                $columns = 2;

                echo "<aside class='list-group-item m-b-20'>\n";
                echo "<div class='row m-b-20'>\n";

                foreach ($info['articles']['child_categories'] as $catID => $catData) {

                    if ($counter != 0 && ($counter % $columns == 0)) {
                        echo "</div>\n<div class='row'>\n";
                    }

                    echo "<div class='col-xs-12 col-sm-6'>\n";
                    echo "<!--article_idx_cat_name-->\n";
                    echo "<h3 class='display-inline-block m-r-10'>
                        <a href='".INFUSIONS."articles/articles.php?cat_id=".$catData['article_cat_id']."'>
					        <strong>".$catData['article_cat_name']."</a></strong>
					    </a>
                    </h3>
					<span class='badge'><i class='fa fa-folder'></i> ".$catData['article_sub_count']."</span>
					<span class='badge'><i class='fa fa-file-o'></i> ".$catData['article_count']."</span>";
                    echo ($catData['article_cat_description'] != "") ? "<div>".parse_textarea($catData['article_cat_description'])."</div>" : "";
                    echo "</div>\n";
                    $counter++;
                }

                echo "</div>\n";
                echo "</aside>\n";
            }

            if (isset($info['articles']['item'])) {
                foreach ($info['articles']['item'] as $cdata) {
                    echo "<aside>\n";
                    echo "<h4 class='display-inline-block'><strong><a href='".INFUSIONS."articles/articles.php?article_id=".$cdata['article_id']."'>".$cdata['article_subject']."</a></strong></h4> <span class='label label-success m-l-5'>".$cdata['new']."</span><br/>\n";
                    echo preg_replace("/<!?--\s*pagebreak\s*-->/i", "", stripslashes($cdata['article_snippet']))."\n";
                    echo "</aside>\n";
                    echo "<hr/>\n";
                }
                echo !empty($info['page_nav']) ? "<div class='m-t-5'>".$info['page_nav']."</div>\n" : '';
            } else {
                echo "<div class='well text-center'>".$locale['403']."</div>\n";
            }

            echo "<!--sub_article_cat-->";
            closetable();
        }
    }

    // Implements the article template
    public static function render_article($subject, $article, $info) {

        $locale = fusion_get_locale();
        ?>
        <section id="home" name="home"></section>
        <div id="headerwrap">
            <div class="container">
                <div class="logo">
                    <img src="<?php echo THEME."assets/img/logo.png" ?>" alt="<?php echo fusion_get_settings("sitename") ?>"/>
                </div>
                <br>
                <div class="row">
                    <h1><?php echo $locale['400'] ?></h1>
                    <br>
                    <div class="col-lg-6 col-lg-offset-3">
                        <?php echo render_breadcrumbs(); ?>
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /headerwrap -->
        <?php

        $category = "<a href='".INFUSIONS."articles/articles.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>\n";
        $comment = "<a href='".INFUSIONS."articles/articles.php?article_id=".$info['article_id']."#comments'> ".format_word($info['article_comments'], $locale['fmt_comment'])." </a>\n";
        opentable($subject);
        echo "<!--pre_article-->";
        echo "<article>\n";
        echo display_avatar($info, "80px");
        echo "<p>".getuserlevel($info['user_level'])."</p>\n";
        echo "<h3>$subject</h3>";
        echo "<p>".ucfirst($locale['posted'])." ".$locale['by']." <a href='".BASEDIR."profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> <span class='news-date'>".showdate("newsdate", $info['article_date'])."</span> ".$locale['in']." $category ".$locale['and']." $comment</p>\n";

        echo "<a title='".$locale['global_075']."' href='".BASEDIR."print.php?type=A&amp;item_id=".$info['article_id']."'>".$locale['print']."</a>";
        echo !empty($info['edit_link']) ? " ".THEME_BULLET." <a href='".$info['edit_link']."' title='".$locale['global_076']."' />".$locale['edit']."</a>\n" : "";

        echo "<p class='m-t-20'>\n";
        echo ($info['article_breaks'] == "y" ? nl2br($article) : $article)."<br />\n";
        echo "</p>\n";
        echo "</article>";
        echo "<!--sub_article-->";
        echo $info['page_nav'];
        closetable();

        if ($info['article_allow_comments']) {
            showcomments("A", DB_ARTICLES, "article_id", $_GET['article_id'],
                         INFUSIONS."articles/articles.php?article_id=".$_GET['article_id']);
        }

        if ($info['article_allow_ratings']) {
            opentable("Ratings");
            showratings("A", $_GET['article_id'], INFUSIONS."articles/articles.php?article_id=".$_GET['article_id']);
            closetable();
        }

    }
}

// Article Category Page
function render_articles_category($info) {
    article_template::render_articles_category($info);
}

// Article Index
function render_articles_main($info) {
    article_template::render_articles_main($info);
}

// Article Page
function render_article($subject, $article, $info) {
    article_template::render_article($subject, $article, $info);
}