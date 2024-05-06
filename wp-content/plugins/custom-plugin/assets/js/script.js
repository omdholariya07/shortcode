jQuery(document).ready(function($) {
    // Attach click event handler to elements with the class 'shortcode-button'
    $(document).on('click', '.shortcode-button', function(e) { 
        // Prevent default behavior 
        e.preventDefault(); 
        
        // Get the value of 'data-page-id' attribute of the clicked element
        var page_id = $(this).data('page-id'); 
        
        // Get the value of 'data-redirect-url' attribute of the clicked element (URL to redirect to)
        var redirectUrl = $(this).data('redirect-url');
        
        // Get the value of 'data-style' attribute of the clicked element
        var style = $(this).data('style');
        
        // AJAX call to track button click
        $.ajax({
            url: ajax_object.ajaxurl, // URL to send the AJAX request
            type: 'POST', // HTTP method for the request
            data: { // Data to send with the request
                action: 'track_button_click', // Action to perform on the server
                page_id: page_id, // Page ID to track
            },
            success: function(response) { // Callback function for successful AJAX request
                console.log('Button click tracked successfully', page_id); // Log success message to console
                // Open redirect URL in a new window/tab
                window.open(redirectUrl, '_blank');
            },
            error: function(xhr, status, error) { // Callback function for failed AJAX request
                console.error('Error tracking button click:', error); // Log error message to console
            }
        });
    });
});
