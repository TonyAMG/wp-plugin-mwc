<?php


namespace PluginMaintenance;


class PluginUninstallation
{
    public function __construct()
    {
        //register_uninstall_hook( __FILE__, self::MWCUninstall());
    }

    public static function MWCUninstall()
    {
        delete_option('movie_world_collector_plugin_options');
    }
}