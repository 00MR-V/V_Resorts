// AdminManageBooking.js
$(document).ready(function () {
    console.log("Admin Manage Bookings Script Loaded");

    // Fetch bookings on page load
    fetchBookings();

    // Filter bookings when the filter form is submitted (press Enter or click Filter)
    $("#filterForm").on("submit", function(e) {
        e.preventDefault();
        fetchBookings();
    });

    // Event: View booking details
    $(document).on("click", ".viewBookingBtn", function () {
        let bookingId = $(this).data("id");
        $.ajax({
            url: "AdminGetBooking.php",
            type: "POST",
            data: { bookingId: bookingId },
            dataType: "json",
            success: function (data) {
                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
                $("#bookingModal .modal-content").html(`
                    <button class="close-button">&times;</button>
                    <h2>Booking Details</h2>
                    <p><strong>Booking ID:</strong> ${data.Booking_ID}</p>
                    <p><strong>Property:</strong> ${data.propertyName}</p>
                    <p><strong>Customer:</strong> ${data.customerName}</p>
                    <p><strong>Check-In Date:</strong> ${data.Check_In_Date}</p>
                    <p><strong>Check-Out Date:</strong> ${data.Check_Out_Date}</p>
                    <p><strong>Status:</strong> ${data.Status}</p>
                    <p><strong>Payment:</strong> ${data.Payment}</p>
                `);
                $("#bookingModal").removeClass("hidden");
            },
            error: function (xhr, status, error) {
                alert("Error fetching booking details.");
            }
        });
    });

    // Event: Close modal
    $(document).on("click", ".close-button", function () {
        $("#bookingModal").addClass("hidden");
    });

    // Event: Update booking status
    $(document).on("click", ".updateStatusBtn", function () {
        let bookingId = $(this).data("id");
        let newStatus = $(this).data("newstatus");
        if (confirm("Are you sure you want to set this booking to " + newStatus + "?")) {
            $.ajax({
                url: "AdminUpdateBookingStatus.php",
                type: "POST",
                data: { bookingId: bookingId, newStatus: newStatus },
                success: function(response) {
                    alert(response);
                    fetchBookings();
                },
                error: function(xhr, status, error) {
                    alert("Error updating booking status.");
                }
            });
        }
    });

    // Function to fetch all bookings, including filtering by the search term
    function fetchBookings() {
        let searchTerm = $("input[name='search']").val();
        $.ajax({
            url: "AdminGetBooking.php",
            type: "POST",
            data: { search: searchTerm },
            dataType: "html",
            success: function(data) {
                if (!data.trim()) {
                    $("tbody#bookingsTableBody").html("<tr><td colspan='8'>No bookings found.</td></tr>");
                    return;
                }
                $("tbody#bookingsTableBody").html(data);
            },
            error: function(xhr, status, error) {
                alert("Error fetching bookings.");
            }
        });
    }
});
