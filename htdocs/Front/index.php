<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE user_id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    $loggedUserID = $_SESSION['user_id'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link id="theme" rel="stylesheet" href="css\style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php" style="text-decoration: none;"><h1>MotoZona.hr</h1></a>
    </header>
    <nav>
        <?php if (isset($user)): ?>
        
        <p><?= htmlspecialchars($user["name"]) ?></p>
        <a class="logout" href="logout.php">Log out</a>

        
        <?php else: ?>
        
        <a href="signup.html" class="signup">Sign up</a>
        <a href="login.php">Log in</a>
        
    <?php endif; ?>
    </nav>


    <main>
        <?php
            $mysqli = require __DIR__ . "/database.php";


            $sql = "SELECT post.post_id, post.title, post.post_text, post.user_id, post.created_at, user.name AS creator_name
                    FROM post
                    JOIN user ON post.user_id = user.user_id
                    ORDER BY post.created_at DESC";

            $result = $mysqli->query($sql);

            // Ispisuje sve postove
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $postId = htmlspecialchars($row['post_id']);
                    $title = htmlspecialchars($row['title']);
                    $text = nl2br(substr(htmlspecialchars($row['post_text']), 0, 250)) . '...';
                    $creator = htmlspecialchars($row['creator_name']);
                    $date = date('d.m.Y - H:i:s', strtotime($row['created_at']));
                    $creatorID = $row['user_id'];


                    echo "<a href='view_post.php?post_id=$postId' class='post-link'>";
                    echo "<div class='post'>";
                    echo "<h2>$title</h2>";
                    echo "<p>$text</p>";
                    echo "<span class='meta'>Objavio $creator ($date)</span>";
                    if (isset($user)) {
                        if ($loggedUserID == $creatorID) {
                            echo "<a href='delete_post.php?post_id=$postId'>Obriši</a>";
                        }
                    }
                    
                    echo "</div>";
                    echo "</a>";
                }
            } else {
                echo "<p>Nema objava</p>";
            }

            $mysqli->close();
        ?>

            <?php if (isset($user)): ?>
                <a href="newpost.html">
                    <div class="sectionNewPost">
                    <button class="Post"><img src="Images\NewPostWhite.svg"></button>
                    </div>
                </a>  
                <?php else: ?>
            
            <?php endif; ?>
    </main>

    <section>
        <div class="sectionBorder">
        </div>
        <div class="sectionContent">
            <p><br>OSNOVNA PRAVILA: <br><br>Poštujte druge članove.<br><br>Objavljujte relevantne teme.<br><br>Izbjegavajte reklamu.<br><br>Ne dijelite tuđe osobne podatke.<br><br>Ne objavljujte neprimjeren sadržaj.</p>
        </div>
    </section>

    <footer>
        <p>Jakov Huđin</p>
        <p>Završni rad</p>
        <p>Mentor: Mladen Savić</p>
    </footer>
</body>
</html>

