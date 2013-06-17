<?php
/*
Plugin Name: WP Metrize Icons
Plugin URI: http://www.decodigothemes.com
Description: Use the Metrize icon set in Wordpress.
Version: 1.0
Author: DeCodigo
Author URI: http://www.decodigothemes.com
Author Email: nelson@decodigothemes.com
Credits:
    Metrize Icons
    http://www.alessioatzeni.com/metrize-icons/

License:
    Copyright (C) 2013  Nelson Polanco
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


define( 'WPMETRIZE_CURRENT_FILE', __FILE__ );
define( 'WPMETRIZE_CURRENT_DIR', dirname(WPMETRIZE_CURRENT_FILE));

class WPMetrize {

    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
    }

    public function init() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
        add_shortcode( 'icon', array( &$this, 'setup_shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );
    }

    public function register_plugin_styles() {
        global $wp_styles;
        wp_enqueue_style( 'metrize_styles', WPMETRIZE_CURRENT_DIR . 'metrize.css', WPMETRIZE_CURRENT_FILE  );
        wp_enqueue_style( 'metrize_lte_ie7', WPMETRIZE_CURRENT_DIR . 'lte-ie7.js', WPMETRIZE_CURRENT_FILE , array(), '1.0', 'all'  );
        $wp_styles->add_data( 'metrize_lte_ie7', 'conditional', 'lte IE 7' );
    }

    public function setup_shortcode( $params ) {
        extract( shortcode_atts( array(
                    'name'  => 'icon-user'
                ), $params ) );
        $icon = '<i class="'.$params['name'].'">&nbsp;</i>';

        return $icon;
    }

}

// Ok Go!
new WPMetrize();