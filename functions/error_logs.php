<?php 
require_once __DIR__ . '/db.php';

function insertError(string $message): bool {
    $pdo = getDBConnection();

    $sql = "INSERT INTO errorlog (Message, Timestamp) VALUES (:message, NOW())";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':message' => $message
    ]);
}

function getErrors(){
    $pdo = getDBConnection();
    
    $query = "SELECT * FROM errorlog";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();  
}
?>