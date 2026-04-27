<?php
include(__DIR__ . '/../config/db.php');

$error = '';
$username = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvocadoFlix | Register</title>
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

                <div class="kicker">Fresh account, fresh watchlist</div>
                <h1>Join the brightest seat in the cinema.</h1>
                <p>
                    Create your account to step into a playful movie space with protected access, streaming-ready pages,
                    and a mood that feels much closer to a theater than a classroom form.
                </p>
            </div>

            <div class="auth-floating reveal">
                <div class="logo-spotlight float-gentle" data-tilt>
                    <img src="assets/images/logo.png" alt="">
                    <div class="logo-caption">
                        The logo becomes part of the set here, so the register page already feels like the start of the AvocadoFlix experience.
                    </div>
                </div>

                <div class="mini-stack">
                    <article class="mini-card reveal" data-tilt>
                        <strong>Protected access</strong>
                        <p>Users register first, then the movie catalog and player pages stay behind authentication.</p>
                    </article>
                    <article class="mini-card reveal" data-tilt>
                        <strong>Student-friendly build</strong>
                        <p>The PHP and MySQL logic stays aligned with your existing project while the interface gets a premium lift.</p>
                    </article>
                </div>
            </div>

            <div class="auth-note">Popcorn tones, theater shadows, and motion pulled from the real logo art.</div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-card reveal">
                <div class="brand-badge">
                    <img class="brand-logo" src="assets/images/logo.png" alt="AvocadoFlix logo">
                    <span class="brand-wordmark">AvocadoFlix</span>
                </div>

                <div class="kicker">Create your account</div>
                <h2>Start your movie profile</h2>
                <p class="intro">
                    Make a quick account, unlock the protected library, and head straight into the streaming dashboard.
                </p>

                <?php if ($error !== ''): ?>
                    <p class="status-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <form method="POST">
                    <div class="field-grid">
                        <div class="field">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" placeholder="Choose a screen name" value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>

                        <div class="field">
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Create a secure password" required>
                            <div class="helper">Use a strong password so your account stays protected.</div>
                        </div>
                    </div>

                    <button class="cta" type="submit">Create account</button>
                </form>

                <div class="auth-meta">Already registered?</div>
                <p class="auth-footer">Sign in to continue your movie night <a class="text-link" href="login.php">Log in</a></p>
                <p class="auth-legal">By continuing, users confirm they are creating an account for educational access to the AvocadoFlix platform.</p>
            </div>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
