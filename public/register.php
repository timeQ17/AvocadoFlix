<?php
include(__DIR__ . '/../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

   // Check duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already exists!";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avocadoflix | Create your account</title>
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
            --error: #ff8f7a;
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
                radial-gradient(circle at top left, rgba(143, 209, 79, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(243, 201, 105, 0.16), transparent 24%),
                linear-gradient(135deg, #050b08 0%, var(--bg) 40%, #091510 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .page-shell {
            width: min(1180px, 100%);
            min-height: 720px;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            background: rgba(7, 17, 13, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .brand-panel {
            position: relative;
            padding: 48px;
            background:
                linear-gradient(160deg, rgba(18, 43, 30, 0.9), rgba(8, 15, 12, 0.5)),
                radial-gradient(circle at 20% 30%, rgba(143, 209, 79, 0.22), transparent 35%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-panel::before,
        .brand-panel::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(8px);
            opacity: 0.8;
        }

        .brand-panel::before {
            width: 220px;
            height: 220px;
            top: -60px;
            right: -40px;
            background: radial-gradient(circle, rgba(143, 209, 79, 0.28), transparent 70%);
        }

        .brand-panel::after {
            width: 260px;
            height: 260px;
            bottom: -90px;
            left: -60px;
            background: radial-gradient(circle, rgba(243, 201, 105, 0.2), transparent 70%);
        }

        .brand-top,
        .brand-bottom {
            position: relative;
            z-index: 1;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
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

        .brand-copy h1 {
            margin: 0 0 16px;
            font-size: clamp(2.6rem, 5vw, 4.4rem);
            line-height: 0.94;
            letter-spacing: -0.04em;
        }

        .brand-copy p {
            margin: 0;
            max-width: 34rem;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .poster-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 32px;
        }

        .poster {
            min-height: 160px;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.06), transparent),
                linear-gradient(160deg, rgba(143, 209, 79, 0.2), rgba(8, 15, 12, 0.82));
            padding: 16px;
            display: flex;
            align-items: flex-end;
            font-weight: 700;
            color: rgba(246, 242, 232, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .poster:nth-child(2) {
            transform: translateY(18px);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.06), transparent),
                linear-gradient(160deg, rgba(243, 201, 105, 0.22), rgba(8, 15, 12, 0.84));
        }

        .poster:nth-child(3) {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.06), transparent),
                linear-gradient(160deg, rgba(77, 131, 52, 0.26), rgba(8, 15, 12, 0.84));
        }

        .brand-note {
            margin-top: 42px;
            display: flex;
            gap: 12px;
            align-items: center;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .brand-note span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 16px rgba(243, 201, 105, 0.55);
        }

        .form-panel {
            padding: 42px 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(180deg, rgba(10, 24, 18, 0.94), rgba(7, 17, 13, 0.96));
        }

        .form-card {
            width: min(460px, 100%);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow);
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

        .form-card h2 {
            margin: 0 0 10px;
            font-size: clamp(2rem, 3.5vw, 2.7rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .form-card .intro {
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

        label {
            font-size: 0.92rem;
            color: #edf3e4;
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

        .helper {
            font-size: 0.82rem;
            color: var(--muted);
        }

        .cta {
            margin-top: 8px;
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

        .footer-copy a {
            color: #dff4b8;
            text-decoration: none;
        }

        .footer-copy a:hover,
        .footer-copy a:focus-visible {
            text-decoration: underline;
        }

        .terms {
            margin-top: 16px;
            color: #93a18d;
            font-size: 0.78rem;
            line-height: 1.6;
        }

        @media (max-width: 960px) {
            .page-shell {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                min-height: 420px;
                padding: 32px;
            }

            .poster-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .poster:nth-child(2) {
                transform: none;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .brand-panel {
                min-height: auto;
                padding: 24px;
            }

            .brand-copy h1 {
                font-size: 2.35rem;
            }

            .poster-row {
                grid-template-columns: 1fr;
            }

            .form-panel {
                padding: 18px;
            }

            .form-card {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <section class="brand-panel" aria-hidden="true">
            <div class="brand-top">
                <div class="logo">
                    <div class="logo-mark"></div>
                    <span>Avocadoflix</span>
                </div>

                <div class="brand-copy">
                    <h1>Fresh stories. Big-screen mood. One cozy account away.</h1>
                    <p>
                        Join Avocadoflix to save your watchlist, unlock tailored picks, and dive into a streaming space
                        that feels playful, cinematic, and a little unexpected.
                    </p>
                </div>

                <div class="poster-row">
                    <div class="poster">Midnight Orchard</div>
                    <div class="poster">Green Room Rewind</div>
                    <div class="poster">Sunlit Signal</div>
                </div>
            </div>

            <div class="brand-bottom">
                <div class="brand-note">
                    <span></span>
                    Curated for movie nights, weekend binges, and your next comfort rewatch.
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-card">
                <div class="eyebrow">Start your watchlist</div>
                <h2>Create your account</h2>
                <p class="intro">
                    Set up your Avocadoflix profile and step into a warmer, more playful streaming experience.
                </p>

                <form method="POST">
                    <div class="field-grid">
                        <div class="field">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" placeholder="Choose a screen name" required>
                        </div>

                        <div class="field">
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" placeholder="you@example.com" required>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Create a secure password" required>
                            <div class="helper">Use at least 8 characters for stronger protection.</div>
                        </div>
                    </div>

                    <button class="cta" type="submit">Create account</button>
                </form>

                <div class="divider">Already have an account?</div>
                <p class="footer-copy">Sign in to continue your watchlist <a href="#">Log in</a></p>
                <p class="terms">
                    By continuing, you agree to Avocadoflix's Terms of Service and Privacy Policy.
                </p>
            </div>
        </section>
    </main>
</body>
</html>