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
public function get_data_by_date_range($start_date, $end_date) {
    global $wpdb;

    $data = [];
    $current_date = strtotime($start_date);
    $end_timestamp = strtotime($end_date);

    while ($current_date <= $end_timestamp) {
        $date = date('Y-m-d', $current_date);
        $data[$date] = ['date' => $date, 'views' => 0, 'clicks' => 0, 'ctr' => 0];
        $current_date = strtotime('+1 day', $current_date);
    }
    
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
    
    foreach ($results as $result) {
        $date = $result['date'];
        $ctr = $this->calculate_ctr($result['clicks'], $result['views']);
        $data[$date] = ['date' => $date, 'views' => $result['views'], 'clicks' => $result['clicks'], 'ctr' => $ctr];
    }

    $data = array_slice($data, -7);
    return array_values($data);
}
private function calculate_ctr($clicks, $views) {   
        if ($views > 0) {
            $ctr = ($clicks / $views) * 100;
            return round($ctr, 2);
        } else {
            return 0;
        }
    }
}

$tracking = new Tracking();
