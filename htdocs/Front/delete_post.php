<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$conn = require __DIR__ . "/database.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Use prepared statements to avoid SQL injection
$stmt_comments = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
$stmt_comments->bind_param("i", $post_id);

$stmt_post = $conn->prepare("DELETE FROM post WHERE post_id = ?");
$stmt_post->bind_param("i", $post_id);

if ($stmt_comments->execute() && $stmt_post->execute()) {
    header("Location: index.php");
    die();
} else {
    echo "Greška prilikom brisanja objave " . $conn->error;
}

$stmt_comments->close();
$stmt_post->close();
$conn->close();
?>
