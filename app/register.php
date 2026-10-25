<?php

require_once __DIR__ . '/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($full_name) || empty($email) || empty($password)) {

        $message = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "Email address is already registered.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $full_name,
                $email,
                $hashed_password
            ]);

            $message = "Registration successful!";
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

    <title>Register - Digitalia</title>

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
            --success-bg: #edf2e9;
            --success-text: #53664b;
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

        /* Left visual panel */

        .visual-panel {
            position: relative;
            min-height: 100vh;
            background: var(--cream);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            border-right: 1px solid var(--border);
        }

        .visual-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
        }

        .visual-label {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            color: var(--red);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .visual-line {
            width: 35px;
            height: 1px;
            background: var(--red);
        }

        .visual-content h1 {
            max-width: 500px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(58px, 6vw, 86px);
            font-weight: 600;
            line-height: 0.9;
            letter-spacing: -1.5px;
            color: var(--charcoal);
            margin-bottom: 30px;
        }

        .visual-content h1 span {
            color: var(--red);
        }

        .visual-content p {
            max-width: 350px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .book-shape {
            position: absolute;
            width: 280px;
            height: 380px;
            right: -110px;
            bottom: 8%;
            background: var(--red);
            transform: rotate(-8deg);
            opacity: 0.95;
        }

        .book-shape::before {
            content: "";
            position: absolute;
            width: 245px;
            height: 345px;
            top: 18px;
            left: 18px;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .book-shape::after {
            content: "DIGITALIA";
            position: absolute;
            top: 50%;
            left: 45px;
            color: rgba(255, 255, 255, 0.75);
            font-family: "Cormorant Garamond", serif;
            font-size: 25px;
            font-weight: 600;
            letter-spacing: 3px;
            transform: rotate(-90deg);
            transform-origin: left center;
        }

        .visual-footer {
            position: absolute;
            bottom: 35px;
            left: 60px;
            color: #918982;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Right form panel */

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
            margin-bottom: 48px;
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
            margin-bottom: 32px;
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
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
        }

        .message.success {
            border: 1px solid rgba(83, 102, 75, 0.2);
            background: var(--success-bg);
            color: var(--success-text);
        }

        .message.error {
            border: 1px solid rgba(155, 61, 74, 0.2);
            background: var(--error-bg);
            color: var(--error-text);
        }

        .form-group {
            margin-bottom: 19px;
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

        .password-note {
            margin-top: 7px;
            color: #938b85;
            font-size: 11px;
            line-height: 1.5;
        }

        .submit-button {
            width: 100%;
            height: 52px;
            margin-top: 9px;
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

        .login-link {
            margin-top: 26px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .login-link a {
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
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
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .visual-content h1 {
                font-size: 64px;
            }

            .visual-content p {
                max-width: 450px;
            }

            .book-shape {
                width: 220px;
                height: 300px;
                right: -100px;
            }

            .book-shape::before {
                width: 190px;
                height: 265px;
            }

            .visual-footer {
                display: none;
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

            .book-shape {
                width: 180px;
                height: 250px;
                right: -100px;
            }

            .book-shape::before {
                width: 150px;
                height: 215px;
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

                Begin your
                <span>
                    collection.
                </span>

            </h1>

            <p>
                Create your Digitalia account and
                build a personal space for the books
                you want to discover, read, and keep.
            </p>

        </div>

        <div class="book-shape"></div>

        <div class="visual-footer">
            Your personal digital library
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
                    New Member
                </div>

                <h2>
                    Create account
                </h2>

                <p>
                    Join Digitalia to save books,
                    manage your personal library,
                    and continue your reading journey.
                </p>

            </div>


            <?php if ($message): ?>

                <?php
                $isSuccess =
                    $message === "Registration successful!";
                ?>

                <div
                    class="message <?php echo $isSuccess ? 'success' : 'error'; ?>"
                >

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label for="full_name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        required
                    >

                </div>


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
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                    >

                    <p class="password-note">
                        Password must be at least 6 characters.
                    </p>

                </div>


                <button
                    type="submit"
                    class="submit-button"
                >
                    Create Account
                </button>

            </form>


            <p class="login-link">

                Already have an account?

                <a href="login.php">
                    Log in
                </a>

            </p>

        </div>

    </main>

</div>

</body>

</html>