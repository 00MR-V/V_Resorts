$(document).ready(function () {
    console.log("✅ Admin Manage Properties Script Loaded");

    // Open modal for adding a new property
    $("#addPropertyBtn").click(function () {
        $("#propertyModal").removeClass("hidden");
        $("#modalTitle").text("Add New Property");
        $("#propertyForm")[0].reset();
        $("#propertyId").val(""); // Clear hidden ID field
    });

    // Close modal
    $("#closeModal, #closeModal2").click(function () {
        $("#propertyModal").addClass("hidden");
    });

    // Submit form (Add/Edit Property)
    $("#propertyForm").submit(function (e) {
        e.preventDefault();

        // Validate required fields
        if (
            $("#propertyName").val().trim() === "" ||
            $("#propertyType").val().trim() === "" ||
            $("#propertyLocation").val().trim() === "" ||
            $("#propertyPrice").val().trim() === ""
        ) {
            alert("⚠️ Please fill in all required fields.");
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: "AdminSaveProperty.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("✅ Save Property Response:", response);
                alert(response);
                $("#propertyModal").addClass("hidden");
                fetchProperties();
            },
            error: function (xhr, status, error) {
                console.log("❌ Save Property Error:", xhr.responseText);
                alert("❌ An error occurred while saving the property.");
            },
        });
    });

    // Edit property (Prefill Modal)
    $(document).on("click", ".editBtn", function () {
        let propertyId = $(this).data("id");

        console.log("📌 Property ID being sent:", propertyId);

        if (!propertyId) {
            alert("⚠️ Error: No property ID found. Make sure `data-id` is set in the button.");
            return;
        }

        $.ajax({
            url: "AdminGetProperty.php",
            type: "POST",
            data: { propertyId: propertyId },
            dataType: "json",
            success: function (data) {
                console.log("📌 Response from server:", data);

                if (data.error) {
                    alert("❌ Error: " + data.error);
                    return;
                }

                $("#propertyId").val(data.Property_ID);
                $("#propertyName").val(data.Name);
                $("#propertyType").val(data.Type);
                $("#propertyLocation").val(data.Location);
                $("#propertyPrice").val(data.Price);
                $("#propertyAvailability").val(data.Availability);

                $("#propertyModal").removeClass("hidden");
                $("#modalTitle").text("Edit Property");
            },
            error: function (xhr, status, error) {
                console.log("❌ AJAX error:", xhr.responseText);
                alert("❌ An error occurred while fetching property details.");
            },
        });
    });

    // Delete property
    $(document).on("click", ".deleteBtn", function () {
        if (confirm("⚠️ Are you sure you want to delete this property?")) {
            let propertyId = $(this).data("id");

            $.ajax({
                url: "AdminDeleteProperty.php",
                type: "POST",
                data: { propertyId: propertyId },
                success: function (response) {
                    console.log("✅ Delete Property Response:", response);
                    alert(response);
                    fetchProperties();
                },
                error: function (xhr, status, error) {
                    console.log("❌ Delete Property Error:", xhr.responseText);
                    alert("❌ An error occurred while deleting the property.");
                },
            });
        }
    });

    // Fetch properties dynamically
    function fetchProperties() {
        $.ajax({
            url: "AdminGetProperty.php",
            type: "POST",
            dataType: "html", // ✅ Expect HTML response
            success: function (data) {
                console.log("✅ Fetch Properties Response:", data);
    
                if (!data.trim()) {
                    alert("⚠️ No properties found.");
                    $("tbody").html("<tr><td colspan='8'>No properties found.</td></tr>");
                    return;
                }
    
                $("tbody").html(data);
            },
            error: function (xhr, status, error) {
                console.log("❌ Fetch Properties Error:", xhr.responseText);
                alert("❌ Error in fetching properties.");
            },
        });
    }
    
});
