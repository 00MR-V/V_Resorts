<?php
$properties = require 'FetchProperties.php'; // Fetch properties dynamically
?>

<div class="horizontalScrollContainer">
    <?php foreach ($properties as $property): ?>
        <div class="propertyCard">
            <img src="data:image/jpeg;base64,<?= base64_encode($property['propertyPhoto']); ?>" alt="<?= htmlspecialchars($property['Name']); ?>">
            <h3><?= htmlspecialchars($property['Name']); ?></h3>
            <p><?= htmlspecialchars($property['Description']); ?></p>
            <p><strong>Price:</strong> $<?= number_format($property['Price']); ?> per night</p>
            <a class="bookNowButton" href="/WEBSITE%20REVAMPED/Pages/SpecificPropertyPage/SpecificPropertyPage.php?property_id=<?= $property['Property_ID']; ?>">Book Now</a>
        </div>
    <?php endforeach; ?>
</div>
