<?php
require_once '../database/VResortsConnction.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $input['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT Email FROM adminn WHERE Email = :email UNION SELECT Email FROM customers WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        $resetToken = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("INSERT INTO password_resets (email, token, expires_at)
                       VALUES (:email, :token, :expires_at)
                       ON DUPLICATE KEY UPDATE token = :token, expires_at = :expires_at")
            ->execute([
                ':email' => $email,
                ':token' => $resetToken,
                ':expires_at' => $expiresAt
            ]);

        echo json_encode(['success' => true, 'message' => 'A reset link has been sent to your email.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found.']);
    }
}
?>
