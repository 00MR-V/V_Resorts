<?php

// Include the database connection file
require_once "VResortsConnction.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is an admin
    $sql = "SELECT * FROM adminn WHERE Email = :email AND Password = :password";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password); // Note: In a real scenario, passwords should be hashed!

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $adminn = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminn) {
        // Admin credentials are correct, start a session
        session_start();
        $_SESSION['user_id'] = $adminn['Admin_Id'];
        $_SESSION['username'] = $adminn['Username'];

        // Redirect to the admin page
        header("Location: /WEBSITE REVAMPED/Pages/AdminPage/AdminPage.php");


        exit;
    } else {
        // Check if the user is a regular customer
        $sql = "SELECT * FROM customers WHERE Email = :email AND Password = :password";

        // Prepare the statement for customers
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // Note: In a real scenario, passwords should be hashed!

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Customer credentials are correct, start a session
            session_start();
            $_SESSION['user_id'] = $user['Cust_Id'];
            $_SESSION['username'] = $user['Username'];
            // Store role (admin or user) based on login credentials
            // if ($user['role'] == 'admin') {
            //     $_SESSION['role'] = 'admin';
            // } else {
            //     $_SESSION['role'] = 'user';
            // }

            // Redirect to a dashboard or homepage
            header("Location: /WEBSITE REVAMPED/Pages/HomePage/HomePage.php");

            exit;
        } else {
            // Invalid credentials for both admin and customer
            echo "Invalid email or password!";
        }
    }
}
