jQuery(document).ready(function($) {
    $('.button').on('click', function(e) {
       e.preventDefault(); 
        
        var page_id = $(this).data('page-id'); 
        console.log(page_id);
        
        
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'track_button_click', 
                page_id: page_id,
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
