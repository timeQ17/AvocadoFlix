<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM movies");
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avocadoflix | Home</title>
    <style>
        :root {
            --bg: #07110d;
            --bg-soft: #0d1913;
            --surface: rgba(12, 25, 19, 0.88);
            --surface-strong: rgba(16, 34, 25, 0.96);
            --primary: #8fd14f;
            --primary-strong: #6fb73c;
            --accent: #f3c969;
            --text: #f6f2e8;
            --muted: #b7c3b5;
            --border: rgba(143, 209, 79, 0.16);
            --shadow: 0 24px 70px rgba(0, 0, 0, 0.38);
            --radius-xl: 30px;
            --radius-lg: 22px;
            --radius-md: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(143, 209, 79, 0.14), transparent 24%),
                radial-gradient(circle at 15% 20%, rgba(243, 201, 105, 0.1), transparent 20%),
                linear-gradient(180deg, #040907 0%, #07110d 26%, #08140f 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page-shell {
            width: min(1280px, calc(100% - 32px));
            margin: 18px auto 40px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 34px;
            overflow: hidden;
            background: rgba(7, 17, 13, 0.76);
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 28px;
            background: rgba(6, 14, 11, 0.78);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(14px);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: radial-gradient(circle at 35% 35%, #b6ed72 0%, #95d956 38%, #4f7d2d 100%);
            box-shadow: 0 10px 24px rgba(111, 183, 60, 0.35);
            position: relative;
        }

        .logo-mark::after {
            content: "";
            position: absolute;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #553421;
            top: 15px;
            left: 15px;
            box-shadow: 0 0 0 4px rgba(255, 235, 192, 0.16);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .welcome-pill,
        .logout-link {
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
        }

        .welcome-pill {
            color: var(--muted);
        }

        .logout-link {
            background: linear-gradient(135deg, var(--primary) 0%, #b9eb68 100%);
            color: #10210f;
            font-weight: 800;
            box-shadow: 0 16px 30px rgba(111, 183, 60, 0.22);
        }

        .hero {
            padding: 68px 32px 28px;
            background:
                linear-gradient(90deg, rgba(4, 8, 7, 0.96) 0%, rgba(4, 8, 7, 0.72) 46%, rgba(4, 8, 7, 0.2) 100%),
                radial-gradient(circle at 78% 20%, rgba(143, 209, 79, 0.2), transparent 24%),
                linear-gradient(160deg, #102118 0%, #09110d 100%);
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(143, 209, 79, 0.08);
            color: #d8efb8;
            border: 1px solid rgba(143, 209, 79, 0.14);
            font-size: 0.84rem;
            margin-bottom: 18px;
        }

        .hero-tag::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 12px rgba(143, 209, 79, 0.65);
        }

        .hero h1 {
            margin: 0 0 16px;
            max-width: 11ch;
            font-size: clamp(2.8rem, 6vw, 5rem);
            line-height: 0.92;
            letter-spacing: -0.05em;
        }

        .hero p {
            margin: 0;
            max-width: 40rem;
            color: var(--muted);
            line-height: 1.8;
            font-size: 1.02rem;
        }

        .content {
            padding: 28px;
        }

        .section-header {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 1.6rem;
            letter-spacing: -0.03em;
        }

        .section-header p {
            margin: 6px 0 0;
            color: var(--muted);
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .movie-card {
            border-radius: 26px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(12, 26, 19, 0.78);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .movie-poster {
            aspect-ratio: 4 / 5;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(0, 0, 0, 0.78));
            overflow: hidden;
        }

        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .movie-body {
            padding: 18px;
        }

        .movie-title {
            margin: 0 0 12px;
            font-size: 1.1rem;
            line-height: 1.35;
        }

        .watch-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary) 0%, #b9eb68 100%);
            color: #10210f;
            font-weight: 800;
            box-shadow: 0 12px 24px rgba(111, 183, 60, 0.24);
        }

        .empty-state {
            padding: 32px;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(12, 26, 19, 0.72);
            color: var(--muted);
        }

        .footer {
            padding: 0 28px 28px;
            color: #90a08f;
            font-size: 0.92rem;
        }

        @media (max-width: 820px) {
            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-right {
                justify-content: space-between;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .page-shell {
                width: min(100% - 16px, 100%);
                margin: 8px auto 20px;
                border-radius: 24px;
            }

            .topbar,
            .content,
            .footer {
                padding-left: 18px;
                padding-right: 18px;
            }

            .hero {
                padding: 50px 18px 22px;
            }

            .hero h1 {
                max-width: none;
                font-size: 2.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <div class="logo">
                <span class="logo-mark"></span>
                <span>Avocadoflix</span>
            </div>

            <div class="topbar-right">
                <div class="welcome-pill">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
                <a class="logout-link" href="logout.php">Logout</a>
            </div>
        </header>

        <section class="hero">
            <div class="hero-tag">Fresh picks for tonight</div>
            <h1>Your movie night starts here.</h1>
            <p>
                Browse your current catalog in a warmer, more cinematic home screen built for quick picks,
                cozy rewatches, and big-screen energy.
            </p>
        </section>

        <main class="content">
            <div class="section-header">
                <div>
                    <h2>Available movies</h2>
                    <p>Jump straight into the latest titles from your Avocadoflix library.</p>
                </div>
            </div>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="movie-grid">
                    <?php while ($movie = $result->fetch_assoc()): ?>
                        <article class="movie-card">
                            <div class="movie-poster">
                                <img
                                    src="<?php echo htmlspecialchars($movie['thumbnail']); ?>"
                                    alt="<?php echo htmlspecialchars($movie['title']); ?> poster"
                                >
                            </div>
                            <div class="movie-body">
                                <h3 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                <a class="watch-link" href="watch.php?id=<?php echo urlencode($movie['id']); ?>">Watch now</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    No movies found yet. Add titles to your database and they will appear here.
                </div>
            <?php endif; ?>
        </main>

        <footer class="footer">
            Avocadoflix brings warm visuals, playful energy, and cinematic browsing into your streaming home.
        </footer>
    </div>
</body>
</html>