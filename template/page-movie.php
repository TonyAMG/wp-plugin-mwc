<?php

$query_var_movie_id = get_query_var('movie_id');
if (!empty($query_var_movie_id) && preg_match('/^\d{1,7}$/', $query_var_movie_id)) {
    get_header();
    echo do_shortcode(
        '[movie_world_collector type="movie_details" movie_id="'.get_query_var('movie_id').'"]'
    );
    get_footer();
} else {
    header('Location: '.get_option('siteurl'));
}
