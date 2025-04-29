<?php
session_start();
if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE user_id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}



$conn = require __DIR__ . "/database.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

$sql = "SELECT post.title, post.post_text, post.created_at, user.name AS creator_name
        FROM post
        JOIN user ON post.user_id = user.user_id
        WHERE post.post_id = ?
        ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Post not found.");
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?></title>
    <link id="theme" rel="stylesheet" href="css\viewPostStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="scripts\animacija.js"></script>
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
        <div class="post">
            <h1><?php echo htmlspecialchars($row['title']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($row['post_text'])); ?></p>
            <span class="meta">Objavio <?php echo htmlspecialchars($row['creator_name']); ?> 
                (<?php echo date('d.m.Y - H:i:s', strtotime($row['created_at'])); ?>)
            </span>
        </div>

        <div class="comments">
        <?php
            $mysqli = require __DIR__ . "/database.php";


            $sql_comments = "SELECT comments.comment_text, comments.created_at, user.name AS commentor_name
                    FROM comments
                    JOIN user ON comments.user_id = user.user_id
                    WHERE comments.post_id = $post_id
                    ORDER BY comments.created_at ASC";

            $result_comments = $mysqli->query($sql_comments);

            // Ispisuje sve komentare
            if ($result_comments->num_rows > 0) {
                while ($row = $result_comments->fetch_assoc()) {
                    echo "<div class='comment'>";
                    echo "<p>" . nl2br(htmlspecialchars($row['comment_text'])) . "</p>";
                    echo "<span class='meta'>Komentirao " . htmlspecialchars($row['commentor_name']) . 
                        " (" . date('d.m.Y - H:i:s', strtotime($row['created_at'])) . ")</span>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nema komentara</p>";
            }

            $mysqli->close();
        ?>
        
        </div>

        <div class="commentBox">
            <div class="naslovKomentara">
                <h3>Komentiraj</h3>
                <button class="odustani" onclick="closeComment()"><img src="Images\ExitWhite.svg"></button>
            </div>
            <form action="process-newcomment.php" onsubmit="return validateCommentForm()" method="POST" id="newcomment">
                <div class="tekst">
                    <textarea id="tekst" name="tekst"></textarea>
                </div>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button class="objavi">Objavi komentar</button>
            </form>
        </div>

    </main>
    

    <section>
        <div class="sectionBorder">
        </div>
        <div class="sectionContent">
        <p><br>OSNOVNA PRAVILA: <br><br>Poštujte druge članove.<br><br>Objavljujte relevantne teme.<br><br>Izbjegavajte reklamu.<br><br>Ne dijelite tuđe osobne podatke.<br><br>Ne objavljujte neprimjeren sadržaj.</p>

            <?php if (isset($user)): ?>
            <div class="sectionNewPost">
                <a onclick="openComment()">Komentiraj</a>
            </div>  

            <?php else: ?>
            
            <?php endif; ?>
        </div>
    </section>


    <footer>
        <p>Jakov Huđin</p>
        <p>Završni rad</p>
        <p>Mentor: Mladen Savić</p>
    </footer>
</body>
</html>

<script>
    function validateCommentForm(){
        var tekst = document.forms["newcomment"]["tekst"].value;
        if (tekst == ""){
            alert("Unesi tekst komentara!");
            return false;
        }
        if (text.length>255){
            alert("Maksimalna velicina komentara je 255 znakova!");
            return false;
        }
    }
</script>  