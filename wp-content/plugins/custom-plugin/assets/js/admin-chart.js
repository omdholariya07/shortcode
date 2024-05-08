jQuery(document).ready(function($) {
    // Function to load graph
    function loadGraph(start_date, end_date) {
        // Send AJAX request to fetch data
        $.ajax({
            url: ajax_object.ajaxurl, // URL for AJAX request
            type: 'POST', // HTTP method
            data: {
                action: 'get_data_by_date_range', // Action to perform on server
                start_date: start_date, // Start date for data retrieval
                end_date: end_date, // End date for data retrieval
            },
            success: function(response) {
                // Call renderChart function with retrieved data
                renderChart(response);
            },
            error: function(xhr, status, error) {
                // Log error if AJAX request fails
                console.error('Error fetching data:', error);
            }
        });
    }

    // Event listener for form submission
    $('.custom-submit-button').on('click', function(e) {
        e.preventDefault(); // Prevent default form submission behavior

        // Get start and end dates from form inputs
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        // Call loadGraph function with provided dates
        loadGraph(start_date, end_date);
    });

    // Function to render chart
    function renderChart(data) {
        // Initialize arrays for chart data
        var labels = [];
        var viewsData = [];
        var clicksData = [];
        var ctrData = [];

        // Extract data from response and populate arrays
        data.forEach(function(item) {
            labels.push(item.date);
            viewsData.push(item.views);
            clicksData.push(item.clicks);
            ctrData.push(item.ctr);
        });

        // Create new Chart instance with retrieved data
        new Chart("data-Chart", {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Views',
                    borderColor: 'blue',
                    data: viewsData
                }, {
                    label: 'Total Clicks',
                    borderColor: 'green',
                    data: clicksData
                }, {
                    label: 'Click Through Rate (%)',
                    borderColor: 'red',
                    data: ctrData
                }]
            },
            options: {
                title: {
                    display: true,
                    text: ''
                },
                scales: {
                    x: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'MMM DD',
                            displayFormats: {
                                day: 'MMM DD'
                            }
                        }
                    }],
                    y: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                }
            }
        });
    }

    // Load graph with last 7 days data on page load
    var endDate = new Date();
    var startDate = new Date(endDate);
    startDate.setDate(startDate.getDate() - 6); // Get date 7 days ago
    var startFormatted = formatDate(startDate);
    var endFormatted = formatDate(endDate);
    $('#start_date').val(startFormatted); // Set start date input value
    $('#end_date').val(endFormatted); // Set end date input value
    loadGraph(startFormatted, endFormatted); // Load graph with initial date range

    // Function to format date to YYYY-MM-DD
    function formatDate(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    }
});
