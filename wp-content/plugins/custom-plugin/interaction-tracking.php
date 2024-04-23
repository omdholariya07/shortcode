<?php
class Tracking {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'tracking'; 
        register_activation_hook( __FILE__, array( $this, 'create_tracking_table' ) );
    }

    public function create_tracking_table() {
        global $wpdb;
      //  $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id int(9) NOT NULL AUTO_INCREMENT,
            page_id int(20) NOT NULL,
            event_type varchar(20) NOT NULL,
            event_timestamp timestamp DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
        )";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function track_button_display($page_id) {
        global $wpdb;
        $wpdb->insert(
            $this->table_name,
            array(
                'page_id' => $page_id,
                'event_type' => 'display',
                'event_timestamp' => current_time('mysql', 1), 
            ),
        );
    }

    public function track_button_click($page_id) {
        global $wpdb;
        $wpdb->insert(
            $this->table_name,
            array(
                'page_id' => $page_id,
                'event_type' => 'click',
                'event_timestamp' => current_time('mysql', 1), 
            ),
            
        );
    }
    public function get_total_views() {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE event_type = 'display'");
        return $result;
    }
    
    public function get_total_clicks() {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE event_type = 'click'");
        return $result;
    }
    
    public function get_ctr() {
        $total_views = $this->get_total_views();
        $total_clicks = $this->get_total_clicks();
        if ($total_views > 0) {
            $ctr = ($total_clicks / $total_views) * 100;
            return round($ctr, 2);
        } else {
            return 0;
        }
    }
    
}

$tracking = new Tracking();
