<?php


class TMDbAPI
{
    private $options;

    public function getMovieInfo($type = 'popular', $movie_id = '')
    {
        switch ($type) {
            case 'popular':
                $cache_name = 'mwc_plugin_cache_popular';
                $request_part = 'popular';
                break;
            case 'movie_details':
                $cache_name = 'mwc_plugin_cache_movie_details_'.$movie_id;
                $request_part = $movie_id;
                break;
            case 'movie_credits':
                $cache_name = 'mwc_plugin_cache_movie_credits_'.$movie_id;
                $request_part = $movie_id.'/credits';
                break;
        }

        $cached = get_transient( $cache_name );
        if ( $cached !== false )
            return $cached;

        $this->options = get_option( 'mwc_plugin_options' );
        $api_key = $this->options['api_key'];
        $request = 'https://api.themoviedb.org/3/movie/'.$request_part.'?api_key='.$api_key.'&language=ru-RU';
        $response = wp_remote_get( $request );
        $movies = json_decode( wp_remote_retrieve_body( $response ), true );

        set_transient( $cache_name, $movies, 6 * HOUR_IN_SECONDS );

        return $movies;
    }
}