$(document).ready(function () {
    console.log("✅ Admin Manage Properties Script Loaded");


    $("#addPropertyBtn").click(function () {
        $("#propertyModal").removeClass("hidden");
        $("#modalTitle").text("Add New Property");
        $("#propertyForm")[0].reset();
        $("#propertyId").val("");
    
        $("#existingPhotoPreview").hide();
        $("#existingGalleryPreview").hide();
        $("#existingGalleryImgs").empty();
    });

    
    $("#closeModal, #closeModal2").click(function () {
        $("#propertyModal").addClass("hidden");
    });

   
    $(document).on("click", ".read-more", function (e) {
        e.preventDefault();
        let fullText = $(this).data("fulltext");
        $("#readMoreContent").text(fullText);
        $("#readMoreModal").removeClass("hidden");
    });
    $("#closeReadMoreModal").click(function () {
        $("#readMoreModal").addClass("hidden");
    });

    
    $("#propertyForm").submit(function (e) {
        e.preventDefault();
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
            error: function (xhr) {
                console.log("❌ Save Property Error:", xhr.responseText);
                alert("❌ An error occurred while saving the property.");
            },
        });
    });

 
    $(document).on("click", ".editBtn", function () {
        let propertyId = $(this).data("id");
        console.log("📌 Property ID being sent:", propertyId);
        if (!propertyId) {
            alert("⚠️ Error: No property ID found.");
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
                $("#propertyDescription").val(data.Description || "");
                $("#bigDescription").val(data.Big_Description || "");
                $("#propertyCapacity").val(data.Capacity || "");
                $("#propertyAmenities").val(
                    Array.isArray(data.Amenities) ? data.Amenities.join(", ") : ""
                );

                
                if (data.propertyPhoto) {
                    $("#existingPhotoImg")
                        .attr("src", "data:image/jpeg;base64," + data.propertyPhoto);
                    $("#existingPhotoPreview").show();
                } else {
                    $("#existingPhotoPreview").hide();
                }

            
                $("#existingGalleryImgs").empty();
                if (Array.isArray(data.Gallery_Photos) && data.Gallery_Photos.length) {
                    data.Gallery_Photos.forEach(function (b64, idx) {
                        const $box = $(`
                            <div class="preview-container" data-index="${idx}">
                              <span class="remove-photo">×</span>
                              <img src="data:image/jpeg;base64,${b64}" alt="Gallery Photo">
                            </div>
                        `);
                        $("#existingGalleryImgs").append($box);
                    });
                    $("#existingGalleryPreview").show();
                } else {
                    $("#existingGalleryPreview").hide();
                }

            
                $("#existingPhotoPreview .remove-photo")
                  .off("click")
                  .on("click", function () {
                    $("#existingPhotoImg").attr("src", "");
                    $("#propertyPhoto").val("");
                    $("#existingPhotoPreview").hide();
                  });

             
                $("#existingGalleryImgs")
                  .off("click", ".remove-photo")
                  .on("click", ".remove-photo", function () {
                    $(this).parent(".preview-container").remove();
                  });

            
                $("#propertyModal").removeClass("hidden");
                $("#modalTitle").text("Edit Property");
            },
            error: function (xhr) {
                console.log("❌ AJAX error:", xhr.responseText);
                alert("❌ An error occurred while fetching property details.");
            },
        });
    });


    $(document).on("click", ".deleteBtn", function () {
        if (!confirm("⚠️ Are you sure you want to delete this property?")) return;
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
            error: function (xhr) {
                console.log("❌ Delete Property Error:", xhr.responseText);
                alert("❌ An error occurred while deleting the property.");
            },
        });
    });

    function fetchProperties() {
        $.ajax({
            url: "AdminGetProperty.php",
            type: "POST",
            dataType: "html",
            success: function (data) {
                console.log("✅ Fetch Properties Response:", data);
                if (!data.trim()) {
                    alert("⚠️ No properties found.");
                    $("tbody").html("<tr><td colspan='13'>No properties found.</td></tr>");
                    return;
                }
                $("tbody").html(data);
            },
            error: function (xhr) {
                console.log("❌ Fetch Properties Error:", xhr.responseText);
                alert("❌ Error in fetching properties.");
            },
        });
    }
});
