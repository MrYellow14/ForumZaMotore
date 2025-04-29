<?php


if (empty($_POST["tekst"])) {
    die("Unesi tekst!");
}

session_start();


if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    echo "Post ID received: " . htmlspecialchars($post_id);
} else {
    echo "Post ID not provided.";
}



if (isset($_SESSION["user_id"])) {                      
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE user_id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}


var_dump($user["user_id"]);


$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO comments (user_id, post_id, comment_text)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $user["user_id"],
                  $post_id ,
                  $_POST["tekst"]);
                  
try {                                                       
    if ($stmt->execute()) {
        header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
    }
} catch (mysqli_sql_exception $e) {
        die("Database error: " . $e->getMessage());
}
                
