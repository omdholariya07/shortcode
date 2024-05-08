<?php
class Tracking {

    private $table_name;

    // Constructor to initialize the table name and register activation hook
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'tracking'; // Set table name with WordPress database prefix
        register_activation_hook( __FILE__, array( $this, 'create_tracking_table' ) ); // Register activation hook to create table on plugin activation
    }

    // Method to create tracking table
    public function create_tracking_table() {
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id int(9) NOT NULL AUTO_INCREMENT,
            page_id int(20) NOT NULL,
            event_type varchar(20) NOT NULL,
            event_timestamp timestamp DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
        )"; // SQL query to create tracking table
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql ); // Execute SQL query using dbDelta function to create or update table
    }

    // Method to track button display
    public function track_button_display($page_id) {
        global $wpdb;
        $wpdb->insert(
            $this->table_name,
            array(
                'page_id' => $page_id,
                'event_type' => 'display',
                'event_timestamp' => current_time('mysql', 1), // Get current timestamp
            ),
        );
    }

    // Method to track button click
    public function track_button_click($page_id) {
        global $wpdb;
        $wpdb->insert(
            $this->table_name,
            array(
                'page_id' => $page_id,
                'event_type' => 'click',
                'event_timestamp' => current_time('mysql', 1), // Get current timestamp
            ),
        );
    }

    // Method to get total views
    public function get_total_views() {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE event_type = 'display'");
        return $result;
    }

    // Method to get total clicks
    public function get_total_clicks() {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE event_type = 'click'");
        return $result;
    }

    // Method to calculate click-through rate (CTR)
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

    // Method to get data by date range
    public function get_data_by_date_range($start_date, $end_date) {
        global $wpdb;

        $data = [];
        $current_date = strtotime($start_date);
        $end_timestamp = strtotime($end_date);

        // Initialize data array with dates and default values
        while ($current_date <= $end_timestamp) {
            $date = date('Y-m-d', $current_date);
            $data[$date] = ['date' => $date, 'views' => 0, 'clicks' => 0, 'ctr' => 0];
            $current_date = strtotime('+1 day', $current_date);
        }

        // Prepare and execute SQL query to get data by date range
        $query = $wpdb->prepare("
            SELECT 
                DATE(event_timestamp) AS date, 
                COUNT(CASE WHEN event_type = 'display' THEN 1 ELSE NULL END) AS views, 
                COUNT(CASE WHEN event_type = 'click' THEN 1 ELSE NULL END) AS clicks 
            FROM $this->table_name 
            WHERE event_timestamp BETWEEN %s AND %s 
            GROUP BY DATE(event_timestamp)", 
            $start_date, 
            $end_date
        );

        $results = $wpdb->get_results($query, ARRAY_A);

        // Process query results and update data array
        foreach ($results as $result) {
            $date = $result['date'];
            $ctr = $this->calculate_ctr($result['clicks'], $result['views']);
            $data[$date] = ['date' => $date, 'views' => $result['views'], 'clicks' => $result['clicks'], 'ctr' => $ctr];
        }

        return array_values($data);
    }

    // Method to calculate click-through rate (CTR)
    private function calculate_ctr($clicks, $views) {   
        if ($views > 0) {
            $ctr = ($clicks / $views) * 100;
            return round($ctr, 2);
        } else {
            return 0;
        }
    }
}

$tracking = new Tracking(); // Instantiate Tracking class
