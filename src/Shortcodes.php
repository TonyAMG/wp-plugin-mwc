<?php


class Shortcodes
{

    public function __construct()
    {
        add_shortcode('movie_world_collector', [$this, 'mwc_shortcode_handler']);
    }

    public function mwc_shortcode_handler($atts)
    {
        if (empty(get_option('mwc_plugin_options')['api_key']))
        	return 'Для работы плагина <b>Movie World Collector</b> необходимо ввести API Key в Админке!';

    	$atts = shortcode_atts([
            'type'         => 'popular',
            'movie_id'     => ''
        ], $atts);

        $movie_db_api = new TMDbAPI();
        $response = $movie_db_api->getMovieInfo($atts['type'], $atts['movie_id']);

        $movie_desc_uri = ((new WP_Rewrite)->using_mod_rewrite_permalinks())
            ? 'movie/'
            : 'index.php?pagename=mwc-movie-description&movie_id=';

        if ($atts['type'] === 'popular') {
            $out = '<section class="regular slider">';
            foreach ($response['results'] as $key => $value) {
                $out .= '
                <div>
                    <a href="'.get_site_url().'/'. $movie_desc_uri . $response['results'][$key]['id'] . '">
                        <img src="https://image.tmdb.org/t/p/w342' . $response['results'][$key]['poster_path'] . '">
                    </a>
                </div>';
            }
            $out .= '</section>
            <script type="text/javascript">
                $(document).on(\'ready\', function() {
                $(".regular").slick({
                    dots: true,
                    arrows: false,
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 1500,
                    speed: 2000,
                    slidesToShow: 5,
                    slidesToScroll: 2,
                });
                });
            </script>';
        }

        if ($atts['type'] === 'movie_details') {

            //$movie_credits = $movie_db_api->getMovieInfo('movie_credits', $atts['movie_id']);

            $out = '
                <div class="mwc-plugin-film-details-page">
                    <h2 class="mwc-plugin-film-title">'.$response['title'].'</h2>
                    <p class="mwc-plugin-film-original-title">'.$response['original_title'].'</p>
                    <div class="mwc-plugin-film-logo-column">
                        <img src="https://image.tmdb.org/t/p/w342/'.$response['poster_path'].'">
                    </div>
                    <div class="mwc-plugin-film-details-column">
                        <span class="mwc-plugin-film-desc-headers">Релиз: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">'.$response['release_date'].'</span>
                        <br>
                        <span class="mwc-plugin-film-desc-headers">Бюджет: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">'.$this->prepareFinance('budget', $response).' </span>
                        <br>
                        <span class="mwc-plugin-film-desc-headers">Сборы: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">'.$this->prepareFinance('revenue', $response).' </span>
                        <br>
                        <span class="mwc-plugin-film-desc-headers">Жанр: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">
                            '.$this->extractArrayParam('genres', 'name', $response).'
                        </span>
                        <br>
                        <span class="mwc-plugin-film-desc-headers">Время: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">'.$this->prepareRuntime($response['runtime']).'</span>
                        <br>
                        <span class="mwc-plugin-film-desc-headers">Страна: &nbsp;</span>
                        <span class="mwc-plugin-film-desc">
                            '.$this->extractArrayParam('production_countries', 'iso_3166_1', $response).'
                        </span>
                    </div>
                    <div class="mwc-plugin-film-overview-column">
                        '.$response['overview'].'
                    </div>
                    
                </div>
            ';
            //var_dump($response);
            //var_dump($movie_credits);
        }

        return $out;
    }

    private function prepareRuntime($runtime): string
    {
        if ($runtime === 0){
            return 'неизвестно';
        } else {
            return $runtime.' мин. / '.floor($runtime / 60) .' ч. '. $runtime % 60 .' мин.';
        }
    }

    private function prepareFinance($type, array $response): string
    {
        if ($response[$type] === 0 || !isset($response[$type])) {
            return 'неизвестно';
        } else {
            return '$'.number_format($response[$type], "0", "", ", ");
        }
    }

    private function extractArrayParam($type, $subtype, array $response)
    {
        if (empty($response[$type]))
            return 'неизвестно';
        $param = '';
        foreach ($response[$type] as $key => $value) {
            $param .= $value[$subtype].' ';
        }
        return str_replace(' ', ', ', trim($param));
    }


}