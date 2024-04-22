<?php
/**
 * Plugin Name: Custom Plugin
 * Plugin URI: https://facebook.com/
 * Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. Akismet Anti-spam keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
 * Version: 5.3.1
 * Requires at least: 5.8
 * Requires PHP: 5.6.20
 * Author: OM
 * Author URI: https://facebook.com/wordpress-plugins/
 * License: GPLv2 or later
 */

require_once(plugin_dir_path(__FILE__) . 'interaction-tracking.php'); 
class Custom_Plugin {

    public function __construct() {

        define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
        define("PLUGIN_URL", plugins_url());
        add_action("admin_menu", array($this, 'add_my_custom_menu'));
        add_action("wp_enqueue_scripts", array($this, 'custom_plugin_assets'));
        add_shortcode('button', array($this, 'shortcode'));
        add_action('wp_ajax_track_button_click', array($this, 'track_button_click'));
        add_action('wp_ajax_nopriv_track_button_click', array($this, 'track_button_click')); 
    }

    public function add_my_custom_menu() {
        add_menu_page(
            "customplugin", 
            "Custom Plugin", 
            "manage_options", 
            "custom-plugin", 
            array($this, 'custom_admin_view'), 
            "dashicons-dashboard", 
            11 
        );
    }

    public function custom_admin_view() {
        echo "<h1>hello</h1>";
    }

    public function custom_plugin_assets() {
        wp_enqueue_style("cpt_style", PLUGIN_URL."/custom-plugin/assets/css/style.css");

        wp_enqueue_script('custom-tracking', PLUGIN_URL . '/custom-plugin/assets/js/script.js', array('jquery'), '1.0', true);
    }
    
    public function shortcode($atts) {
        
        $atts = shortcode_atts(
            array(
                'text' => 'Click Here',
                'style' => 'default',
                'url' => '#',
                'play_icon' => false 
            ),
            $atts,
            'button'
        ); 
        
        $text = $atts['text'];
        $style = $atts['style'];
        $url = esc_url($atts['url']);
        $play_icon = $atts['play_icon'];

        global $post;
        if ($post) {
            $tracking = new Tracking(); 
            $tracking->track_button_display($post->ID); 
        }
        
        $button_html = '<a href="' . $url . '" class="button ' . $style . '">';

        if ($play_icon) {
            $button_html .= '<span class="play-icon"></span>'; 
        }

        $button_html .= '<span class="button-text">' . $text . '</span>';

        $button_html .= '</a>';

        return $button_html;
    }

    
    public function track_button_click() {
        if (isset($_POST['page_id'])) {
            $page_id = intval($_POST['page_id']);
            $tracking = new Tracking(); 
            $tracking->track_button_click($page_id); 
        }
        wp_die(); 
    }
}

$custom_plugin = new Custom_Plugin();
