<?php
session_start();
include(__DIR__ . '/../config/db.php');

$error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: home.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvocadoFlix | Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body class="page-auth">
    <main class="app-shell auth-shell">
        <section class="auth-visual">
            <div class="auth-copy hero-fade">
                <div class="brand-badge">
                    <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                    <span class="brand-wordmark">AvocadoFlix</span>
                </div>

                <div class="kicker">Cinema night starts here</div>
                <h1>Slide back into your favorite seat.</h1>
                <p>
                    Log in to reopen your watchlist, continue where you left off, and enjoy a movie space
                    that feels warm, playful, and ready for the next scene.
                </p>
            </div>

            <div class="auth-floating reveal">
                <div class="logo-spotlight float-gentle" data-tilt>
                    <img src="assets/images/logo.png" alt="">
                    <div class="logo-caption">
                        Your popcorn-loving AvocadoFlix mascot sets the tone: cozy theater energy with a brighter, friendlier twist.
                    </div>
                </div>

                <div class="mini-stack">
                    <article class="mini-card reveal" data-tilt>
                        <strong>Continue your queue</strong>
                        <p>Pick up unfinished movies, favorite genres, and comfort rewatches in a click.</p>
                    </article>
                    <article class="mini-card reveal" data-tilt>
                        <strong>Feel the motion</strong>
                        <p>Subtle depth, soft glow, and cinematic pacing make the interface feel alive without getting in the way.</p>
                    </article>
                </div>
            </div>

            <div class="auth-note">Built for student streaming projects with a little more character.</div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-card reveal">
                <div class="brand-badge">
                    <img class="brand-logo" src="assets/images/logo.png" alt="AvocadoFlix logo">
                    <span class="brand-wordmark">AvocadoFlix</span>
                </div>

                <div class="kicker">Welcome back</div>
                <h2>Log in to keep watching</h2>
                <p class="intro">
                    Your account keeps the catalog protected and your movie night personal.
                </p>

                <?php if ($error !== ''): ?>
                    <p class="status-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <form method="POST">
                    <div class="field-grid">
                        <div class="field">
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>

                        <div class="field">
                            <div class="field-row">
                                <label for="password">Password</label>
                                <span class="helper">Private access only</span>
                            </div>
                            <input id="password" type="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <button class="cta" type="submit">Log in</button>
                </form>

                <div class="auth-meta">New here?</div>
                <p class="auth-footer">Create your account and unlock the catalog <a class="text-link" href="register.php">Register</a></p>
            </div>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
