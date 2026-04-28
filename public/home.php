<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function resolve_media_path($path, $fallback)
{
    $path = trim((string) $path);

    if ($path === '') {
        return $fallback;
    }

    if (preg_match('~^(https?:)?//~', $path)) {
        return $path;
    }

    $relativePath = ltrim($path, '/');

    if (file_exists(__DIR__ . '/' . $relativePath)) {
        return $relativePath;
    }

    return $fallback;
}

function short_text($text, $limit)
{
    $text = trim((string) $text);

    if ($text === '' || strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit - 3)) . '...';
}

function professional_title($title)
{
    $title = trim((string) $title);
    $normalized = strtolower($title);

    if ($title === '' || $normalized === 'sample movie') {
        return 'Project Hail Mary';
    }

    return $title;
}

function professional_summary($title, $description)
{
    $description = trim((string) $description);
    $normalizedTitle = strtolower(trim((string) $title));

    if ($description !== '') {
        return short_text($description, 118);
    }

    if ($normalizedTitle === 'sample movie' || $normalizedTitle === '') {
        return 'A spotlight title from the current lineup, presented in a cleaner premium streaming layout.';
    }

    return 'Now available to browse on AvocadoFlix.';
}

$posterFallback = 'assets/images/project_hail_mary_ver3.jpg';
$heroFallback = 'assets/images/wicked_for_good_ver21.jpg';
$curatedPosters = array(
    array(
        'title' => 'Hoppers',
        'image' => 'assets/images/hoppers_ver2_xlg.jpg',
    ),
    array(
        'title' => 'Jackass Forever',
        'image' => 'assets/images/jackass_best_and_last_xlg.jpg',
    ),
    array(
        'title' => 'Project Hail Mary',
        'image' => 'assets/images/project_hail_mary_ver3.jpg',
    ),
    array(
        'title' => 'Super Mario Galaxy',
        'image' => 'assets/images/super_mario_galaxy_movie_ver12_xlg.jpg',
    ),
    array(
        'title' => 'Wicked: For Good',
        'image' => 'assets/images/wicked_for_good_ver21.jpg',
    ),
    array(
        'title' => 'Zootopia 2',
        'image' => 'assets/images/zootopia_two_ver9.jpg',
    ),
    array(
        'title' => 'Frankenstein',
        'image' => 'assets/images/frankenstein_ver9.jpg',
    ),
    array(
        'title' => 'G.I. Joe',
        'image' => 'assets/images/g_i_joe_ver14.jpg',
    ),
    array(
        'title' => 'Hachiko: A Dog\'s Story',
        'image' => 'assets/images/hachiko_a_dogs_story_ver3.jpg',
    ),
    array(
        'title' => 'Avatar',
        'image' => 'assets/images/fire_and_water_making_the_avatar_films.jpg',
    ),
);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$movies = array();

$result = $conn->query("SELECT * FROM movies ORDER BY id DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['thumbnail_display'] = resolve_media_path($row['thumbnail'] ?? '', $posterFallback);
        $row['display_title'] = professional_title($row['title'] ?? '');
        $row['summary'] = professional_summary($row['title'] ?? '', $row['description'] ?? '');
        $movies[] = $row;
    }
}

$featuredMovie = count($movies) > 0 ? $movies[0] : null;
$featuredImage = $featuredMovie ? $featuredMovie['thumbnail_display'] : $heroFallback;
$featuredTitle = $featuredMovie ? $featuredMovie['display_title'] : 'Tonight on AvocadoFlix';
$featuredCopy = $featuredMovie && !empty($featuredMovie['description'])
    ? short_text($featuredMovie['description'], 150)
    : 'Discover spotlight premieres, polished rows, and a sharper streaming experience built around the AvocadoFlix look.';
