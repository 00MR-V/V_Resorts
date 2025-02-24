$(document).ready(function() {
    console.log("Admin Manage Properties Script Loaded");

    // Open modal for adding a new property
    $("#addPropertyBtn").click(function() {
        $("#propertyModal").removeClass("hidden");
        $("#modalTitle").text("Add New Property");
        $("#propertyForm")[0].reset();
        $("#propertyId").val(""); // Clear hidden ID field
    });

    // Close modal
    $("#closeModal").click(function() {
        $("#propertyModal").addClass("hidden");
    });

    // Submit form (Add/Edit Property)
    $("#propertyForm").submit(function(e) {
        e.preventDefault();

        // Validate required fields
        if ($("#propertyName").val().trim() === "" || 
            $("#propertyType").val().trim() === "" || 
            $("#propertyLocation").val().trim() === "" || 
            $("#propertyPrice").val().trim() === "") {
            alert("Please fill in all required fields.");
            return;
        }

        let formData = new FormData(this); // Use FormData for flexibility

        $.ajax({
            url: "AdminSaveProperty.php",
            type: "POST",
            data: formData,
            processData: false, // Important for file uploads
            contentType: false, // Important for file uploads
            success: function(response) {
                alert(response);
                $("#propertyModal").addClass("hidden");
                fetchProperties(); // Refresh properties list dynamically
            },
            error: function() {
                alert("An error occurred while saving the property.");
            }
        });
    });

    // Edit property (Prefill Modal)
    $(document).on("click", ".editBtn", function() {
        let propertyId = $(this).data("id");

        $.ajax({
            url: "AdminGetProperty.php",
            type: "POST",
            data: { propertyId: propertyId },
            dataType: "json",
            success: function(data) {
                if (data) {
                    $("#propertyId").val(data.Property_ID);
                    $("#propertyName").val(data.Name);
                    $("#propertyType").val(data.Type);
                    $("#propertyLocation").val(data.Location);
                    $("#propertyPrice").val(data.Price);
                    $("#propertyAvailability").val(data.Availability);

                    $("#propertyModal").removeClass("hidden");
                    $("#modalTitle").text("Edit Property");
                } else {
                    alert("Error: Could not fetch property details.");
                }
            },
            error: function() {
                alert("An error occurred while fetching property details.");
            }
        });
    });

    // Delete property
    $(document).on("click", ".deleteBtn", function() {
        if (confirm("Are you sure you want to delete this property?")) {
            let propertyId = $(this).data("id");

            $.ajax({
                url: "AdminDeleteProperty.php",
                type: "POST",
                data: { propertyId: propertyId },
                success: function(response) {
                    alert(response);
                    fetchProperties(); // Refresh properties list dynamically
                },
                error: function() {
                    alert("An error occurred while deleting the property.");
                }
            });
        }
    });

    // Function to fetch and refresh properties dynamically
    function fetchProperties() {
        $.ajax({
            url: "AdminFetchProperties.php",
            type: "GET",
            success: function(data) {
                $("tbody").html(data); // Update the table without reloading the page
            },
            error: function() {
                alert("An error occurred while fetching the properties.");
            }
        });
    }
});
