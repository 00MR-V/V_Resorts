<?php
// Include the database connection file
require_once "VResortsConnction.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data with fallback to avoid undefined array key warnings
    $first_name = $_POST['firstName'] ?? null;
    $last_name = $_POST['lastName'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $address = $_POST['address'] ?? null;
    $phone_number = $_POST['phone'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirmPassword'] ?? null;

    // Validate required fields
    if (!$first_name || !$last_name || !$dob || !$address || !$phone_number || !$username || !$email || !$password || !$confirm_password) {
        echo "All fields are required!";
        exit;
    }

    // Validate if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password for security
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the `customers` table
    $sql = "INSERT INTO customers (FName, LName, DOB, Address, Phone_Num, Username, Email, Password) 
            VALUES (:first_name, :last_name, :dob, :address, :phone_number, :username, :email, :password)";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            header("Location: /WEBSITE REVAMPED/Pages/HomePage/HomePage.php#logInModal");
            exit; // Ensure script stops after redirection
        } else {
            echo "Error: Could not register user.";
        }
    } catch (PDOException $e) {
        // Catch and display any database errors
        echo "Database Error: " . $e->getMessage();
    }
}
?>
