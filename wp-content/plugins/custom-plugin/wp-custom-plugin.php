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

// Include necessary files
require_once(plugin_dir_path(__FILE__) . 'interaction-tracking.php'); 
require_once(plugin_dir_path(__FILE__) . 'admin-page.php'); 

// Define and initialize the main plugin class
class Custom_Plugin {

    // Constructor method to initialize actions and hooks
    public function __construct() {
        // Define constants
        define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
        define("PLUGIN_URL", plugins_url());
        
        // Add actions and hooks
        add_action("wp_enqueue_scripts", array($this, 'custom_plugin_assets'));
        add_shortcode('button', array($this, 'shortcode'));
        add_action('wp_ajax_track_button_click', array($this, 'track_button_click'));
        add_action('wp_ajax_get_button_stats', array($this, 'get_button_stats'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_page_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_chart_assets'));
        add_action('wp_ajax_get_data_by_date_range', array($this, 'get_data_by_date_range'));
        add_action('wp_ajax_nopriv_get_data_by_date_range', 'get_data_by_date_range');
    }

 // Enqueue assets for frontend
 public function custom_plugin_assets() {
    // Enqueue button-style.css
    wp_enqueue_style("button-style", PLUGIN_URL . "/custom-plugin/assets/css/button-style.css"); 
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

    // Enqueue button-script.js
    wp_enqueue_script('button-script', PLUGIN_URL . '/custom-plugin/assets/js/button-script.js', array('jquery'), '1.0', true); 
    
    // Localize button-script.js
    wp_localize_script('button-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}

// Enqueue assets for admin page
public function enqueue_chart_assets() {
    // Enqueue Chart.js
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@2.9.4', array(), '2.9.4', true);
    
    // Enqueue admin-chart.js
    wp_enqueue_script('admin-chart', PLUGIN_URL . '/custom-plugin/assets/js/admin-chart.js', array('jquery'), '1.0', true); 
    
    // Localize admin-chart.js
    wp_localize_script('admin-chart', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}


// Enqueue styles for admin page
public function enqueue_admin_page_styles() {
    // Enqueue admin page styles
    wp_enqueue_style("admin-page-styles", PLUGIN_URL . "/custom-plugin/assets/css/admin-page-style.css"); 
    
    // Enqueue Font Awesome
    if (!wp_style_is('font-awesome', 'enqueued')) {
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
    }
}

// Shortcode function for generating button
public function shortcode($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(
        array(
            'text' => 'Click Here',
            'style' => '', 
            'url' => '',
            'play_icon' => true,
            'color' => ''
        ),
        $atts,
        'button'
    );

    // Extract shortcode attributes
    $text = $atts['text'];
    $style = $atts['style']; 
    $url = esc_url($atts['url']);
    $play_icon = $atts['play_icon'];
    $color = $atts['color']; 

    // Get current page ID
    global $post;
    $page_id = ($post) ? $post->ID : 0;

    // Track button display
    $tracking = new Tracking();
    $tracking->track_button_display($page_id);

    // Generate message HTML
    $message_html = '<h6>Click on the below button which will take you to another page</h6>';

    // Generate button HTML with data attributes for URL, style, and page ID
    $button_html = '<div class="shortcode-button-container">' .
                    '<button class="shortcode-button" data-page-id="' . esc_attr($page_id) . '" data-redirect-url="' . $url . '" style="' . $style . '">'; 
    if ($play_icon) {
        $button_html .= '<i class ="play-icon fa fa-play-circle-o"></i>';
    }
    $button_html .= '<span class="button-text" style="color: ' . $color . '">' . $text . '</span>' .
                    '</button>' .
                    '</div>'; // Close the container

    // Return generated message and button HTML
    return $message_html . $button_html;
}

 
    // AJAX callback to track button click
    public function track_button_click() {
        if (isset($_POST['page_id'])) {
            $page_id = intval($_POST['page_id']);
            $tracking = new Tracking(); 
            $url = ''; 
            $tracking->track_button_click($page_id); 
        }
    }
    
    // AJAX callback to get button click stats
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

    // AJAX callback to get data by date range for chart
public function get_data_by_date_range() {
    $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    $tracking = new Tracking();
    $results = $tracking->get_data_by_date_range($start_date, $end_date);
    
    wp_send_json($results);
}

}

// Instantiate the main plugin class
$custom_plugin = new Custom_Plugin();