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
            $error = "Wrong password.";
        }
    } else {
        $error = "User not found.";
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
        <section class="auth-visual reveal">
            <img class="auth-backdrop" src="assets/images/hoppers_ver2_xlg.jpg" alt="AvocadoFlix backdrop">
            <div class="auth-overlay">
                <div class="brand-badge">
                    <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                    <span class="brand-copy">
                        <span class="brand-wordmark">AvocadoFlix</span>
                        <span class="brand-subtitle">Sign in</span>
                    </span>
                </div>

                <div class="auth-visual-copy hero-fade">
                    <span class="eyebrow">Premium streaming access</span>
                </div>

                <div class="poster-stack">
                    <img src="assets/images/hoppers_ver2_xlg.jpg" alt="AvocadoFlix poster preview">
                    <img src="assets/images/project_hail_mary_ver3.jpg" alt="AvocadoFlix poster preview">
                    <img src="assets/images/super_mario_galaxy_movie_ver12_xlg.jpg" alt="AvocadoFlix poster preview">
                </div>
            </div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-card reveal">
                <span class="eyebrow">Welcome back</span>
                <h2>Log in</h2>
                <p class="intro">Sign in to continue your lineup.</p>

                <?php if ($error !== ''): ?>
                    <p class="status-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <form method="POST" class="field-grid">
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <button class="button button-primary button-block" type="submit">Log in</button>
                </form>

                <p class="auth-footer">Need an account? <a class="text-link" href="register.php">Register</a></p>
            </div>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
