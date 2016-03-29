<?php

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
                    echo "<div class='small m-b-10'>".$data['meta']."</div>";
                    echo "<div class='overflow-hide'>".fusion_first_words($data['content'], 100)."</div>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo $content['norecord'];
            }
            closetable();
        }
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

}

/**
 * Declaring display_home() function again to override the stock home function
 * @param $info
 */
function display_home($info) {
    home_template::display_home($info);
}