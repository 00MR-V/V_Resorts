<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: ../AdminPage.php");
    exit;
}

$adminId = (int) $_SESSION['user_id'];


require_once __DIR__ . "/../../database/VResortsConnection.php";

try {
  
    $stmt = $pdo->prepare("
        SELECT FName, LName 
        FROM adminn 
        WHERE Admin_Id = :id 
        LIMIT 1
    ");
    $stmt->execute([':id' => $adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if ($admin) {
        $greetingName = htmlspecialchars($admin['FName'] . ' ' . $admin['LName']);
    } else {
        $greetingName = 'Admin';
    }
} catch (PDOException $e) {
    
    $greetingName = 'Admin';
}
?>


<div class="sidebar">
    <div class="admin-info">
        <h2>Welcome Admin <?php echo $greetingName; ?></h2>
    </div>
    <ul class="nav-list">
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPage.php" data-section="dashboard">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageProperties/AdminManageProperties.php">
                Manage Properties
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageBooking/AdminManageBooking.php">
                Manage Bookings
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPageAnalytics/AdminPageAnalytics.php">
                Analytics
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/HomePage/HomePage.php">
                Back to User Home Page
            </a>
        </li>
    </ul>
</div>