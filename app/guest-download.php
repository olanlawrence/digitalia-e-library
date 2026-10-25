<?php

session_start();

require_once __DIR__ . '/config/database.php';

/*
 * Guest users only.
 */

if (isset($_SESSION['user_id'])) {
    header("Location: my-library.php");
    exit;
}

/*
 * Make sure a guest session exists.
 */

if (!isset($_SESSION['guest'])) {
    $_SESSION['guest'] = true;
    $_SESSION['guest_downloads'] = 0;
}

$guest_session_id = session_id();

$guest_download_count = (int) $_SESSION['guest_downloads'];

/*
 * Get books downloaded during the current guest session.
 */

$stmt = $pdo->prepare(
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

$stmt->execute([$guest_session_id]);

$downloads = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Guest Downloads | Digitalia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

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

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
        }

        .navbar {
            width: 100%;
            border-bottom: 1px solid var(--border);
            background: rgba(252, 250, 246, 0.94);
        }

        .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            min-height: 78px;
            padding: 0 28px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            font-family: "Cormorant Garamond", serif;
            font-size: 30px;
            font-weight: 700;
            color: var(--red);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-links a {
            color: var(--charcoal);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .nav-links a:hover {
            color: var(--red);
        }

        .nav-links .account-link {
            color: var(--red);
        }

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 76px 28px 90px;
        }

        .header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 48px;
        }

        .eyebrow {
            margin-bottom: 12px;
            color: var(--red);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(52px, 7vw, 78px);
            line-height: 0.9;
            font-weight: 600;
        }

        .header-copy {
            max-width: 390px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 30px;
            padding: 20px 24px;

            background: var(--cream-light);
            border: 1px solid var(--border);
        }

        .summary-label {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .summary-count {
            color: var(--red);
            font-size: 18px;
            font-weight: 700;
        }

        .book-list {
            display: grid;
            gap: 14px;
        }

        .book-card {
            background: var(--cream-light);
            border: 1px solid var(--border);
            padding: 28px 30px;

            display: grid;
            grid-template-columns: 54px 1fr auto;
            align-items: center;
            gap: 24px;
        }

        .book-number {
            font-family: "Cormorant Garamond", serif;
            color: var(--red);
            font-size: 25px;
            font-weight: 600;
        }

        .book-category {
            margin-bottom: 5px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .book-title {
            margin-bottom: 5px;
            font-family: "Cormorant Garamond", serif;
            font-size: 28px;
            font-weight: 600;
            line-height: 1.1;
        }

        .book-author {
            color: var(--muted);
            font-size: 13px;
        }

        .downloaded-time {
            color: var(--muted);
            font-size: 12px;
            text-align: right;
            line-height: 1.6;
        }

        .empty-state {
            background: var(--cream-light);
            border: 1px solid var(--border);
            padding: 72px 30px;
            text-align: center;
        }

        .empty-state h2 {
            margin: 0 0 12px;
            font-family: "Cormorant Garamond", serif;
            font-size: 42px;
            font-weight: 600;
        }

        .empty-state p {
            max-width: 450px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 32px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 46px;
            padding: 0 22px;

            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);

            text-decoration: none;
            font-size: 13px;
            font-weight: 600;

            transition: 0.2s ease;
        }

        .button:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        .button.secondary {
            background: transparent;
            color: var(--red);
        }

        .button.secondary:hover {
            background: var(--red);
            color: var(--white);
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 28px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 760px) {

            .nav-inner {
                min-height: auto;
                padding-top: 20px;
                padding-bottom: 20px;
                align-items: flex-start;
            }

            .nav-links {
                gap: 14px;
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            .header {
                display: block;
            }

            .header-copy {
                margin-top: 22px;
            }

            .book-card {
                grid-template-columns: 42px 1fr;
            }

            .downloaded-time {
                grid-column: 2;
                text-align: left;
            }

        }

        @media (max-width: 560px) {

            .nav-inner {
                flex-direction: column;
            }

            .nav-links {
                justify-content: flex-start;
            }

            .page {
                padding: 54px 20px 70px;
            }

            .summary {
                align-items: flex-start;
                flex-direction: column;
            }

            .book-card {
                padding: 24px;
            }

        }

    </style>

</head>

<body>

<nav class="navbar">

    <div class="nav-inner">

        <a href="index.php" class="brand">
            Digitalia
        </a>

        <div class="nav-links">

            <a href="books.php">
                Browse Books
            </a>

            <a href="guest.php">
                Guest Home
            </a>

            <a href="register.php" class="account-link">
                Create Account
            </a>

            <a href="login.php">
                Log In
            </a>

        </div>

    </div>

</nav>

<main class="page">

    <header class="header">

        <div>

            <div class="eyebrow">
                Guest activity
            </div>

            <h1>
                Your Downloads.
            </h1>

        </div>

        <div class="header-copy">
            These are the books you have downloaded during
            your current guest session. Your guest downloads
            are limited to five books.
        </div>

    </header>

    <div class="summary">

        <span class="summary-label">
            Downloads used
        </span>

        <span class="summary-count">
            <?= $guest_download_count ?> / 5
        </span>

    </div>

    <?php if (!empty($downloads)): ?>

        <section class="book-list">

            <?php foreach ($downloads as $index => $download): ?>

                <article class="book-card">

                    <div class="book-number">
                        <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                    </div>

                    <div>

                        <div class="book-category">
                            <?= htmlspecialchars($download['category']) ?>
                        </div>

                        <div class="book-title">
                            <?= htmlspecialchars($download['title']) ?>
                        </div>

                        <div class="book-author">
                            <?= htmlspecialchars($download['author']) ?>
                        </div>

                    </div>

                    <div class="downloaded-time">

                        Downloaded<br>

                        <?= date(
                            'M d, Y · h:i A',
                            strtotime($download['downloaded_at'])
                        ) ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <section class="empty-state">

            <h2>
                Nothing downloaded yet.
            </h2>

            <p>
                Explore the Digitalia collection and download
                a book to see it appear here during your current
                guest session.
            </p>

            <div class="actions">

                <a href="books.php" class="button">
                    Browse Books
                </a>

                <a href="register.php" class="button secondary">
                    Create an Account
                </a>

            </div>

        </section>

    <?php endif; ?>

</main>

<footer>

    Guest downloads are available only during your current session.

</footer>

</body>

</html>