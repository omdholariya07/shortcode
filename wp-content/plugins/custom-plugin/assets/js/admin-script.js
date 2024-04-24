// jQuery(document).ready(function($) {
    
//     function updateStats() {
//         $.ajax({
//             url: ajax_object.ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'get_button_stats'
//             },
//             success: function(response) {
                
//                 $('.total-clicks').text(response.total_clicks);
//                 $('.ctr').text(response.ctr + '%');
//             },
//             error: function(xhr, status, error) {
//                 console.error('Error fetching button stats:', error);
//             }
//         });
//     }
    
  
//     $('form').on('submit', function(e) {
//         e.preventDefault();
        
        
//         $.ajax({
//             url: ajax_object.ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'handle_admin_form_submission',
//                 start_date: $('#start_date').val(),
//                 end_date: $('#end_date').val()
//             },
//             success: function(response) {
                
//                 console.log('Form submitted successfully');
//             },
//             error: function(xhr, status, error) {
//                 console.error('Error submitting form:', error);
//             }
//         });
//     });
// });


   