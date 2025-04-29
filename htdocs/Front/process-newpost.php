<?php

if (empty($_POST["naslov"])) {
    die("Naslov je obavezan!");
}

if (empty($_POST["tekst"])) {
    die("Tekst je obavezan!");
}

session_start();

if (isset($_SESSION["user_id"])) {                      //koristimo user id koji je sacuvan u sessionu kako bi dobili ostatak podataka o useru (treba nam ime da stavimo u tablicu post)
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE user_id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}


var_dump($user["user_id"]);


$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO post (user_id, title, post_text)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $user["user_id"],
                  $_POST["naslov"],
                  $_POST["tekst"]);
                  



try {                                                       //try-catch metoda error handlinga ak budes u dokumenatciji
    if ($stmt->execute()) {
        header("Location: index.php");
    exit;
    }
} catch (mysqli_sql_exception $e) {
        die("Database error: " . $e->getMessage());
}
                
