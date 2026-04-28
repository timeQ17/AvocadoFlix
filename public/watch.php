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

function resolve_video_path($path)
{
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('~^(https?:)?//~', $path)) {
        return $path;
    }

    $relativePath = ltrim($path, '/');

    if (file_exists(__DIR__ . '/' . $relativePath)) {
        return $relativePath;
    }

    return '';
}

function professional_title($title)
{
    $title = trim((string) $title);

    if ($title === '' || strtolower($title) === 'sample movie') {
        return 'Project Hail Mary';
    }

    return $title;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$posterFallback = 'assets/images/project_hail_mary_ver3.jpg';

$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if ($movie) {
    $movie['thumbnail_display'] = resolve_media_path($movie['thumbnail'] ?? '', $posterFallback);
    $movie['video_display'] = resolve_video_path($movie['video_path'] ?? '');
    $movie['display_title'] = professional_title($movie['title'] ?? '');
}
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
    <div class="app-shell app-shell-home">
        <header class="topbar">
            <a class="brand-badge" href="home.php">
                <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                <span class="brand-copy">
                    <span class="brand-wordmark">AvocadoFlix</span>
                    <span class="brand-subtitle">Watch page</span>
                </span>
            </a>

            <div class="topbar-actions">
                <a class="button button-soft" href="home.php">Back to home</a>
                <a class="button button-soft" href="logout.php">Logout</a>
            </div>
        </header>

        <main class="watch-main">
            <?php if ($movie): ?>
                <section class="watch-layout">
                    <div class="player-panel reveal">
                        <div class="player-head">
                            <span class="eyebrow">Trailer</span>
                            <h1><?php echo htmlspecialchars($movie['display_title']); ?></h1>
                        </div>

                        <?php if ($movie['video_display'] !== ''): ?>
                            <div class="player-frame">
                                <video controls poster="<?php echo htmlspecialchars($movie['thumbnail_display']); ?>">
                                    <source src="<?php echo htmlspecialchars($movie['video_display']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="watch-actions-panel">
                                <div class="action-copy">
                                    <span class="eyebrow">Instant access</span>
                                    <h2>Watch the official trailer now</h2>
                                    <p>Preview the film immediately, then unlock the full feature with an AvocadoFlix subscription.</p>
                                </div>

                                <div class="cta-stack">
                                    <a class="button button-primary button-hero" href="<?php echo htmlspecialchars($movie['video_display']); ?>">Watch trailer</a>
                                    <button class="button button-soft button-locked button-outline-hero" type="button" data-subscribe-open>Unlock full movie</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="player-empty">
                                <img src="<?php echo htmlspecialchars($movie['thumbnail_display']); ?>" alt="<?php echo htmlspecialchars($movie['display_title']); ?> poster">
                                <div>
                                    <h2>Playback preview</h2>
                                    <p>This title is staged in the catalog now. Add a valid `video_path` later and the player will go live automatically.</p>
                                </div>
                            </div>
                            <div class="cta-stack">
                                <button class="button button-soft button-locked" type="button" data-subscribe-open>Watch movie • Subscribe first</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <aside class="watch-sidebar">
                        <div class="watch-poster reveal" data-tilt>
                            <img src="<?php echo htmlspecialchars($movie['thumbnail_display']); ?>" alt="<?php echo htmlspecialchars($movie['display_title']); ?> poster">
                        </div>

                        <div class="info-panel reveal">
                            <span class="eyebrow">Overview</span>
                            <p>
                                <?php
                                echo !empty($movie['description'])
                                    ? htmlspecialchars($movie['description'])
                                    : 'A featured catalog title presented with polished artwork and a ready-to-activate player layout.';
                                ?>
                            </p>

                            <div class="info-pills">
                                <span class="pill">Trailer available</span>
                                <span class="pill">Movie requires subscription</span>
                            </div>

                            <div class="subscription-note">
                                <h3>Unlock the full movie</h3>
                                <p>Watch the trailer now. To start the full film, activate a subscription plan first.</p>
                            </div>
                        </div>
                    </aside>
                </section>

                <div class="modal-shell" data-subscribe-modal hidden>
                    <div class="modal-backdrop" data-subscribe-close></div>
                    <section class="subscribe-modal" role="dialog" aria-modal="true" aria-labelledby="subscribe-title">
                        <button class="modal-close" type="button" aria-label="Close subscription modal" data-subscribe-close>&times;</button>
                        <span class="eyebrow">Subscription required</span>
                        <h2 id="subscribe-title">Choose a plan to watch the full movie</h2>
                        <p class="modal-copy">The trailer is open now. Upgrade your account to unlock the full streaming catalog, higher quality playback, and premiere access.</p>

                        <div class="plan-grid">
                            <article class="plan-card">
                                <div class="plan-name">Mobile</div>
                                <div class="plan-price">PHP 149<span>/month</span></div>
                                <p>One screen, standard access, perfect for quick trailer-to-movie upgrades.</p>
                            </article>
                            <article class="plan-card plan-card-featured">
                                <div class="plan-badge">Most popular</div>
                                <div class="plan-name">Standard</div>
                                <div class="plan-price">PHP 279<span>/month</span></div>
                                <p>Full movie access, HD playback, and simultaneous streaming on two devices.</p>
                            </article>
                            <article class="plan-card">
                                <div class="plan-name">Premium</div>
                                <div class="plan-price">PHP 429<span>/month</span></div>
                                <p>Best quality, larger household access, and first-look lineup drops.</p>
                            </article>
                        </div>

                        <div class="modal-actions">
                            <a class="button button-primary" href="register.php">Start subscription</a>
                            <button class="button button-soft" type="button" data-subscribe-close>Maybe later</button>
                        </div>
                    </section>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <img src="assets/images/jackass_best_and_last_xlg.jpg" alt="AvocadoFlix poster placeholder">
                    <div>
                        <h3>Movie not found</h3>
                        <p>Return to the home page and choose another title.</p>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
