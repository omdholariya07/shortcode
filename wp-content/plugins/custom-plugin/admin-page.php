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
    <div class="admin-stats">
        <div class="box">
            <div class="box views-box">
                <!-- Apply views-box class to the box for Total Views -->
                <div>
                    <i class="fa fa-eye"></i> Total Views: <span
                        class="views-number"><?php echo $this->get_total_views(); ?></span>
                </div>
            </div>

            <div class="box clicks-box">
                <!-- Apply clicks-box class to the box for Total Clicks -->
                <div>
                    <i class="fa fa-mouse-pointer"></i> Total Clicks: <span
                        class="views-number"><?php echo $this->get_total_clicks(); ?></span>
                </div>
            </div>

            <div class="box ctr-box">
                <!-- Apply ctr-box class to the box for Click Through Rate -->
                <div>
                    <i class="fa fa-hand-o-up"></i> Click Through Rate: <span
                        class="views-number"><?php echo $this->get_ctr(); ?>%</span>
                </div>
            </div>

        </div>

        <!-- Form to select date range -->
        <form method="post" action="" class="form">
            <div class="form-description">
                
                <h3>Select start and end date and click submit button to generate a graph between the selected dates
                </h3>
                <h3>The graph below shows the data for the last 7 days</h3>
            </div>
            <div class="date-inputs">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date"
                    value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <input type="submit" name="submit" class="custom-submit-button" value="Submit">
        </form>
    </div>

    <!-- Canvas for chart -->
    <canvas id="data-Chart" style="height:350px; width:80%;"></canvas>
</div>

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