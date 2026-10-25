<?php

session_start();

if (!isset($_SESSION['guest'])) {
    $_SESSION['guest'] = true;
    $_SESSION['guest_downloads'] = 0;
}

$guestDownloads = $_SESSION['guest_downloads'];
$remainingDownloads = max(0, 5 - $guestDownloads);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Guest Mode - Digitalia</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
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
            --white: #ffffff;
        }

        body {
            min-height: 100vh;
            background: var(--cream-light);
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
        }

        /* NAVIGATION */

        .navbar {
            height: 78px;
            padding: 0 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: rgba(252, 250, 246, 0.97);
        }

        .brand {
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: var(--red);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--red);
        }

        .register-link {
            padding: 10px 17px;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .register-link:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        /* MAIN */

        .main {
            width: 88%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 70px 0 90px;
        }

        /* HERO */

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 70px;
            align-items: center;
            min-height: 470px;
            padding-bottom: 70px;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            color: var(--red);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .eyebrow-line {
            width: 34px;
            height: 1px;
            background: var(--red);
        }

        .hero h1 {
            max-width: 760px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(65px, 8vw, 105px);
            font-weight: 600;
            line-height: 0.83;
            letter-spacing: -2.5px;
        }

        .hero h1 span {
            color: var(--red);
        }

        .hero-description {
            max-width: 550px;
            margin-top: 30px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.85;
        }

        /* DOWNLOAD CARD */

        .limit-card {
            padding: 35px;
            border: 1px solid var(--border);
            background: var(--white);
        }

        .limit-label {
            margin-bottom: 20px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .limit-number {
            margin-bottom: 8px;
            font-family: "Cormorant Garamond", serif;
            font-size: 58px;
            font-weight: 600;
            line-height: 0.9;
        }

        .limit-number span {
            color: #aaa29c;
            font-size: 30px;
        }

        .limit-text {
            margin-bottom: 25px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .progress {
            width: 100%;
            height: 4px;
            background: #e9e3dc;
        }

        .progress-bar {
            width: <?php echo min(100, ($guestDownloads / 5) * 100); ?>%;
            height: 100%;
            background: var(--red);
        }

        .remaining {
            margin-top: 12px;
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .remaining strong {
            color: var(--charcoal);
        }

        /* ACTIONS */

        .section {
            padding-top: 60px;
        }

        .section-heading {
            margin-bottom: 25px;
        }

        .section-heading .eyebrow {
            margin-bottom: 12px;
        }

        .section-heading h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 40px;
            font-weight: 600;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .action-card {
            min-height: 210px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid var(--border);
            background: var(--white);
            text-decoration: none;
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: rgba(155, 61, 74, 0.35);
            box-shadow: 0 15px 35px rgba(50, 40, 35, 0.07);
        }

        .action-label {
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .action-content h3 {
            margin-bottom: 8px;
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
        }

        .action-content p {
            max-width: 390px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .action-arrow {
            align-self: flex-end;
            color: var(--red);
            font-size: 23px;
        }

        /* ACCOUNT PROMPT */

        .account-section {
            padding-top: 70px;
        }

        .account-box {
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            border: 1px solid var(--border);
            background: var(--cream);
        }

        .account-box h3 {
            margin-bottom: 8px;
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
        }

        .account-box p {
            max-width: 570px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .account-actions {
            flex-shrink: 0;
            display: flex;
            gap: 10px;
        }

        .create-button,
        .login-button {
            min-height: 44px;
            padding: 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .create-button {
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
        }

        .create-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        .login-button {
            border: 1px solid var(--border);
            color: var(--charcoal);
            background: var(--cream-light);
        }

        .login-button:hover {
            border-color: var(--charcoal);
        }

        /* FOOTER */

        .footer {
            margin-top: 80px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            color: #918982;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {

            .hero {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .limit-card {
                max-width: 500px;
            }

            .account-box {
                display: block;
            }

            .account-actions {
                margin-top: 25px;
            }

        }

        @media (max-width: 650px) {

            .navbar {
                padding: 0 6%;
            }

            .nav-link {
                display: none;
            }

            .main {
                width: 88%;
                padding-top: 50px;
            }

            .hero {
                padding-bottom: 50px;
            }

            .hero h1 {
                font-size: 64px;
            }

            .actions {
                grid-template-columns: 1fr;
            }

            .account-box {
                padding: 30px;
            }

            .account-actions {
                flex-direction: column;
            }

            .create-button,
            .login-button {
                width: 100%;
            }

        }

        @media (max-width: 500px) {

            .navbar {
                height: 70px;
            }

            .brand {
                font-size: 28px;
            }

            .register-link {
                padding: 9px 13px;
            }

            .main {
                padding: 45px 0 70px;
            }

            .hero h1 {
                font-size: 58px;
            }

            .limit-card {
                padding: 28px;
            }

            .footer {
                display: block;
                line-height: 2;
            }

        }

    </style>

</head>

<body>

<nav class="navbar">

    <a
        href="index.php"
        class="brand"
    >
        Digit<span>alia</span>
    </a>

    <div class="nav-right">

        <a
            href="login.php"
            class="nav-link"
        >
            Log In
        </a>

        <a
            href="register.php"
            class="register-link"
        >
            Create Account
        </a>

    </div>

</nav>


<main class="main">

    <section class="hero">

        <div>

            <div class="eyebrow">

                <span class="eyebrow-line"></span>

                Guest Mode

            </div>

            <h1>
                Explore
                <span>freely.</span>
            </h1>

            <p class="hero-description">
                Browse the Digitalia collection without creating
                an account. Discover books and download what
                interests you while keeping your experience simple.
            </p>

        </div>


        <div class="limit-card">

            <div class="limit-label">
                Guest Downloads
            </div>

            <div class="limit-number">

                <?php echo $guestDownloads; ?>

                <span>
                    / 5
                </span>

            </div>

            <p class="limit-text">
                Guest accounts can download up to five books.
                Create an account to unlock a larger personal
                library experience.
            </p>

            <div class="progress">

                <div class="progress-bar"></div>

            </div>

            <div class="remaining">

                <strong>
                    <?php echo $remainingDownloads; ?>
                </strong>

                download<?php echo $remainingDownloads === 1 ? '' : 's'; ?>

                remaining

            </div>

        </div>

    </section>


    <section class="section">

        <div class="section-heading">

            <div class="eyebrow">
                Start Exploring
            </div>

            <h2>
                Digitalia Collection
            </h2>

        </div>


        <div class="actions">

            <a
                href="books.php"
                class="action-card"
            >

                <div class="action-label">
                    Discover
                </div>

                <div class="action-content">

                    <h3>
                        Browse Books
                    </h3>

                    <p>
                        Explore available titles, search by
                        author or title, and filter the collection
                        by category.
                    </p>

                </div>

                <div class="action-arrow">
                    →
                </div>

            </a>


            <a
                href="register.php"
                class="action-card"
            >

                <div class="action-label">
                    Personalize
                </div>

                <div class="action-content">

                    <h3>
                        Create Your Library
                    </h3>

                    <p>
                        Save books to your personal library and
                        keep your favorite titles organized in one place.
                    </p>

                </div>

                <div class="action-arrow">
                    →
                </div>

            </a>

        </div>

    </section>


    <section class="account-section">

        <div class="account-box">

            <div>

                <h3>
                    Ready for more?
                </h3>

                <p>
                    Create a Digitalia account to save books,
                    build your personal collection, and enjoy
                    a more complete library experience.
                </p>

            </div>


            <div class="account-actions">

                <a
                    href="register.php"
                    class="create-button"
                >
                    Create Account
                </a>

                <a
                    href="login.php"
                    class="login-button"
                >
                    Log In
                </a>

            </div>

        </div>

    </section>


    <footer class="footer">

        <span>
            Digitalia
        </span>

        <span>
            Guest access
        </span>

    </footer>

</main>

</body>

</html>