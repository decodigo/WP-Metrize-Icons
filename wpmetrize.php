<?php
/*
Plugin Name: WP Metrize Icons
Plugin URI: http://www.decodigothemes.com
Description: Use the Metrize Icon @font-face icon set in Wordpress for retina ready icons.
Version: 1.0.1
Author: DeCodigo
Author URI: http://www.decodigothemes.com
Author Email: nelson@decodigothemes.com
Credits:
    Metrize Icons
    by Alessio Atzeni
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

    THIS SOFTWARE IS LICENSED ARE PROVIDED "AS IS" WITHOUT WARRANTY OF
    ANY KIND, EITHER EXPRESSED OR IMPLIED. THE AUTHOR IS NOT LIABLE
    FOR ANY DAMAGES ARISING OUT OF ANY DEFECTS IN THIS MATERIAL. YOU
    AGREE TO HOLD THE AUTHOR HARMLESS FOR ANY RESULT THAT MAY OCCUR
    DURING THE COURSE OF USING, OR INABILITY TO USE THESE LICENSED
    SOFTWARE. IN NO EVENT SHALL WE BE LIABLE FOR ANY DAMAGES INCLUDING,
    BUT NOT LIMITED TO, DIRECT, INDIRECT, SPECIAL, INCIDENTAL OR
    CONSEQUENTIAL DAMAGES OR OTHER LOSSES ARISING OUT OF THE USE OF OR
    INABILITY TO USE THIS PRODUCTS.
*/

/**
 * Setup Some constants
 */
define( 'WPMETRIZE_VERSION', '1.0.1' );
define( 'WPMETRIZE_CURRENT_DIR', __FILE__ );
define( 'WPMETRIZE_CURRENT_URI', plugins_url('', WPMETRIZE_CURRENT_DIR) );

// Import Icon List singleton
include 'singleton-iconlist.php';

class WPMetrize {
    /**
     * Setup the plugin
     */
    public function __construct() {
        add_shortcode( 'icon', array( &$this, 'setup_shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );
        add_action( 'init', array( &$this, 'add_mce_buttons') );
        add_action( 'admin_footer', array( &$this, 'admin_popup' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'plugin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'plugin_scripts' ) );
    }

    /**
     * CSS and JS
     */
    public function plugin_scripts() {
        global $wp_styles;
        wp_enqueue_style( 'metrize_styles', WPMETRIZE_CURRENT_URI . '/css/metrize.css' , false, WPMETRIZE_VERSION  );
        wp_enqueue_style( 'metrize_lte_ie7', WPMETRIZE_CURRENT_URI . '/js/lte-ie7.js', false , array(), WPMETRIZE_VERSION, 'all'  );
        $wp_styles->add_data( 'metrize_lte_ie7', 'conditional', 'lte IE 7' );

        if(is_admin())
            wp_enqueue_style( 'metrize_admin_styles', WPMETRIZE_CURRENT_URI . '/css/metrize-admin.css', false, WPMETRIZE_VERSION  );
    }

    /**
     * Set up the shortcode for the icons
     */
    public function setup_shortcode( $params ) {
        extract( shortcode_atts( array(
                    'name'  => 'leaf',
                    'size'  => 'medium'
                ), $params ) );
        $icon = '<i class="metrize-'. sanitize_key( $params['name'] ) .' '. sanitize_key( $params['size'] ) .'">&nbsp;</i>';

        return $icon;
    }

    /**
     * Add Tiny MCE Buttons to the Wordpress Editor
     */
    public function add_mce_buttons() {
        add_filter( "mce_external_plugins", array( &$this, "add_mce_plugin") );
        add_filter( 'mce_buttons', array( &$this, 'register_mce_buttons') );
    }

    /**
     * Adds the script necessary for the Tiny MCE Plugin
     */
    public function add_mce_plugin( $plugin_array ) {
        $plugin_array['wpmetrize'] = WPMETRIZE_CURRENT_URI . '/js/scripts.js';
        return $plugin_array;
    }

    /**
     * Register the Button with Tiny MCE
     */
    public function register_mce_buttons( $buttons ) {
        array_push($buttons, 'metrize_icons');
        return $buttons;
    }

    /**
     * Build the admin popup.
     */
    public function admin_popup(){
        $icons = WPMetrizeIconList::get_list();
        add_thickbox();
        ?>
        <input style="display:none" id="iconpopupbtn" alt="#TB_inline?width=full&height=full&inlineId=icons_popup" title="<?php  echo __('Add Icons', 'codigo');?>" class="thickbox" type="button" value="open sesame"/>
        <div id="icons_popup" style="display:none;">
            <h2><?php  echo __('Metrize Icons', 'wpmetrize');?></h2>

            <p class="description"><?php echo __('Select an icon to be inserted in the content body.','wpmetrize') ?></p>

            <p class="size-cont">
                <label for="metrize_size"><?php _e('Size','wpmetrize') ?>:</label>

                <select id="metrize_size">
                    <option type="radio" name="metrize_size" value="small"><?php _e('Small','wpmetrize'); ?></option>
                    <option type="radio" name="metrize_size" value="medium" selected><?php _e('Medium','wpmetrize'); ?></option>
                    <option type="radio" name="metrize_size" value="large"><?php _e('Large','wpmetrize'); ?></option>
                    <option type="radio" name="metrize_size" value="xlarge"><?php _e('Extra Large','wpmetrize'); ?></option>
                </select>
            </p>

            <div class="metrize-icons-container">
                <?php foreach ($icons as $icon):?>
                <span class="box1">
                    <a aria-hidden="true" class="metrize-<?php echo $icon; ?>" rel="<?php echo $icon; ?>"></a>
                </span>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }
}

// Ok Go!
new WPMetrize();