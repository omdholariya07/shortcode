<?php
class Custom_Admin_Page {
    public function __construct() {
        add_action('admin_menu', array($this, 'custom_menu_page'));
    }

    public function custom_menu_page() {
        add_menu_page(
            "Custom Plugin", 
            "Custom Plugin", 
            "manage_options", 
            "custom-plugin", 
            array($this, 'admin_page'), 
            "dashicons-dashboard", 
            11 
        );
    }


    public function admin_page() {
        ?>

        

<div class="wrap">
   
    <!-- <canvas id="buttonStatsChart" width="400" height="400"></canvas> -->
    
    <div>Total Views: <?php echo $this->get_total_views(); ?></div>
    
    
    <div>Total Clicks: <?php echo $this->get_total_clicks(); ?></div>
    
   
    <div>Click Through Rate: <?php echo $this->get_ctr(); ?>%</div>

    <form method="post" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">
        <input type="submit" name="submit" class="button-primary" value="Submit">
    </form>
    </div>
        <canvas id="myChart" style="height:350px; width:70%;"></canvas>
        <?php
    }
    public function get_total_views() {
        $tracking = new Tracking();
        return $tracking->get_total_views();
    }
    public function get_total_clicks() {
        $tracking = new Tracking();
        return $tracking->get_total_clicks();
    }

    public function get_ctr() {
        $tracking = new Tracking();
        return $tracking->get_ctr();
    }
}


$custom_admin_page = new Custom_Admin_Page();
