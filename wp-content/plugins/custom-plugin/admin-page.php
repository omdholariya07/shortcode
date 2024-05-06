<?php
class Custom_Admin_Page {

    // Constructor to initialize the admin menu page
    public function __construct() {
        add_action('admin_menu', array($this, 'custom_menu_page')); // Add action hook to create custom menu page
    }

    // Method to create custom menu page
    public function custom_menu_page() {
        add_menu_page(
            "Custom Plugin", // Page title
            "Custom Plugin", // Menu title
            "manage_options", // Capability required to access menu page
            "custom-plugin", // Menu slug
            array($this, 'admin_page'), // Callback function to display page content
            "dashicons-dashboard", // Icon
            11 // Position
        );
    }

    // Callback function to display admin page content
    public function admin_page() {
        ?>   

        <div class="wrap">
            
            <div>Total Views: <?php echo $this->get_total_views(); ?></div> <!-- Display total views -->
            
            <div>Total Clicks: <?php echo $this->get_total_clicks(); ?></div> <!-- Display total clicks -->
           
            <div>Click Through Rate: <?php echo $this->get_ctr(); ?>%</div> <!-- Display click-through rate -->

            <!-- Form to select date range -->
            <form method="post" action="">
                <h3>Select start and end date and click submit button it will generate graph between selected date.</h3>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date"> 
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date">
                <input type="submit" name="submit" class="b1" value="Submit">
                <h3>Graph should display total views,total clicks, and click through rate.</h3>
            </form>
        </div>

        <!-- Canvas for chart -->
        <canvas id="data-Chart" style="height:350px; width:80%;"></canvas>

        <?php
    }

    // Method to get total views
    public function get_total_views() {
        $tracking = new Tracking(); // Instantiate Tracking class
        return $tracking->get_total_views(); // Get total views
    }

    // Method to get total clicks
    public function get_total_clicks() {
        $tracking = new Tracking(); // Instantiate Tracking class
        return $tracking->get_total_clicks(); // Get total clicks
    }

    // Method to get click-through rate (CTR)
    public function get_ctr() {
        $tracking = new Tracking(); // Instantiate Tracking class
        return $tracking->get_ctr(); // Get CTR
    }
}

$custom_admin_page = new Custom_Admin_Page(); // Instantiate Custom_Admin_Page class
