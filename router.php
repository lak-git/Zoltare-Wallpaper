<?php
require_once "./functions/authentication.php";
require_once "./functions/queries.php";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);


// -- Authenticated Routes --
// Login
if($path === "/Zoltare/api/login" && $_SERVER['REQUEST_METHOD']==="POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(isset($email) && isset($password)){

       $auth = login($email, $password);

       if($auth['status']){  
            session_start();
            $_SESSION["user_id"] = $auth['user_id'];
            $_SESSION["admin"] = $auth['is_admin'];
            
            // If admin redirect to admin dashboard
            if($_SESSION['admin']){
                header("Location:http://localhost/Zoltare/admin");
            }
            else{
                header("Location:http://localhost/Zoltare");
            }
       }
       else{
        header("Location://localhost/Zoltare/login?error=Email+or+Password+incorrect");
        exit();
       }
    }
}
// Logout
elseif($path === "/Zoltare/api/logout" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_destroy();
    header("location:http://localhost/Zoltare/login");
}
// Register
elseif($path === "/Zoltare/api/register" && $_SERVER['REQUEST_METHOD']==="POST"){
    $checked = true;
    $correctPasswords = true;

    $formData = [
        'username'          => htmlspecialchars(trim($_POST['username'] ?? '')),
        'email'             => htmlspecialchars(trim($_POST['email'] ?? '')),
        'password'          => trim($_POST['password'] ?? ''), 
        'confirmPassword'   => trim($_POST['confirmPassword'] ?? '') 
    ];

    foreach($formData as $key => $value){
        if($value == ''){
            $checked = false;
        }
    }
    if ($formData['password'] !== $formData['confirmPassword']) {
        $correctPasswords = false;
    }
    
    if($checked && $correctPasswords){
        $formData['passwordHash'] = password_hash($formData['password'], PASSWORD_DEFAULT);
        unset($formData['password']);
        if(insertNewUser($formData)){
            header("location:http://localhost/Zoltare/login");
        }
        else{
            header("location:http://localhost/Zoltare/register?error=Something+Went+Wrong");
        }

    }
    elseif ($checked && !$correctPasswords) {
        echo "Password do not match";
    }
    else{
        echo "missing POST variable";
    }

}
elseif($path === "/Zoltare/register" && $_SERVER['REQUEST_METHOD']==="GET"){
    include "./views/register.php";
} 
elseif($path === "/Zoltare/login" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    $_SESSION['user_id'] = null;
    $_SESSION['admin'] = null;
    include "./views/login.php";
}

elseif($path === "/Zoltare/admin" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin']){
        $totalUsers = getUserCount()[0]['COUNT(user_id)'];
        $totalWallpapers = getWallpaperCount()[0]['COUNT(wallpaper_id)'];
        $totalPurchases = getPurchaseCount()[0]['COUNT(purchase_id)'];
        include "./views/admin/admin.php";
    }
    else{
        http_response_code(403); 
    }
}
elseif($path === "/Zoltare/admin/upload" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin']){
        include "./views/admin/admin_upload.php";
    }
    else{
        http_response_code(403); 
    }
}
elseif($path === "/Zoltare/admin/delete" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin']){
        $wallpapers = getWallpaper();
        foreach ($wallpapers as $i => $wp) {
            if (!empty($wp['category']) && is_string($wp['category'])) {
                $wallpapers[$i]['category'] = ucfirst(trim($wp['category']));
            }
        }
        include "./views/admin/admin_delete.php";
    }
    else{
        http_response_code(403); 
    }
}
elseif($path === "/Zoltare/admin/edit" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin']){
        $wallpapers = getWallpaper();
        include "./views/admin/admin_edit.php";
    }
    else{
        http_response_code(403); 
    }
}
elseif($path === "/Zoltare/admin/errors" && $_SERVER['REQUEST_METHOD']==="GET"){

    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin']){
        $errors = getErrors();
        include "./views/admin/admin_errors.php";
    }
    else{
        http_response_code(403); 
    }
  
}

elseif($path === "/Zoltare/payment" && $_SERVER['REQUEST_METHOD']==="POST"){
    session_start();
    $wallpaperID = $_POST['wallpaper_id'];
    if(isset($_SESSION['user_id']) && isset($wallpaperID)){
        include "./views/payment.php";
    }
    else{
        http_response_code(403); 
    }
}
// --

