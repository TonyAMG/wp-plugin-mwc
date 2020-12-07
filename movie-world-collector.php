<?php

/*
Plugin Name: Movie World Collector
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: This plugin can retrieve various movies information from TMDb API and output it thru shortcodes.
Version: 1.0
Author: Tony
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/



use PluginMaintenance\PluginActivation;
use PluginMaintenance\PluginUninstallation;

require dirname(__FILE__) . '/src/PluginInit.php';
require dirname(__FILE__) . '/src/AdminMenu.php';
require dirname(__FILE__) . '/src/TMDbAPI.php';
require dirname(__FILE__) . '/src/Shortcodes.php';
require dirname(__FILE__) . '/src/PluginStyling.php';
require dirname(__FILE__) . '/src/PluginMaintenance/PluginActivation.php';
require dirname(__FILE__) . '/src/PluginMaintenance/PluginUninstallation.php';


$movie_collector_init = new PluginInit();
$movie_collector_styling = new PluginStyling();
$movie_collector_shotcodes = new Shortcodes();
if ( is_admin() ) $my_settings_page = new AdminMenu();
$movie_collector_activation = new PluginActivation();
$movie_collector_uninstallation = new PluginUninstallation();



####################################
########### TEST BLOCK #############
####################################