$editorialPicks = array_slice($curatedPosters, 0, 5);
$comingSoon = array_slice($curatedPosters, 5);
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
<body class="home-page">
    <div class="app-shell app-shell-home">
        <header class="topbar">
            <a class="brand-badge" href="home.php">
                <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                <span class="brand-copy">
                    <span class="brand-wordmark">AvocadoFlix</span>
                    <span class="brand-subtitle">Movie library</span>
                </span>
            </a>

            <nav class="topbar-nav">
                <a class="nav-chip is-active" href="home.php">Home</a>
                <a class="nav-chip" href="#library">Library</a>
            </nav>

            <div class="topbar-actions">
                <span class="pill">Hi, <?php echo htmlspecialchars($username); ?></span>
                <a class="button button-soft" href="logout.php">Logout</a>
            </div>
        </header>

        <main class="home-main">
            <section class="feature-stage feature-stage-cinematic reveal">
                <img class="feature-backdrop" src="<?php echo htmlspecialchars($featuredImage); ?>" alt="<?php echo htmlspecialchars($featuredTitle); ?> artwork">
                <div class="feature-curtain"></div>

                <div class="feature-copy feature-copy-cinematic hero-fade">
                    <span class="eyebrow">Featured tonight</span>
                    <h1><?php echo htmlspecialchars($featuredTitle); ?></h1>
                    <p><?php echo htmlspecialchars($featuredCopy); ?></p>

                    <div class="feature-actions">
                        <?php if ($featuredMovie): ?>
                            <a class="button button-primary" href="watch.php?id=<?php echo urlencode($featuredMovie['id']); ?>">Watch</a>
                        <?php endif; ?>
                        <a class="button button-soft" href="#library">More info</a>
                    </div>
                </div>

                <div class="feature-spotlight reveal" data-tilt>
                    <img src="<?php echo htmlspecialchars($curatedPosters[2]['image']); ?>" alt="<?php echo htmlspecialchars($curatedPosters[2]['title']); ?> poster">
                </div>
            </section>

            <section class="shelf-section shelf-netflix" id="library">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Continue watching</span>
                        <h2>Continue watching</h2>
                    </div>
                    <p>Resume standout titles from your account.</p>
                </div>

                <?php if (count($movies) > 0): ?>
                    <div class="movie-rail movie-rail-netflix">
                        <?php foreach ($movies as $movie): ?>
                            <article class="poster-card poster-card-netflix reveal">
                                <a class="poster-link" href="watch.php?id=<?php echo urlencode($movie['id']); ?>">
                                    <div class="poster-frame">
                                        <img src="<?php echo htmlspecialchars($movie['thumbnail_display']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> poster">
                                    </div>
                                    <div class="poster-meta">
                                        <h3><?php echo htmlspecialchars($movie['display_title']); ?></h3>
                                        <p><?php echo htmlspecialchars($movie['summary']); ?></p>
                                        <span class="inline-link">Play</span>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="assets/images/jackass_best_and_last_xlg.jpg" alt="AvocadoFlix poster placeholder">
                        <div>
                            <h3>No movies added yet</h3>
                            <p>Add records to the `movies` table and they will appear here automatically.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <section class="shelf-section shelf-netflix">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Hand-picked posters</span>
                        <h2>Trending now</h2>
                    </div>
                    <p>Fresh picks across adventure, family, comedy, and sci-fi.</p>
                </div>

                <div class="movie-rail movie-rail-netflix">
                    <?php foreach ($editorialPicks as $poster): ?>
                        <article class="poster-card poster-card-netflix poster-card-static reveal">
                            <div class="poster-link">
                                <div class="poster-frame">
                                    <img src="<?php echo htmlspecialchars($poster['image']); ?>" alt="<?php echo htmlspecialchars($poster['title']); ?> poster">
                                </div>
                                <div class="poster-meta">
                                    <h3><?php echo htmlspecialchars($poster['title']); ?></h3>
                                    <p>Streaming this week on the AvocadoFlix front page.</p>
                                    <span class="inline-link">Explore title</span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="shelf-section shelf-netflix">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Coming soon</span>
                        <h2>Worth the wait</h2>
                    </div>
                    <p>A second row built from your latest artwork uploads.</p>
                </div>

                <div class="movie-rail movie-rail-netflix">
                    <?php foreach ($comingSoon as $poster): ?>
                        <article class="poster-card poster-card-netflix poster-card-static reveal">
                            <div class="poster-link">
                                <div class="poster-frame">
                                    <img src="<?php echo htmlspecialchars($poster['image']); ?>" alt="<?php echo htmlspecialchars($poster['title']); ?> poster">
                                </div>
                                <div class="poster-meta">
                                    <h3><?php echo htmlspecialchars($poster['title']); ?></h3>
                                    <p>Featured artwork staged for the streaming-style catalog.</p>
                                    <span class="inline-link">Coming soon</span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
