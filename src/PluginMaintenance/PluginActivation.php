<?php


namespace PluginMaintenance;


class PluginActivation
{
    public function __construct()
    {
        //register_activation_hook( __FILE__, self::MWCActivate() );
    }


    public static function MWCActivate()
    {
        add_option('movie_world_collector_plugin_options');
    }



}