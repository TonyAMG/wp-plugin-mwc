<?php


class PluginStyling
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'movie_world_collector_style']);
    }

    function movie_world_collector_style()
    {
        wp_enqueue_style(
            'movie-world-collector-theme',
            plugins_url('../assets/css/movie-world-collector-theme.css',__FILE__)
        );
        wp_enqueue_style(
            'slick-slider',
            plugins_url('../assets/slick-slider/slick.css', __FILE__)
        );
        wp_enqueue_style(
            'slick-slider-theme',
            plugins_url('../assets/slick-slider/slick-theme.css', __FILE__)
        );
        wp_enqueue_script(
            'jquery-slick',
            plugins_url('../assets/slick-slider/jquery-2.2.0.min.js', __FILE__)
        );
        wp_enqueue_script(
            'slick-slider',
            plugins_url('../assets/slick-slider/slick.js', __FILE__)
        );
    }
}