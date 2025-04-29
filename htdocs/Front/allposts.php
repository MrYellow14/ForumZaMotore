<?php
$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM post";

$sql = "SELECT post.post_id, post.title, post.post_text, post.created_at, user.name AS creator_name
        FROM post
        JOIN user ON post.user_id = user.id
        ORDER BY post.created_at DESC";

$result = $mysqli->query($sql);

// Display Posts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($row['post_text'])) . "</p>";
        echo "<span class='meta'>Posted by " . htmlspecialchars($row['creator_name']) . 
             " on " . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</span>";
        echo "</div>";
    }
} else {
    echo "<p>No posts found.</p>";
}

$mysqli->close();
?>