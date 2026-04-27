<?php
session_start();
include(__DIR__ . '/../config/db.php');

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
            echo "Wrong password!";
        }

    } else {
        echo "User not found!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avocadoflix | Welcome back</title>
    <style>
        :root {
            --bg: #07110d;
            --bg-secondary: #0d1d16;
            --surface: rgba(10, 24, 18, 0.88);
            --surface-strong: rgba(15, 34, 25, 0.96);
            --primary: #8fd14f;
            --primary-strong: #6fb73c;
            --secondary: #315c2e;
            --accent: #f3c969;
            --text: #f6f2e8;
            --muted: #b7c3b5;
            --border: rgba(143, 209, 79, 0.18);
            --shadow: 0 24px 70px rgba(0, 0, 0, 0.45);
            --radius-xl: 28px;
            --radius-lg: 18px;
            --radius-md: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(143, 209, 79, 0.16), transparent 28%),
                radial-gradient(circle at bottom left, rgba(243, 201, 105, 0.14), transparent 26%),
                linear-gradient(135deg, #050b08 0%, var(--bg) 40%, #091510 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .page-shell {
            width: min(1160px, 100%);
            min-height: 720px;
            display: grid;
            grid-template-columns: 0.94fr 1.06fr;
            background: rgba(7, 17, 13, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .form-panel {
            padding: 42px 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(180deg, rgba(10, 24, 18, 0.95), rgba(7, 17, 13, 0.97));
        }

        .form-card {
            width: min(450px, 100%);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background:
                radial-gradient(circle at 35% 35%, #b6ed72 0%, #95d956 38%, #4f7d2d 100%);
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

        .eyebrow {
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

        .eyebrow::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 12px rgba(143, 209, 79, 0.65);
        }

        .form-card h1 {
            margin: 0 0 10px;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .intro {
            margin: 0 0 28px;
            color: var(--muted);
            line-height: 1.65;
        }

        .field-grid {
            display: grid;
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        label {
            font-size: 0.92rem;
            color: #edf3e4;
        }

        .inline-link,
        .footer-copy a {
            color: #dff4b8;
            text-decoration: none;
        }

        .inline-link:hover,
        .inline-link:focus-visible,
        .footer-copy a:hover,
        .footer-copy a:focus-visible {
            text-decoration: underline;
        }

        input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            background: var(--surface-strong);
            color: var(--text);
            padding: 15px 16px;
            font: inherit;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        input::placeholder {
            color: #8fa18f;
        }

        input:focus {
            border-color: rgba(143, 209, 79, 0.72);
            box-shadow: 0 0 0 4px rgba(143, 209, 79, 0.12);
            transform: translateY(-1px);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .checkbox-row label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            cursor: pointer;
        }

        .checkbox-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            padding: 0;
            accent-color: var(--primary);
            transform: none;
            box-shadow: none;
        }

        .cta {
            margin-top: 10px;
            width: 100%;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary) 0%, #b9eb68 100%);
            color: #10210f;
            font-weight: 800;
            font-size: 1rem;
            padding: 16px 20px;
            cursor: pointer;
            box-shadow: 0 16px 30px rgba(111, 183, 60, 0.32);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .cta:hover,
        .cta:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 20px 34px rgba(111, 183, 60, 0.42);
            filter: saturate(1.06);
        }

        .cta:focus-visible {
            outline: 3px solid rgba(243, 201, 105, 0.35);
            outline-offset: 3px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0 18px;
            color: #8ea08d;
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: linear-gradient(90deg, transparent, rgba(143, 209, 79, 0.22), transparent);
        }

        .footer-copy {
            margin: 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .hero-panel {
            position: relative;
            padding: 48px;
            background:
                linear-gradient(160deg, rgba(18, 43, 30, 0.88), rgba(8, 15, 12, 0.5)),
                radial-gradient(circle at 75% 25%, rgba(143, 209, 79, 0.22), transparent 34%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .hero-panel::before,
        .hero-panel::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(8px);
            opacity: 0.85;
        }

        .hero-panel::before {
            width: 240px;
            height: 240px;
            top: -70px;
            left: -50px;
            background: radial-gradient(circle, rgba(243, 201, 105, 0.18), transparent 70%);
        }

        .hero-panel::after {
            width: 280px;
            height: 280px;
            right: -90px;
            bottom: -90px;
            background: radial-gradient(circle, rgba(143, 209, 79, 0.22), transparent 70%);
        }

        .hero-top,
        .hero-bottom {
            position: relative;
            z-index: 1;
        }

        .hero-copy h2 {
            margin: 0 0 16px;
            font-size: clamp(2.4rem, 4.8vw, 4rem);
            line-height: 0.95;
            letter-spacing: -0.045em;
        }

        .hero-copy p {
            margin: 0;
            max-width: 32rem;
            color: var(--muted);
            line-height: 1.7;
            font-size: 1.04rem;
        }

        .feature-stack {
            display: grid;
            gap: 14px;
            margin-top: 34px;
        }

        .feature-card {
            padding: 18px 18px 16px;
            border-radius: 20px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.05), transparent),
                rgba(11, 21, 17, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .feature-card strong {
            display: block;
            margin-bottom: 6px;
            color: #f4efdd;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.94rem;
        }

        .session-note {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .session-note span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 16px rgba(243, 201, 105, 0.55);
        }

        @media (max-width: 960px) {
            .page-shell {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                min-height: 380px;
                padding: 32px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .form-panel,
            .hero-panel {
                padding: 20px;
            }

            .form-card {
                padding: 24px 20px;
            }

            .checkbox-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <section class="form-panel">
            <div class="form-card">
                <div class="logo">
                    <div class="logo-mark"></div>
                    <span>Avocadoflix</span>
                </div>

                <div class="eyebrow">Welcome back</div>
                <h1>Pick up where the credits paused.</h1>
                <p class="intro">
                    Log in to reopen your watchlist, continue your last stream, and get back to your next favorite story.
                </p>

                <form method="POST">
                    <div class="field-grid">
                        <div class="field">
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" placeholder="you@example.com" required>
                        </div>

                        <div class="field">
                            <div class="field-row">
                                <label for="password">Password</label>
                                <a class="inline-link" href="#">Forgot password?</a>
                            </div>
                            <input id="password" type="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="checkbox-row">
                        <label for="remember">
                            <input id="remember" type="checkbox" name="remember">
                            Keep me signed in
                        </label>
                    </div>

                    <button class="cta" type="submit">Log in</button>
                </form>

                <div class="divider">New to Avocadoflix?</div>
                <p class="footer-copy">Create an account and start watching <a href="#">Register</a></p>
            </div>
        </section>

        <section class="hero-panel" aria-hidden="true">
            <div class="hero-top">
                <div class="hero-copy">
                    <h2>Your next movie night is already waiting.</h2>
                    <p>
                        Step back into a streaming space built with warm color, playful energy, and a cinema-first sense
                        of atmosphere.
                    </p>
                </div>

                <div class="feature-stack">
                    <div class="feature-card">
                        <strong>Saved watchlist</strong>
                        <p>Return to your queued dramas, comfort rewatches, and late-night discoveries in one click.</p>
                    </div>
                    <div class="feature-card">
                        <strong>Fresh recommendations</strong>
                        <p>Get playful, mood-aware suggestions shaped around what you love to stream.</p>
                    </div>
                    <div class="feature-card">
                        <strong>Cinematic atmosphere</strong>
                        <p>Enjoy a warmer, premium interface that feels closer to a movie lounge than a utility form.</p>
                    </div>
                </div>
            </div>

            <div class="hero-bottom">
                <div class="session-note">
                    <span></span>
                    Ready when you are, from quick lunch-break episodes to full weekend marathons.
                </div>
            </div>
        </section>
    </main>
</body>
</html>