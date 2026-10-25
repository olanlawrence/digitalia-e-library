<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Digitalia</title>

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

        /* GLOBAL LINK RESET */

        a {
            color: inherit;
        }

        /* NAVIGATION */

        .navbar {
            height: 78px;
            padding: 0 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: rgba(252, 250, 246, 0.96);
        }

        .brand {
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .brand:visited {
            color: var(--charcoal);
        }

        .brand span {
            color: var(--red);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .nav-link {
            color: var(--charcoal);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .nav-link:visited {
            color: var(--charcoal);
        }

        .nav-link:hover {
            color: var(--red);
        }

        .logout-link {
            padding: 10px 17px;
            border: 1px solid var(--border);
            color: var(--charcoal);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .logout-link:visited {
            color: var(--charcoal);
        }

        .logout-link:hover {
            border-color: var(--red);
            background: var(--red);
            color: var(--white);
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
            grid-template-columns: 1.4fr 0.6fr;
            gap: 60px;
            align-items: end;
            padding-bottom: 70px;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            margin-bottom: 18px;
            color: var(--red);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 760px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(58px, 7vw, 92px);
            font-weight: 600;
            line-height: 0.88;
            letter-spacing: -2px;
        }

        .hero h1 span {
            color: var(--red);
        }

        .hero-description {
            max-width: 400px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        /* SECTION */

        .section {
            padding-top: 60px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 24px;
        }

        .section-heading h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 1;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 12px;
        }

        /* CARDS */

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .card {
            min-height: 250px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--charcoal);
            text-decoration: none;
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .card:visited {
            color: var(--charcoal);
        }

        .card:hover {
            color: var(--charcoal);
            transform: translateY(-3px);
            border-color: rgba(155, 61, 74, 0.35);
            box-shadow: 0 15px 35px rgba(50, 40, 35, 0.07);
        }

        .card-label {
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .card-content h3 {
            margin-bottom: 10px;
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            font-weight: 600;
        }

        .card-content p {
            max-width: 330px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .card-arrow {
            align-self: flex-end;
            color: var(--red);
            font-size: 24px;
            line-height: 1;
        }

        /* ACCOUNT */

        .account-section {
            padding-top: 70px;
        }

        .account-box {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .account-item {
            padding: 25px 25px 25px 0;
            border-right: 1px solid var(--border);
        }

        .account-item:not(:first-child) {
            padding-left: 25px;
        }

        .account-item:last-child {
            border-right: none;
        }

        .account-label {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .account-value {
            color: var(--charcoal);
            font-size: 14px;
            font-weight: 500;
            word-break: break-word;
        }

        /* AI */

        .ai-section {
            padding-top: 70px;
        }

        .ai-box {
            padding: 42px;
            border: 1px solid var(--border);
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        .ai-label {
            margin-bottom: 12px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .ai-box h3 {
            margin-bottom: 8px;
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
        }

        .ai-box p {
            max-width: 550px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .coming-soon {
            flex-shrink: 0;
            padding: 11px 18px;
            border: 1px solid var(--border);
            background: var(--cream-light);
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
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

        @media (max-width: 1000px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 850px) {

            .navbar {
                padding: 0 6%;
            }

            .nav-right {
                gap: 15px;
            }

            .nav-link {
                display: none;
            }

            .main {
                width: 88%;
                padding-top: 55px;
            }

            .hero {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .account-box {
                grid-template-columns: 1fr;
            }

            .account-item {
                padding: 20px 0 !important;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .account-item:last-child {
                border-bottom: none;
            }

            .ai-box {
                flex-direction: column;
                align-items: flex-start;
            }

        }

        @media (max-width: 500px) {

            .navbar {
                height: 70px;
            }

            .brand {
                font-size: 28px;
            }

            .logout-link {
                padding: 9px 13px;
            }

            .main {
                padding: 45px 0 70px;
            }

            .hero {
                padding-bottom: 50px;
            }

            .hero h1 {
                font-size: 58px;
            }

            .section {
                padding-top: 50px;
            }

            .section-heading {
                display: block;
            }

            .section-heading h2 {
                margin-bottom: 8px;
            }

            .card {
                min-height: 220px;
                padding: 26px;
            }

            .ai-box {
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
        href="dashboard.php"
        class="brand"
    >
        Digit<span>alia</span>
    </a>

    <div class="nav-right">

        <a
            href="books.php"
            class="nav-link"
        >
            Browse Books
        </a>

        <a
            href="my-library.php"
            class="nav-link"
        >
            My Library
        </a>

        <a
            href="logout.php"
            class="logout-link"
        >
            Log Out
        </a>

    </div>

</nav>


<main class="main">

    <section class="hero">

        <div>

            <div class="eyebrow">
                Your Digital Library
            </div>

            <h1>
                Welcome back,
                <span>
                    <?php echo htmlspecialchars($full_name); ?>.
                </span>
            </h1>

        </div>

        <p class="hero-description">
            Discover books, build your personal collection,
            and keep everything you love to read in one place.
        </p>

    </section>


    <section class="section">

        <div class="section-heading">

            <div>
                <h2>
                    Your Library
                </h2>
            </div>

            <p>
                Explore and manage your collection
            </p>

        </div>


        <div class="cards">

            <a
                href="books.php"
                class="card"
            >

                <div class="card-label">
                    Discover
                </div>

                <div class="card-content">

                    <h3>
                        Browse Books
                    </h3>

                    <p>
                        Explore the Digitalia collection
                        and find your next book to read.
                    </p>

                </div>

                <div class="card-arrow">
                    →
                </div>

            </a>


            <a
                href="my-library.php"
                class="card"
            >

                <div class="card-label">
                    Collection
                </div>

                <div class="card-content">

                    <h3>
                        My Library
                    </h3>

                    <p>
                        View the books you have saved
                        and keep your personal collection organized.
                    </p>

                </div>

                <div class="card-arrow">
                    →
                </div>

            </a>


            <a
                href="my-downloads.php"
                class="card"
            >

                <div class="card-label">
                    History
                </div>

                <div class="card-content">

                    <h3>
                        My Downloads
                    </h3>

                    <p>
                        View the books you have downloaded
                        and access your download history anytime.
                    </p>

                </div>

                <div class="card-arrow">
                    →
                </div>

            </a>

        </div>

    </section>


    <section class="account-section">

        <div class="section-heading">

            <div>
                <h2>
                    Account
                </h2>
            </div>

            <p>
                Your Digitalia profile
            </p>

        </div>


        <div class="account-box">

            <div class="account-item">

                <div class="account-label">
                    Full Name
                </div>

                <div class="account-value">
                    <?php echo htmlspecialchars($full_name); ?>
                </div>

            </div>


            <div class="account-item">

                <div class="account-label">
                    Email Address
                </div>

                <div class="account-value">
                    <?php echo htmlspecialchars($email); ?>
                </div>

            </div>


            <div class="account-item">

                <div class="account-label">
                    Account Type
                </div>

                <div class="account-value">
                    <?php echo htmlspecialchars($role); ?>
                </div>

            </div>

        </div>

    </section>


    <section class="ai-section">

        <div class="ai-box">

            <div>

                <div class="ai-label">
                    Coming in a later phase
                </div>

                <h3>
                    Digitalia AI Assistant
                </h3>

                <p>
                    Your future reading companion will help you
                    search the library, compare books, discover
                    recommendations, and manage your collection
                    through natural-language conversations.
                </p>

            </div>

            <div class="coming-soon">
                Coming Soon
            </div>

        </div>

    </section>


    <footer class="footer">

        <span>
            Digitalia
        </span>

        <span>
            Your personal digital library
        </span>

    </footer>

</main>

</body>

</html>