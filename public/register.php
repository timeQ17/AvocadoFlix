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
        $error = "Email already exists.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Unable to create account.";
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
        <section class="auth-visual reveal">
            <img class="auth-backdrop" src="assets/images/hoppers_ver2_xlg.jpg" alt="AvocadoFlix backdrop">
            <div class="auth-overlay">
                <div class="brand-badge">
                    <img class="brand-logo accent-glow" src="assets/images/logo.png" alt="AvocadoFlix logo">
                    <span class="brand-copy">
                        <span class="brand-wordmark">AvocadoFlix</span>
                        <span class="brand-subtitle">Create account</span>
                    </span>
                </div>

                <div class="auth-visual-copy hero-fade">
                    <span class="eyebrow">Premium streaming access</span>
                    <h1>Build your watchlist.</h1>
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
                <span class="eyebrow">New account</span>
                <h2>Register</h2>
                <p class="intro">Create your account and unlock the full lineup.</p>

                <?php if ($error !== ''): ?>
                    <p class="status-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <form method="POST" class="field-grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" placeholder="Choose a name" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Create a password" required>
                    </div>

                    <button class="button button-primary button-block" type="submit">Create account</button>
                </form>

                <p class="auth-footer">Already registered? <a class="text-link" href="login.php">Log in</a></p>
            </div>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/motion.js"></script>
</body>
</html>
