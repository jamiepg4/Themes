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
    }

    public function render_page() {
        ?>
        <!-- Menu -->
        <nav class="menu" id="theMenu">
            <div class="menu-wrap">
                <h1 class="logo"><a href="index.html#home">Minimal</a></h1>
                <i class="icon-remove menu-close"></i>
                <a href="#home" class="smoothScroll">Home</a>
                <a href="#about" class="smoothScroll">About</a>
                <a href="#portfolio" class="smoothScroll">Portfolio</a>
                <a href="#contact" class="smoothScroll">Contact</a>
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
                    <h1>MINIMAL THEME</h1>
                    <br>
                    <h3>Hello, I'm Carlos. I love design.</h3>
                    <br>
                    <br>
                    <div class="col-lg-6 col-lg-offset-3">
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /headerwrap -->

        <!-- ========== ABOUT SECTION ========== -->
        <section id="about" name="about"></section>
        <div id="f">
            <div class="container">
                <div class="row">
                    <h3>ABOUT ME</h3>
                    <p class="centered"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></p>

                    <!-- INTRO INFORMATIO-->
                    <div class="col-lg-6 col-lg-offset-3">
                        <p>A full time theme crafter based in Madrid, Spain. I love designing beautiful, clean and user-friendly interfaces for websites.</p>
                        <p>My passion is turning good ideas and products into eye-catching sites.</p>
                        <p>Sometimes I blog about design and web trends. Also I share links and my thoughts on <a href="http://twitter.com/BlackTie_co">Twitter</a>. Need a free handsome bootstrap theme? <a href="http://blacktie.co">Done!</a></p>
                        <p>I'm available for freelance jobs. Contact me now.</p>
                        <p><button type="button" class="btn btn-warning">I HAVE A FREELANCE JOB</button></p>
                    </div>
                </div>
            </div><!-- /container -->
        </div><!-- /f -->

        <!-- ========== CAROUSEL SECTION ========== -->
        <section id="portfolio" name="portfolio"></section>
        <div id="f">
            <div class="container">
                <div class="row centered">
                    <h3>SOME PROJECTS</h3>
                    <p class="centered"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></p>

                    <div class="col-lg-6 col-lg-offset-3">
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
                    </div><!-- col-lg-8 -->
                </div><!-- row -->
            </div><!-- container -->
        </div>	<!-- f -->

        <!-- ========== CONTACT SECTION ========== -->
        <section id="contact" name="contact"></section>
        <div id="f">
            <div class="container">
                <div class="row">
                    <h3>CONTACT ME</h3>
                    <p class="centered"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></p>

                    <div class="col-lg-6 col-lg-offset-3">
                        <p>Some Avenue, 987<br/>Madrid, Spain<br/>+34 8984-4343</p>
                        <p>iam@awesomemail.com</p>
                        <p><button type="button" class="btn btn-warning">YEAH! CONTACT ME NOW!</button></p>
                    </div>
                </div>
            </div>
        </div>

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