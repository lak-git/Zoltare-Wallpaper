<?php
require_once __DIR__ . '/error_logs.php';

function insertNewUser(array $formData) : bool {
    $pdo = getDBConnection();
    if (!$pdo) return false;

    try {
        $query = "INSERT INTO Users (
            username, email, password, role
        ) VALUES (
            :username, :email, :password, 'user'
        )";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username'     => $formData['username'],
            ':email'        => $formData['email'],
            ':password' => $formData['passwordHash'],
        ]);

        return true;
    } catch (Exception $e) {
        insertError($e->getMessage());
        echo "Error occurred while registering user.";
        return false;
    }
}

function addWallpaper(array $data): bool {
    $pdo = getDBConnection();
    $sql = "INSERT INTO wallpapers (uploaded_by, title, category, image_url)
            VALUES (:uploaded_by, :title, :category, :image_url)";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':uploaded_by' => $data['userID'],
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':image_url' => $data['imageURL']
        ]);
    } catch (Exception $e) {
        insertError($e->getMessage());
        return false;
    }
}

function addAdminWallpaper(array $data): bool {
    $pdo = getDBConnection();
    $sql = "INSERT INTO wallpapers (uploaded_by, title, category, price, image_url)
            VALUES (:uploaded_by, :title, :category, :price, :image_url)";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':uploaded_by' => $data['userID'],
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':price' => $data['price'],
            ':image_url' => $data['imageURL']
        ]);
    } catch (Exception $e) {
        insertError($e->getMessage());
        return false;
    }
}

function getWallpaper() : array {
    $pdo = getDBConnection();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("SELECT * FROM wallpapers");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        insertError($e->getMessage());
        return [];
    }
}

function deleteWallpaper(array $wallpaperID) {
    $pdo = getDBConnection();
    $query = "DELETE FROM wallpapers WHERE wallpaper_id = :wallpaper_id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([':wallpaper_id' => $wallpaperID]);
    } catch (Exception $e) {
        insertError($e->getMessage());
    }
}

function updateWallpaper(array $data) {
    $pdo = getDBConnection();
    $sql = "UPDATE wallpapers SET title = :title, category = :category, price = :price WHERE wallpaper_id = :wallpaper_id";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':wallpaper_id' => $data['wp_id'],
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':price' => (float)$data['price']
        ]);
    } catch (Exception $e) {
        insertError($e->getMessage());
        return false;
    }
}

function purchaseWallpaper(array $data): bool {
    $pdo = getDBConnection();
    $sql = "INSERT INTO purchases (user_id, wallpaper_id)
            VALUES (:user_id, :wallpaper_id)";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':wallpaper_id' => $data['wp_id']
        ]);
    } catch (Exception $e) {
        insertError($e->getMessage());
        return false;
    }
}

function getPurchasedWallpaper(int $userID) : array {
    $pdo = getDBConnection();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("SELECT wallpaper_id FROM purchases WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userID]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        insertError($e->getMessage());
        return [];
    }
}

function getUserCount() : array {
    $pdo = getDBConnection();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(user_id) FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        insertError($e->getMessage());
        return [];
    }
}
function getWallpaperCount() : array {
    $pdo = getDBConnection();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(wallpaper_id) FROM wallpapers");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        insertError($e->getMessage());
        return [];
    }
}
function getPurchaseCount() : array {
    $pdo = getDBConnection();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(purchase_id) FROM purchases");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        insertError($e->getMessage());
        return [];
    }
}
?>