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

        let formData = new FormData(this);

        $.ajax({
            url: "AdminSaveProperty.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                $("#propertyModal").addClass("hidden");
                fetchProperties();
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
                    fetchProperties();
                },
                error: function() {
                    alert("An error occurred while deleting the property.");
                }
            });
        }
    });

    // Read More premium modal
    $(document).on('click', '.read-more', function(e) {
        e.preventDefault();

        const fullText = $(this).data('fulltext');

        $('body').append(`
            <div class="read-more-modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <p>${fullText}</p>
                </div>
            </div>
        `);

        $('.close-btn').click(function(){
            $('.read-more-modal').remove();
        });
    });
    $("#closeModal, #closeModal2").click(function() {
        $("#propertyModal").addClass("hidden");
    });
    
    // Fetch properties dynamically
    function fetchProperties() {
        $.ajax({
            url: "AdminFetchProperties.php",
            type: "GET",
            success: function(data) {
                $("tbody").html(data);
            },
            error: function() {
                alert("An error occurred while fetching the properties.");
            }
        });
    }
});
