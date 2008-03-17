<?php
define('ABSPATH', dirname(__FILE__));

include_once(ABSPATH . '/pz-includes/classes.php');
include_once(ABSPATH . '/pz-includes/functions.php');
include_once(ABSPATH . '/pz-includes/version.php');

$settings = new Settings();
define('CHARSET', $settings->charset);
define('MAX_ITEMS', $settings->max_items);
define('DATE_FORMAT', $settings->date_format);
unset($settings);

$messages = array();

?>

