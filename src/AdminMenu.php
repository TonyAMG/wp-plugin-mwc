<?php


class AdminMenu
{

    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', [$this, 'register_mwc_admin_menu'] );
        add_action( 'admin_init', [$this, 'page_init'] );
    }


    function register_mwc_admin_menu()
    {
        add_options_page(
            'MW Collector',
            'MW Collector',
            'manage_options',
            'mwc',
            [$this, 'create_admin_page']
        );

    }

    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'mwc_plugin_options' );
        ?>
        <div class="wrap">
            <h1>Плагин Movie World Collector</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button('Сохранить');
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'mwc_plugin_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_api_key',
            'Системные настройки', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_section(
            'setting_section_display',
            'Настройки отображения', // Title
            array( $this, 'setting_section_display' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'api_key',
            'API Key', // Title
            array( $this, 'api_key_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_api_key' // Section
        );

        add_settings_field(
            'show_on_pages',
            'Показывать новинки', // Title
            array( $this, 'show_on_pages_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_display' // Section
        );
    }

    public function sanitize( $input )
    {
        $new_input = array();

        if ( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

        if ( isset( $input['show_on_pages']) ) {
            if ($input['show_on_pages'] === 'off' || $input['show_on_pages'] === 'bottom')
                $new_input['show_on_pages'] = $input['show_on_pages'];
        }


        return $new_input;
    }

    public function print_section_info()
    {
        print 'Введи API Key, который ты должен получить на <b>https://www.themoviedb.org/documentation/api</b>';
    }

    public function setting_section_display()
    {
        print 'Настройки отображения слайдера с фильмами на страницах сайта';
    }

    public function api_key_callback()
    {
        printf(
            '<input type="text" size="50" id="api_key" name="mwc_plugin_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function show_on_pages_callback()
    {
        printf(
            '<label>
                        <input type="radio" id="show_on_pages_off" name="mwc_plugin_options[show_on_pages]" value="off" %s>
                            <label for="show_on_pages_off">Отключено</label>
                        <br>
                        <input type="radio" id="show_on_pages_bottom" name="mwc_plugin_options[show_on_pages]" value="bottom" %s>
                            <label for="show_on_pages_bottom">Внизу</label>
                    </label>',
            (isset( $this->options['show_on_pages'] ) && $this->options['show_on_pages'] === 'off' ) ? 'checked' : '',
            (isset( $this->options['show_on_pages'] ) && $this->options['show_on_pages'] === 'bottom' ) ? 'checked' : ''
        );
    }

}