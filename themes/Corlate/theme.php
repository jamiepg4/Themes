<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2016 PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Corlate Theme
| Filename: theme.php
| Version: 1.00
| Author: ShapeBootstrap, PHP-Fusion Inc.
| Site: http://www.shapebootstrap.net
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once THEME."corlate.php";
require_once INCLUDES."theme_functions_include.php";
$theme = new CorlateTheme();

function render_page($license = false) {
    global $theme;
    $theme->render_page();
}

function openside($title) {
}

function closeside() {
}

function opentable($title, $class = FALSE) {
    global $theme;
    $theme::opentable($title, $class);
}

function closetable() {
    global $theme;
    $theme::closetable();
}