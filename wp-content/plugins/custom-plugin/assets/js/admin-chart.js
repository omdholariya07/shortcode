jQuery(document).ready(function($) {
    $('.b1').on('click', function(e) {
        e.preventDefault();

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        console.log("Start Date:", start_date); 
        console.log("End Date:", end_date);     

        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_data_by_date_range', 
                start_date: start_date,
                end_date: end_date,
            },
            success: function(response) {
                renderChart(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });

    });         

    function renderChart(data) {
        var labels = [];
        var viewsData = [];
        var clicksData = [];
        var ctrData = [];
    
        data.forEach(function(item) {
            labels.push(item.date);
            viewsData.push(item.views);
            clicksData.push(item.clicks);
            ctrData.push(item.ctr);
        });
    
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
});
