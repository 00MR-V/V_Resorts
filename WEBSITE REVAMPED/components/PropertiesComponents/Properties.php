<?php
// FetchProperties.php defines fetchProperties() (guarded by function_exists)
// and then does: return fetchProperties(...);
$properties = require __DIR__ . '/FetchProperties.php';
?>

<div class="horizontalScrollContainer">
    <?php foreach ($properties as $property): ?>
        <div class="propertyCard">
            <img
              src="data:image/jpeg;base64,<?= $property['propertyPhoto']; ?>"
              alt="<?= htmlspecialchars($property['Name']); ?>"
            >
            <h3><?= htmlspecialchars($property['Name']); ?></h3>
            <p><?= htmlspecialchars($property['Description']); ?></p>
            <p><strong>Price:</strong> $<?= number_format($property['Price']); ?> per night</p>
            <a
              class="bookNowButton"
              href="/V_Resorts/WEBSITE%20REVAMPED/Pages/SpecificPropertyPage/SpecificPropertyPage.php?property_id=<?= $property['Property_ID']; ?>"
            >
              Book Now
            </a>
        </div>
    <?php endforeach; ?>
</div>
