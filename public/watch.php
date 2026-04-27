<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvocadoFlix | Watch</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <div class="brand-badge">
                <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                <span class="brand-wordmark">AvocadoFlix</span>
            </div>

            <div class="topbar-actions">
                <a class="back-link" href="home.php">Back to home</a>
                <a class="logout-link" href="logout.php">Logout</a>
            </div>
        </header>

        <main class="watch-shell">
            <?php if ($movie): ?>
                <div class="watch-layout">
                    <section class="player-panel reveal">
                        <div class="player-top">
                            <div>
                                <div class="kicker">Now playing</div>
                                <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                            </div>
                        </div>

                        <div class="player-frame">
                            <video controls>
                                <source src="<?php echo htmlspecialchars($movie['video_path']); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <div class="player-description">
                            <?php
                            if (!empty($movie['description'])) {
                                echo htmlspecialchars($movie['description']);
                            } else {
                                echo "This title is now ready to stream inside your protected AvocadoFlix player.";
                            }
                            ?>
                        </div>
                    </section>

                    <aside class="watch-info">
                        <div class="watch-poster reveal" data-tilt>
                            <?php if (!empty($movie['thumbnail'])): ?>
                                <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> poster">
                            <?php else: ?>
                                <img src="assets/images/logo.png" alt="AvocadoFlix placeholder poster">
                            <?php endif; ?>
                        </div>

                        <section class="panel reveal">
                            <h2>About this movie</h2>
                            <p>
                                <?php
                                if (!empty($movie['description'])) {
                                    echo htmlspecialchars($movie['description']);
                                } else {
                                    echo "No description was provided for this title yet.";
                                }
                                ?>
                            </p>

                            <div class="info-list">
                                <div class="info-row">
                                    <strong>Access control</strong>
                                    <p>This player stays behind session-based authentication.</p>
                                </div>
                                <div class="info-row">
                                    <strong>Source</strong>
                                    <p>Video is loaded from the path stored in your existing `movies` table.</p>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    Movie not found. Head back to the home page and choose another title.
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
