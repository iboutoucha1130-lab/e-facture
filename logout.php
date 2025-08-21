<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

if (!empty($_COOKIE['remember_token'])) {
    try {
        $token = $_COOKIE['remember_token'];
        
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    } catch (PDOException $e) {
        error_log(__('error.token_deletion') . $e->getMessage());
    }
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

session_destroy();

header('Location: login.php?logout=1');
exit();
?>