jQuery(document).ready(function($) {
    $('.button').on('click', function(e) {
       e.preventDefault(); 
        
        var page_id = $(this).data('page-id'); 
        // var event_type = $(this).data('event_type'); 
        // var event_timestamp = $(this).data('event_timestamp'); 
        
          
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'track_button_click', 
                page_id: page_id,
                // event_type: event_type,
                // event_timestamp: event_timestamp
            },
            success: function(response) {
                
                console.log('Button click tracked successfully');
            },
            error: function(xhr, status, error) {
        
                console.error('Error tracking button click:', error);
            }
        });
    });

});
