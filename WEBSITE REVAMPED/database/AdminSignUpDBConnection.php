<?php

require_once "VResortsConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $first_name = $_POST['firstName'] ?? null;
    $last_name = $_POST['lastName'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $address = $_POST['address'] ?? null;
    $phone_number = $_POST['phone'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirmPassword'] ?? null;


    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }


 
    $sql = "INSERT INTO adminn (FName, LName, DOB, Address, Phone_Num, Username, Email, Password) 
            VALUES (:firstName, :lastName, :dob, :address, :phone, :username, :email, :password)";

    try {
   
        $stmt = $pdo->prepare($sql);


        $stmt->bindParam(':firstName', $first_name);
        $stmt->bindParam(':lastName', $last_name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone_number);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

       
        if ($stmt->execute()) {
            
            header("Location: /WEBSITE REVAMPED/Pages/HomePage/HomePage.php#logInModal");
            exit; 
        } else {
            echo "Error: Could not register user.";
        }
    } catch (PDOException $e) {
       
        echo "Database Error: " . $e->getMessage();
    }
}
