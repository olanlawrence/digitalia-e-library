<?php

session_start();

require_once __DIR__ . '/config/database.php';

/*
 * Registered users should use My Library instead.
 */

if (isset($_SESSION['user_id'])) {
    header('Location: my-library.php');
    exit;
}

/*
 * Make sure this is a guest session.
 */

if (!isset($_SESSION['guest'])) {
    $_SESSION['guest'] = true;
}

/*
 * Get the current guest session ID.
 */

$guest_session_id = session_id();

/*
 * Get all downloads made during the current guest session.
 *
 * Duplicate downloads are intentionally included.
 */

$downloadStmt = $pdo->prepare(
    "SELECT
        books.title,
        books.author,
        books.category,
        downloads.downloaded_at
     FROM downloads
     INNER JOIN books
        ON downloads.book_id = books.id
     WHERE downloads.guest_session_id = ?
     AND downloads.download_type = 'guest'
     ORDER BY downloads.downloaded_at DESC"
);

$downloadStmt->execute([
    $guest_session_id
]);

$downloads = $downloadStmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * The database is the source of truth for the count.
 */

$guestDownloadCount = count($downloads);

$remainingDownloads = max(
    0,
    5 - $guestDownloadCount
);

/*
 * Keep the session counter synchronized.
 */

$_SESSION['guest_downloads'] = $guestDownloadCount;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Your Downloads - Digitalia</title>

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
            gap: 24px;
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 70px 0 90px;
        }

        /* PAGE HEADER */

        .page-header {
            padding-bottom: 50px;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            margin-bottom: 17px;
            color: var(--red);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .page-header h1 {
            margin-bottom: 22px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(62px, 8vw, 94px);
            font-weight: 600;
            line-height: 0.86;
            letter-spacing: -2px;
        }

        .page-header h1 span {
            color: var(--red);
        }

        .page-description {
            max-width: 620px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        /* SUMMARY */

        .summary {
            margin-top: 35px;
            padding: 28px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            border: 1px solid var(--border);
            background: var(--white);
        }

        .summary-label {
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .summary-number {
            font-family: "Cormorant Garamond", serif;
            font-size: 42px;
            font-weight: 600;
            line-height: 1;
        }

        .summary-number span {
            color: #aaa29c;
            font-size: 25px;
        }

        .summary-remaining {
            color: var(--muted);
            font-size: 11px;
        }

        .summary-remaining strong {
            color: var(--charcoal);
        }

        /* DOWNLOAD LIST */

        .downloads-section {
            padding-top: 55px;
        }

        .section-heading {
            margin-bottom: 25px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .section-heading h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 1;
        }

        .section-count {
            color: var(--muted);
            font-size: 11px;
        }

        .download-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .download-item {
            padding: 28px;
            display: grid;
            grid-template-columns: 70px 1fr 250px;
            gap: 25px;
            align-items: center;
            border: 1px solid var(--border);
            background: var(--white);
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .download-item:hover {
            transform: translateY(-2px);
            border-color: rgba(155, 61, 74, 0.35);
            box-shadow: 0 12px 30px rgba(50, 40, 35, 0.06);
        }

        .download-number {
            color: #a9a19a;
            font-family: "Cormorant Garamond", serif;
            font-size: 28px;
        }

        .download-info {
            min-width: 0;
        }

        .download-category {
            display: inline-block;
            margin-bottom: 9px;
            padding: 5px 8px;
            border: 1px solid rgba(155, 61, 74, 0.2);
            color: var(--red);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .download-title {
            margin-bottom: 6px;
            font-family: "Cormorant Garamond", serif;
            font-size: 31px;
            font-weight: 600;
            line-height: 1;
        }

        .download-author {
            color: var(--red);
            font-size: 12px;
            font-weight: 600;
        }

        .download-time {
            padding-left: 25px;
            border-left: 1px solid var(--border);
        }

        .download-time-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .download-time-value {
            color: var(--charcoal);
            font-size: 12px;
            line-height: 1.6;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 75px 30px;
            border: 1px solid var(--border);
            background: var(--white);
            text-align: center;
        }

        .empty-state h3 {
            margin-bottom: 12px;
            font-family: "Cormorant Garamond", serif;
            font-size: 42px;
            font-weight: 600;
        }

        .empty-state p {
            max-width: 480px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* ACTIONS */

        .actions {
            margin-top: 45px;
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .button {
            min-height: 46px;
            padding: 0 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        .button.secondary {
            border-color: var(--border);
            background: transparent;
            color: var(--charcoal);
        }

        .button.secondary:hover {
            border-color: var(--charcoal);
            background: transparent;
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

        @media (max-width: 800px) {

            .download-item {
                grid-template-columns: 55px 1fr;
            }

            .download-time {
                grid-column: 2;
                padding-left: 0;
                padding-top: 15px;
                border-left: 0;
                border-top: 1px solid var(--border);
            }

        }

        @media (max-width: 650px) {

            .navbar {
                padding: 0 6%;
            }

            .nav-right {
                gap: 12px;
            }

            .nav-link {
                display: none;
            }

            .main {
                width: 88%;
                padding: 50px 0 70px;
            }

            .page-header h1 {
                font-size: 62px;
            }

            .summary {
                display: block;
            }

            .summary-remaining {
                margin-top: 10px;
            }

            .download-item {
                padding: 23px;
                grid-template-columns: 45px 1fr;
                gap: 18px;
            }

            .download-title {
                font-size: 28px;
            }

            .footer {
                display: block;
                line-height: 2;
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

            .download-item {
                grid-template-columns: 1fr;
            }

            .download-number {
                font-size: 24px;
            }

            .download-time {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>

<nav class="navbar">

    <a
        href="guest.php"
        class="brand"
    >
        Digit<span>alia</span>
    </a>

    <div class="nav-right">

        <a
            href="guest.php"
            class="nav-link"
        >
            Guest Home
        </a>

        <a
            href="books.php"
            class="nav-link"
        >
            Browse Books
        </a>

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

    <section class="page-header">

        <div class="eyebrow">
            Guest Activity
        </div>

        <h1>
            Your
            <span>Downloads.</span>
        </h1>

        <p class="page-description">
            These are the books you have downloaded during
            your current guest session. Your guest downloads
            are limited to five books.
        </p>

    </section>


    <section>

        <div class="summary">

            <div>

                <div class="summary-label">
                    Downloads Used
                </div>

                <div class="summary-number">

                    <?php echo $guestDownloadCount; ?>

                    <span>
                        / 5
                    </span>

                </div>

            </div>

            <div class="summary-remaining">

                <strong>
                    <?php echo $remainingDownloads; ?>
                </strong>

                download<?php echo $remainingDownloads === 1 ? '' : 's'; ?>

                remaining

            </div>

        </div>

    </section>


    <section class="downloads-section">

        <div class="section-heading">

            <h2>
                Download History
            </h2>

            <div class="section-count">

                <?php echo $guestDownloadCount; ?>

                <?php echo $guestDownloadCount === 1 ? 'download' : 'downloads'; ?>

            </div>

        </div>


        <?php if ($guestDownloadCount > 0): ?>

            <div class="download-list">

                <?php foreach ($downloads as $index => $download): ?>

                    <article class="download-item">

                        <div class="download-number">

                            <?php
                            echo str_pad(
                                $index + 1,
                                2,
                                '0',
                                STR_PAD_LEFT
                            );
                            ?>

                        </div>


                        <div class="download-info">

                            <div class="download-category">

                                <?php
                                echo htmlspecialchars(
                                    $download['category']
                                );
                                ?>

                            </div>

                            <h3 class="download-title">

                                <?php
                                echo htmlspecialchars(
                                    $download['title']
                                );
                                ?>

                            </h3>

                            <div class="download-author">

                                <?php
                                echo htmlspecialchars(
                                    $download['author']
                                );
                                ?>

                            </div>

                        </div>


                        <div class="download-time">

                            <div class="download-time-label">
                                Downloaded
                            </div>

                            <div class="download-time-value">

                                <?php
                                echo date(
                                    'M d, Y · h:i A',
                                    strtotime(
                                        $download['downloaded_at']
                                    )
                                );
                                ?>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <h3>
                    Nothing downloaded yet.
                </h3>

                <p>
                    Browse the Digitalia collection and download
                    a book to see your activity here.
                </p>

                <div class="actions">

                    <a
                        href="books.php"
                        class="button"
                    >
                        Browse Books
                    </a>

                </div>

            </div>

        <?php endif; ?>


        <?php if ($guestDownloadCount > 0): ?>

            <div class="actions">

                <a
                    href="books.php"
                    class="button"
                >
                    Browse Books
                </a>

                <a
                    href="guest.php"
                    class="button secondary"
                >
                    Back to Guest Home
                </a>

            </div>

        <?php endif; ?>

    </section>


    <footer class="footer">

        <span>
            Digitalia
        </span>

        <span>
            Guest activity
        </span>

    </footer>

</main>

</body>

</html>