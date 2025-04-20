<?php
session_start();


require_once "VResortsConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    
    $sql  = "SELECT * FROM adminn WHERE Email = :email AND Password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email',    $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $adminn = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminn) {
        
        $_SESSION['user_id']    = $adminn['Admin_Id'];
        $_SESSION['username']   = $adminn['Username'];
        
        $_SESSION['admin_name'] = $adminn['FName'] . ' ' . $adminn['LName'];

        header("Location: /V_Resorts/WEBSITE%20REVAMPED/Pages/AdminPage/AdminPage.php");
        exit;
    } else {
        
        $sql  = "SELECT * FROM customers WHERE Email = :email AND Password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email',    $email);
        $stmt->bindParam(':password', $password); 
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
           
            $_SESSION['user_id']  = $user['Cust_Id'];
            $_SESSION['username'] = $user['Username'];

            header("Location: /V_Resorts/WEBSITE%20REVAMPED/Pages/HomePage/HomePage.php");
            exit;
        } else {
            
            echo "Invalid email or password!";
        }
    }
}
?>
