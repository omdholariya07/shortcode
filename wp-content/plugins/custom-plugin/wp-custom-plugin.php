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
require_once(plugin_dir_path(__FILE__) . 'admin-page.php'); 

class Custom_Plugin {

    public function __construct() {
        define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
        define("PLUGIN_URL", plugins_url());
        add_action("wp_enqueue_scripts", array($this, 'custom_plugin_assets'));
        add_shortcode('button', array($this, 'shortcode'));
        add_action('wp_ajax_track_button_click', array($this, 'track_button_click'));
        add_action('wp_ajax_get_button_stats', array($this, 'get_button_stats'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_chart_assets'));
        add_action('wp_ajax_get_data_by_date_range', array($this, 'get_data_by_date_range'));
    }

    public function custom_plugin_assets() {
        wp_enqueue_style("mystyle", PLUGIN_URL . "/custom-plugin/assets/css/style.css");

        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

        wp_enqueue_script('myscript', PLUGIN_URL . '/custom-plugin/assets/js/script.js', array('jquery'), '1.0', true);
        
        wp_localize_script('myscript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

     public function enqueue_chart_assets() {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@2.9.4', array(), '2.9.4', true);
        
       
        wp_enqueue_script('admin-script', PLUGIN_URL . '/custom-plugin/assets/js/admin-chart.js', array('jquery'), '1.0', true);

        wp_localize_script('admin-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

      
     }   
    
    public function shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'text' => 'Click Here',
                'style' => 'default',
                'url' => '#',
                'play_icon' => true
            ),
            $atts,
            'button'
        ); 
        
        $text = $atts['text'];
        $style = $atts['style'];
        $url = esc_url($atts['url']);
        $play_icon = $atts['play_icon'];
    
        global $post;
        $page_id = ($post) ? $post->ID : 0; 
    
        $tracking = new Tracking(); 
        $tracking->track_button_display($page_id); 
    
        $button_html = '<button class="button" data-page-id="' . esc_attr($page_id) . '">';
    
        if ($play_icon) {
            $button_html .= '<i class ="play-icon fa fa-play-circle-o"></i>';
        }
    
        $button_html .= '<span class="button-text">' . $text . '</span>';
    
        $button_html .= '</button>';
    
        return $button_html;
    }
    
    public function track_button_click() {
        if (isset($_POST['page_id'])) {
            $page_id = intval($_POST['page_id']);
            $tracking = new Tracking(); 
            $tracking->track_button_click($page_id); 
        }
    }
    
    public function get_button_stats() {
        $tracking = new Tracking();
        $total_clicks = $tracking->get_total_clicks();
        $ctr = $tracking->get_ctr();
    
        $response = array(
            'total_clicks' => $total_clicks,
            'ctr' => $ctr
        );
    
        wp_send_json($response);
    }

    public function get_data_by_date_range() {
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        $tracking = new Tracking();
        $results = $tracking->get_data_by_date_range($start_date, $end_date);

        wp_send_json($results);
    }

}

$custom_plugin = new Custom_Plugin();