elseif($path === "/Zoltare/upload" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    if (isset($_SESSION['user_id'])) {
        include "./views/upload.php";
    } else {
        header("location:http://localhost/Zoltare/login");
    }
}
elseif($path === "/Zoltare/api/addwallpaper" && $_SERVER['REQUEST_METHOD']==="POST"){
    // Sanitize & validate POST data
    $wallpaperTitle = trim($_POST['title'] ?? '');
    $wallpaperTitle = htmlspecialchars($wallpaperTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $wallpaperCategory = trim($_POST['category'] ?? '');

    // Validation checks
    if ($wallpaperTitle === '' || $wallpaperCategory === '') {
        http_response_code(400);
        echo "Wallpaper Title and category required.";
        exit;
    }

    $f = $_FILES['file'];
    $allowed = ['jpg', 'jpeg', 'png'];
    $maxBytes = 50 * 1024 * 1024; // 50 mb
    if ($f['error'] !== UPLOAD_ERR_OK) {
            echo "
            <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
            <div class='bg-red-100 text-red-700 p-3 rounded'>Upload Error code: {$f['error']}</div></html>";
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed, true)) {
                echo "
                <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                <div class='bg-red-100 text-red-700 p-3 rounded'>Invalid File Type. Must be jpeg, jpg or png</div></html>";
            }
            elseif ($f['size'] > $maxBytes) {
                echo "
                <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                <div class='bg-red-100 text-red-700 p-3 rounded'>File is too large</div></html>";
            }
            else {
                $newName = (new DateTime())->format('YmdHis') . bin2hex(random_bytes(6)) . '.' . $ext;
                $target = './wallpapers/' . $newName;
                if (move_uploaded_file($f['tmp_name'], $target)) {
                    session_start();
                    // Prepare validated data
                    $uploadData = [
                        'title'    => $wallpaperTitle,
                        'category' => $wallpaperCategory,
                        'imageURL' => $target,
                        'userID'   => $_SESSION['user_id']
                    ];

                    // Call DB function
                    $result = addWallpaper($uploadData);

                    if ($result === true) {
                        header("location:http://localhost/Zoltare/gallery");

                    } else {
                        http_response_code(500);
                        echo "
                        <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                        <div class='bg-red-100 text-red-700 p-3 rounded'>Database error occured.</div></html>";
                    }
                } else {
                    echo "
                    <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                    <div class='bg-red-100 text-red-700 p-3 rounded'>Could not save file</div></html>
                    ";
                }
            }
        }
}
elseif($path === "/Zoltare/api/admin/addwallpaper" && $_SERVER['REQUEST_METHOD']==="POST"){
    // Sanitize & validate POST data
    $wallpaperTitle = trim($_POST['title'] ?? '');
    $wallpaperTitle = htmlspecialchars($wallpaperTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $wallpaperCategory = trim($_POST['category'] ?? '');
    $wallpaperPrice = (float)trim($_POST['price'] ?? 0);

    // Validation checks
    if ($wallpaperTitle === '' || $wallpaperCategory === '') {
        http_response_code(400);
        echo "Wallpaper Title and Category required.";
        exit;
    }

    $f = $_FILES['file'];
    $allowed = ['jpg', 'jpeg', 'png'];
    $maxBytes = 50 * 1024 * 1024; // 50 mb
    if ($f['error'] !== UPLOAD_ERR_OK) {
            echo "
            <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
            <div class='bg-red-100 text-red-700 p-3 rounded'>Upload Error code: {$f['error']}</div></html>";
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed, true)) {
                echo "
                <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                <div class='bg-red-100 text-red-700 p-3 rounded'>Invalid File Type. Must be jpeg, jpg or png</div></html>";
            }
            elseif ($f['size'] > $maxBytes) {
                echo "
                <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                <div class='bg-red-100 text-red-700 p-3 rounded'>File is too large</div></html>";
            }
            else {
                $newName = (new DateTime())->format('YmdHis') . bin2hex(random_bytes(6)) . '.' . $ext;
                $target = './wallpapers/' . $newName;
                if (move_uploaded_file($f['tmp_name'], $target)) {
                    session_start();
                    // Prepare validated data
                    $uploadData = [
                        'title'    => $wallpaperTitle,
                        'category' => $wallpaperCategory,
                        'imageURL' => $target,
                        'userID'   => $_SESSION['user_id'],
                        'price'    => $wallpaperPrice
                    ];

                    // Call DB function
                    $result = addAdminWallpaper($uploadData);

                    if ($result === true) {
                        header("location:http://localhost/Zoltare/admin");

                    } else {
                        http_response_code(500);
                        echo "
                        <html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                        <div class='bg-red-100 text-red-700 p-3 rounded'>Database error occured.</div></html>";
                    }
                } else {
                    echo "<html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
                    <div class='bg-red-100 text-red-700 p-3 rounded'>Could not save file</div></html>";
                }
            }
        }
}
elseif($path === "/Zoltare/api/admin/deletewallpaper" && $_SERVER['REQUEST_METHOD'] === "POST"){
    $wallpaperID = $_POST['wallpaper_id'];
    $filename = $_POST['filepath'];
    if (file_exists($filename)) {
        if (unlink($filename)) {
            deleteWallpaper($wallpaperID);
            header("location:http://localhost/Zoltare/admin/delete");
        } else {
            echo "<html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
            <div class='bg-red-100 text-red-700 p-3 rounded'>Could not delete file {$filename}</div></html>";
        }
    } else {
        echo "<html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
        <div class='bg-red-100 text-red-700 p-3 rounded'>File does not exist {$filename}</div></html>";
    }
}
elseif ($path === "/Zoltare/api/admin/editwallpaper" && $_SERVER['REQUEST_METHOD'] === "POST") {
    $wallpaperID = isset($_POST['wallpaper_id']) ? (int)$_POST['wallpaper_id'] : 0;
    if ($wallpaperID <= 0) {
        http_response_code(400);
        echo "Invalid wallpaper ID.";
        exit;
    }

    // Sanitize & validate POST data
    $wallpaperTitle = trim($_POST['wallpaper_title'] ?? '');
    $wallpaperCategory = trim($_POST['wallpaper_category'] ?? '');
    $wallpaperPrice = (float)trim($_POST['wallpaper_price'] ?? 0);

    // Validation checks
    if ($wallpaperTitle === '' || $wallpaperCategory === '') {
        http_response_code(400);
        echo "Wallpaper Title and Category required.";
        exit;
    }

    $updateData = [
        'wp_id'    => $wallpaperID,
        'title'    => $wallpaperTitle,
        'category' => $wallpaperCategory,
        'price'    => $wallpaperPrice
        ];

    $success = updateWallpaper($updateData);
    if ($success) {
        header("location:http://localhost/Zoltare/admin/edit");
    } else {
        http_response_code(500);
        echo "<html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
        <div class='bg-red-100 text-red-700 p-3 rounded'>Failed to update wallpaper</div></html>";
    }
}

