<?php

if (empty($_POST["name"])) {
    die("Nije upisano ime!");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Nije unesen ispravan email!");
}

if (strlen($_POST["password"]) < 8) {
    die("Lozinka mora sadržavati najmanje 8 znakova!");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Lozinka mora sadržavati najmanje jedno slovo!");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Lozinka mora sadržavati najmanje 1 broj!");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Lozinke se ne podudaraju!");
}


$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);



$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
                  



try {                                                      
    if ($stmt->execute()) {
        header("Location: signup-success.html");
    exit;
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        
        die("Email already taken");
    } else {
        
        die("Database error: " . $e->getMessage());
    }
}
                
    
    

?>