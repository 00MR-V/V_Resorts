
var barChart, donutChart, revenueChart; 

$(document).ready(function() {
    $("#loadAnalytics").on("click", function() {
        loadAnalytics();
    });
    $("#propertySelect, #groupBy").on("change", function() {
        loadAnalytics();
    });

    function loadAnalytics() {
        let propertyId = $("#propertySelect").val();
        let propertyName = $("#propertySelect option:selected").text();
        let groupBy = $("#groupBy").val();
        let heading = (propertyId === "all") ? "Overall Analytics" : "Analytics for " + propertyName;
        
        $.ajax({
            url: "AnalyticsData.php",
            type: "POST",
            data: { property_id: propertyId },
            dataType: "json",
            success: function(data) {
                if (data.error) {
                    $("#analyticsText").html("<p>Error: " + data.error + "</p>");
                    return;
                }
             
                let html = "<h2>" + heading + "</h2>";
                html += "<p><strong>Total Bookings:</strong> " + data.totalBookings + "</p>";
                html += "<p><strong>Total Revenue:</strong> $" + numberWithCommas(data.totalRevenue.toFixed(2)) + "</p>";
                html += "<p><strong>Average Booking Value:</strong> $" + numberWithCommas(data.avgBookingValue.toFixed(2)) + "</p>";
                html += "<p><strong>Cancelled Bookings:</strong> " + data.cancelledBookings + "</p>";
                html += "<p><strong>Cancellation Rate:</strong> " + data.cancellationRate + "%</p>";
                $("#analyticsText").html(html);
                

                let barLabels = ['Total Bookings', 'Cancelled Bookings'];
                let barData = [data.totalBookings, data.cancelledBookings];
                if (!barChart) {
                    let ctxBar = document.getElementById('barChart').getContext('2d');
                    barChart = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: barLabels,
                            datasets: [{
                                label: 'Count',
                                data: barData,
                                backgroundColor: ['#3498db', '#e74c3c'],
                                borderColor: ['#2980b9', '#c0392b'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            },
                            plugins: { legend: { display: false } }
                        }
                    });
                } else {
                    barChart.data.datasets[0].data = barData;
                    barChart.update();
                }
                
           
                let nonCancelled = data.totalBookings - data.cancelledBookings;
                let donutLabels = ['Cancelled', 'Not Cancelled'];
                let donutData = [data.cancelledBookings, nonCancelled];
                if (!donutChart) {
                    let ctxDonut = document.getElementById('donutChart').getContext('2d');
                    donutChart = new Chart(ctxDonut, {
                        type: 'doughnut',
                        data: {
                            labels: donutLabels,
                            datasets: [{
                                data: donutData,
                                backgroundColor: ['#e74c3c', '#27ae60'],
                                borderColor: ['#c0392b', '#1e8449'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                } else {
                    donutChart.data.datasets[0].data = donutData;
                    donutChart.update();
                }
                
            
                loadRevenueChart(propertyId, groupBy);
            },
            error: function(xhr, status, error) {
                $("#analyticsText").html("<p>Error loading analytics.</p>");
            }
        });
    }

    function loadRevenueChart(propertyId, groupBy) {
        $.ajax({
            url: "AnalyticsRevenueData.php",
            type: "POST",
            data: { property_id: propertyId, groupBy: groupBy },
            dataType: "json",
            success: function(results) {
                let labels = [];
                let revenueData = [];
                results.forEach(function(row) {
                    labels.push(row.period);
                    revenueData.push(parseFloat(row.revenue));
                });
                if (!revenueChart) {
                    let ctxRevenue = document.getElementById('revenueChart').getContext('2d');
                    revenueChart = new Chart(ctxRevenue, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Revenue',
                                data: revenueData,
                                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                                borderColor: 'rgba(46, 204, 113, 1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: { display: true, text: 'Revenue Over Time' },
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { callback: function(value) { return "$" + numberWithCommas(value); } }
                                }
                            }
                        }
                    });
                } else {
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = revenueData;
                    revenueChart.update();
                }
            },
            error: function(xhr, status, error) {
                console.error("Revenue chart error:", error);
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
