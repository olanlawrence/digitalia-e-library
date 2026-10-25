<?php

session_start();

require_once __DIR__ . '/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT id, full_name, email, password, role
             FROM users
             WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid email or password.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Digitalia</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --cream: #f6f1e8;
            --cream-light: #fcfaf6;
            --red: #9b3d4a;
            --red-dark: #6f2733;
            --charcoal: #252326;
            --muted: #716b68;
            --border: #ded6cc;
            --error-bg: #f5e6e8;
            --error-text: #7b303b;
        }

        body {
            min-height: 100vh;
            font-family: "DM Sans", sans-serif;
            background: var(--cream);
            color: var(--charcoal);
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left panel */

        .visual-panel {
            position: relative;
            min-height: 100vh;
            background: var(--red);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .visual-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            color: #ffffff;
        }

        .visual-label {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .visual-line {
            width: 35px;
            height: 1px;
            background: rgba(255, 255, 255, 0.7);
        }

        .visual-content h1 {
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(58px, 6vw, 86px);
            font-weight: 600;
            line-height: 0.9;
            letter-spacing: -1.5px;
            margin-bottom: 30px;
        }

        .visual-content p {
            max-width: 350px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 15px;
            line-height: 1.8;
        }

        .decorative-circle {
            position: absolute;
            width: 470px;
            height: 470px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 50%;
            right: -220px;
            top: 50%;
            transform: translateY(-50%);
        }

        .decorative-circle::before {
            content: "";
            position: absolute;
            width: 330px;
            height: 330px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            top: 69px;
            left: 69px;
        }

        .decorative-circle::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: 139px;
            left: 139px;
        }

        .visual-footer {
            position: absolute;
            bottom: 35px;
            left: 60px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Right panel */

        .form-panel {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            background: var(--cream-light);
        }

        .form-container {
            width: 100%;
            max-width: 430px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 55px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: 0.4px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--red);
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header .eyebrow {
            margin-bottom: 14px;
            color: var(--red);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.2px;
            text-transform: uppercase;
        }

        .form-header h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 52px;
            font-weight: 600;
            line-height: 0.95;
            color: var(--charcoal);
            margin-bottom: 14px;
        }

        .form-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .message {
            margin-bottom: 22px;
            padding: 13px 15px;
            border: 1px solid rgba(155, 61, 74, 0.2);
            border-radius: 4px;
            background: var(--error-bg);
            color: var(--error-text);
            font-size: 13px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: var(--charcoal);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            height: 52px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 3px;
            outline: none;
            background: #ffffff;
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
            font-size: 14px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .form-group input::placeholder {
            color: #a39c96;
        }

        .form-group input:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(155, 61, 74, 0.08);
        }

        .submit-button {
            width: 100%;
            height: 52px;
            margin-top: 8px;
            border: 1px solid var(--red);
            border-radius: 3px;
            background: var(--red);
            color: #ffffff;
            font-family: "DM Sans", sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease;
        }

        .submit-button:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
            transform: translateY(-1px);
        }

        .register-link {
            margin-top: 28px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .register-link a {
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            color: var(--red-dark);
            text-decoration: underline;
        }

        @media (max-width: 850px) {

            .page {
                grid-template-columns: 1fr;
            }

            .visual-panel {
                min-height: 340px;
                padding: 50px 7%;
            }

            .visual-content h1 {
                font-size: 64px;
            }

            .visual-content p {
                max-width: 450px;
            }

            .visual-footer {
                display: none;
            }

            .decorative-circle {
                width: 350px;
                height: 350px;
                right: -180px;
            }

            .decorative-circle::before {
                width: 240px;
                height: 240px;
                top: 54px;
                left: 54px;
            }

            .decorative-circle::after {
                width: 140px;
                height: 140px;
                top: 104px;
                left: 104px;
            }

            .form-panel {
                min-height: auto;
                padding: 60px 7%;
            }

            .back-link {
                margin-bottom: 40px;
            }

        }

        @media (max-width: 500px) {

            .visual-panel {
                min-height: 290px;
                padding: 40px 6%;
            }

            .visual-content h1 {
                font-size: 55px;
            }

            .visual-content p {
                font-size: 13px;
            }

            .form-panel {
                padding: 45px 6%;
            }

            .form-header h2 {
                font-size: 45px;
            }

        }

    </style>

</head>

<body>

<div class="page">

    <section class="visual-panel">

        <div class="visual-content">

            <div class="visual-label">

                <span class="visual-line"></span>

                Digitalia

            </div>

            <h1>
                Welcome
                back.
            </h1>

            <p>
                Return to your personal digital library
                and continue discovering books,
                stories, and knowledge.
            </p>

        </div>

        <div class="decorative-circle"></div>

        <div class="visual-footer">
            Discover · Read · Explore
        </div>

    </section>


    <main class="form-panel">

        <div class="form-container">

            <a
                href="index.php"
                class="back-link"
            >
                ← Back to Digitalia
            </a>


            <div class="form-header">

                <div class="eyebrow">
                    Member Access
                </div>

                <h2>
                    Sign in
                </h2>

                <p>
                    Enter your account details to
                    access your Digitalia library.
                </p>

            </div>


            <?php if ($message): ?>

                <div class="message">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="submit-button"
                >
                    Log In
                </button>

            </form>


            <p class="register-link">

                Don't have an account?

                <a href="register.php">
                    Create an account
                </a>

            </p>

        </div>

    </main>

</div>

</body>

</html>