elseif($path === "/Zoltare/gallery" && $_SERVER['REQUEST_METHOD']==="GET"){
    session_start();
    $wallpapers = getWallpaper();
    foreach ($wallpapers as $i => $wp) {
        if (!empty($wp['category']) && is_string($wp['category'])) {
            $wallpapers[$i]['category'] = ucfirst(trim($wp['category']));
        }
    }

    if (isset($_SESSION['user_id'])) {
        $paidWallpapers = getPurchasedWallpaper((int)$_SESSION['user_id']);
        include "./views/gallery.php";
    }
    else {
        include "./views/gallery.php";
    }
}
elseif ($path === "/Zoltare/api/submitpayment" && $_SERVER['REQUEST_METHOD'] === "POST") {
    session_start();
    $wallpaperID = isset($_POST['wallpaper_id']) ? (int)$_POST['wallpaper_id'] : 0;
    if ($wallpaperID <= 0) {
        http_response_code(400);
        echo "Invalid wallpaper ID.";
        exit;
    }

    $paymentData = [
        'wp_id'    => $wallpaperID,
        'user_id'  => $_SESSION['user_id']
        ];

    $success = purchaseWallpaper($paymentData);
    if ($success) {
        header("location:http://localhost/Zoltare/gallery");
    } else {
        http_response_code(500);
        echo "<html><head><script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script></head>
        <div class='bg-red-100 text-red-700 p-3 rounded'>Failed to make payment</div></html>";
    }
}
?>