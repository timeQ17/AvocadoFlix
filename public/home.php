<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM movies");
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$movies = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}

$featuredMovie = count($movies) > 0 ? $movies[0] : null;
$heroStyle = '';

if ($featuredMovie && !empty($featuredMovie['thumbnail'])) {
    $heroStyle = " style=\"background-image: linear-gradient(90deg, rgba(4, 8, 7, 0.96) 0%, rgba(4, 8, 7, 0.74) 44%, rgba(4, 8, 7, 0.34) 100%), url('" . htmlspecialchars($featuredMovie['thumbnail'], ENT_QUOTES) . "');\"";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvocadoFlix | Home</title>
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
                <div class="pill">Welcome, <?php echo htmlspecialchars($username); ?></div>
                <a class="logout-link" href="logout.php">Logout</a>
            </div>
        </header>

        <section class="hero<?php echo $featuredMovie ? ' has-poster' : ''; ?>"<?php echo $heroStyle; ?>>
            <div class="hero-inner hero-fade">
                <div class="kicker">Protected movie dashboard</div>
                <h1 class="hero-title">
                    <?php echo $featuredMovie ? htmlspecialchars($featuredMovie['title']) : 'Your movie night starts here.'; ?>
                </h1>
                <p class="hero-copy">
                    <?php if ($featuredMovie && !empty($featuredMovie['description'])): ?>
                        <?php echo htmlspecialchars($featuredMovie['description']); ?>
                    <?php else: ?>
                        Browse the current collection, open your player instantly, and enjoy a warmer streaming interface built around the AvocadoFlix identity.
                    <?php endif; ?>
                </p>

                <div class="hero-actions">
                    <?php if ($featuredMovie): ?>
                        <a class="watch-button" href="watch.php?id=<?php echo urlencode($featuredMovie['id']); ?>">Watch featured</a>
                    <?php endif; ?>
                    <a class="outline-link" href="#movie-grid">Browse movies</a>
                </div>
            </div>

            <div class="hero-rail reveal">
                <div class="hero-meta">
                    <div class="pill">Session protected</div>
                    <div class="pill">PHP + MySQL</div>
                    <div class="pill">Student streaming project</div>
                </div>

                <aside class="hero-card" data-tilt>
                    <h3>AvocadoFlix mood</h3>
                    <p>The mascot, greens, and popcorn-yellow highlights turn the dashboard into something closer to a movie venue than a plain list of links.</p>
                </aside>
            </div>
        </section>

        <main class="content-wrap">
            <section class="section-head" id="movie-grid">
                <div>
                    <h2>Now showing</h2>
                    <p>Your current movie library, presented with a little more atmosphere and depth.</p>
                </div>
            </section>

            <?php if (count($movies) > 0): ?>
                <div class="movie-grid">
                    <?php foreach ($movies as $movie): ?>
                        <article class="movie-card reveal" data-tilt>
                            <div class="movie-poster">
                                <?php if (!empty($movie['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> poster">
                                <?php else: ?>
                                    <img src="assets/images/logo.png" alt="AvocadoFlix placeholder poster">
                                <?php endif; ?>
                            </div>

                            <div class="movie-body">
                                <h3 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                <p class="movie-description">
                                    <?php
                                    if (!empty($movie['description'])) {
                                        echo htmlspecialchars($movie['description']);
                                    } else {
                                        echo "A featured title from your AvocadoFlix collection, ready to stream inside the protected player.";
                                    }
                                    ?>
                                </p>
                                <a class="watch-button" href="watch.php?id=<?php echo urlencode($movie['id']); ?>">Watch now</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    No movies found yet. Once records are added to the `movies` table, they will appear here automatically.
                </div>
            <?php endif; ?>
        </main>

        <footer class="footer-copy">
            AvocadoFlix blends protected access control with a brighter, theater-inspired movie experience.
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
