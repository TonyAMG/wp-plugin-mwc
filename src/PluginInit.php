<?php


class PluginInit
{
    public function __construct()
    {
        add_filter( 'page_template', [$this, 'get_custom_post_type_template'] );
        add_action( 'init', [$this, 'mwc_rewrite_rules']);
        add_filter( 'query_vars', [$this, 'movie_desc_page_query_vars']);
        add_action( 'wp_loaded', [$this, 'createMovieDescriptionPage']);
        add_action( 'get_footer', [$this, 'addMWCSliderOnBottom']);
    }

    public function get_custom_post_type_template( $single_template )
    {
        global $post;

        if ( 'mwc-movie-description' === $post->post_name ) {
            //var_dump($post);
            $single_template = dirname( __FILE__ ) . '/../template/page-movie.php';
        }

        return $single_template;
    }

    public function mwc_rewrite_rules()
    {
        add_rewrite_rule(
            'movie/(.+)/?',
            'index.php?pagename=mwc-movie-description&movie_id=$matches[1]',
            'top'
        );
    }

    public function movie_desc_page_query_vars( $query_vars )
    {
        $query_vars[] = 'movie_id';
        return $query_vars;
    }

    public function createMovieDescriptionPage()
    {
        require ABSPATH . 'wp-admin/includes/post.php';

        if (post_exists( 'mwc-movie-description-page') === 0 ) {
            $my_post = array(
                'post_title' => 'mwc-movie-description-page',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'page',
                'post_name' => 'mwc-movie-description'
            );

            wp_insert_post($my_post);
        }
    }

    public function addMWCSliderOnBottom()
    {
        $plugin_option = get_option( 'mwc_plugin_options' );
        if ($plugin_option && $plugin_option['show_on_pages'] === 'bottom')
            echo do_shortcode('[movie_world_collector]');
    }


}