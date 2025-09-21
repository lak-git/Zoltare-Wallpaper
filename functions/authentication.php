<?php
require_once __DIR__ . '/error_logs.php';

function login($email, $password) {
    $pdo = getDBConnection();
    if (!$pdo) return ['status' => false];

    try {
        $stmt = $pdo->prepare('SELECT * FROM `users` WHERE `email` = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        if (!$result) {
            return ['status' => false];
        }

        $auth = password_verify($password, $result['password']);
        $is_admin = false;
        if ($result['role'] == 'admin') { $is_admin = true; }
        return ['status' => $auth, 'user_id' => $result["user_id"],'is_admin' => $is_admin];
    } catch (Exception $e) {
        insertError($e->getMessage());
        return ['status' => false];
    }
}
?>