<?php
session_start();

// Include the database connection file
require_once "VResortsConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is an admin
    $sql  = "SELECT * FROM adminn WHERE Email = :email AND Password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email',    $email);
    $stmt->bindParam(':password', $password); // Note: In production, use hashed passwords!
    $stmt->execute();
    $adminn = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminn) {
        // Admin credentials are correct, set session and redirect
        $_SESSION['user_id']    = $adminn['Admin_Id'];
        $_SESSION['username']   = $adminn['Username'];
        // ← Store full name for greeting
        $_SESSION['admin_name'] = $adminn['FName'] . ' ' . $adminn['LName'];

        header("Location: /V_Resorts/WEBSITE%20REVAMPED/Pages/AdminPage/AdminPage.php");
        exit;
    } else {
        // Not an admin: check if the user is a regular customer
        $sql  = "SELECT * FROM customers WHERE Email = :email AND Password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email',    $email);
        $stmt->bindParam(':password', $password); // Note: In production, use hashed passwords!
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Customer credentials are correct, set session and redirect
            $_SESSION['user_id']  = $user['Cust_Id'];
            $_SESSION['username'] = $user['Username'];

            header("Location: /V_Resorts/WEBSITE%20REVAMPED/Pages/HomePage/HomePage.php");
            exit;
        } else {
            // Invalid credentials for both admin and customer
            echo "Invalid email or password!";
        }
    }
}
?>
