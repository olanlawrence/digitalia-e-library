<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config/database.php';

$user_id = $_SESSION['user_id'];

/*
 * Get all downloads belonging to the logged-in user.
 */

$stmt = $pdo->prepare(
    "SELECT
        downloads.id AS download_id,
        books.id AS book_id,
        books.title,
        books.author,
        books.category,
        books.description,
        books.file_url,
        downloads.downloaded_at
     FROM downloads
     INNER JOIN books
        ON downloads.book_id = books.id
     WHERE downloads.user_id = ?
     AND downloads.download_type = 'registered'
     ORDER BY downloads.downloaded_at DESC"
);

$stmt->execute([$user_id]);

$downloads = $stmt->fetchAll(PDO::FETCH_ASSOC);

$downloadCount = count($downloads);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Downloads - Digitalia</title>

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

        /* HEADER */

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 40px;
            padding-bottom: 55px;
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

        .page-header h1 {
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(58px, 7vw, 88px);
            font-weight: 600;
            line-height: 0.88;
            letter-spacing: -2px;
        }

        .page-header h1 span {
            color: var(--red);
        }

        .header-description {
            max-width: 390px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        /* SUMMARY */

        .summary {
            padding: 30px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .summary-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .summary-count {
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
        }

        /* DOWNLOADS */

        .downloads-section {
            padding-top: 55px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 25px;
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

        .downloads-list {
            display: flex;
            flex-direction: column;
            border-top: 1px solid var(--border);
        }

        .download-item {
            display: grid;
            grid-template-columns: 55px 1fr auto;
            gap: 25px;
            align-items: center;
            padding: 28px 0;
            border-bottom: 1px solid var(--border);
        }

        .download-number {
            color: var(--red);
            font-family: "Cormorant Garamond", serif;
            font-size: 28px;
            font-weight: 600;
        }

        .download-info {
            min-width: 0;
        }

        .download-category {
            margin-bottom: 7px;
            color: var(--red);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .download-title {
            margin-bottom: 5px;
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 30px;
            font-weight: 600;
            line-height: 1.1;
        }

        .download-author {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .download-date {
            color: #918982;
            font-size: 10px;
            letter-spacing: 0.3px;
        }

        .download-action {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .download-button {
            display: inline-block;
            padding: 11px 18px;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            text-transform: uppercase;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .download-button:visited {
            color: var(--white);
        }

        .download-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 75px 30px;
            border: 1px solid var(--border);
            background: var(--white);
            text-align: center;
        }

        .empty-state-label {
            margin-bottom: 14px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .empty-state h2 {
            margin-bottom: 12px;
            font-family: "Cormorant Garamond", serif;
            font-size: 42px;
            font-weight: 600;
        }

        .empty-state p {
            max-width: 430px;
            margin: 0 auto 25px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .browse-button {
            display: inline-block;
            padding: 12px 20px;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            text-transform: uppercase;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .browse-button:visited {
            color: var(--white);
        }

        .browse-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
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

            .page-header {
                display: block;
            }

            .header-description {
                margin-top: 30px;
            }

            .download-item {
                grid-template-columns: 45px 1fr;
                gap: 18px;
            }

            .download-action {
                grid-column: 2;
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

            .page-header {
                padding-bottom: 45px;
            }

            .page-header h1 {
                font-size: 58px;
            }

            .summary {
                padding: 25px 0;
            }

            .downloads-section {
                padding-top: 45px;
            }

            .section-heading {
                display: block;
            }

            .section-heading h2 {
                margin-bottom: 8px;
            }

            .download-item {
                grid-template-columns: 38px 1fr;
                gap: 15px;
                padding: 25px 0;
            }

            .download-number {
                font-size: 24px;
            }

            .download-title {
                font-size: 27px;
            }

            .download-action {
                grid-column: 2;
            }

            .download-button {
                width: 100%;
                text-align: center;
            }

            .empty-state {
                padding: 55px 20px;
            }

            .empty-state h2 {
                font-size: 36px;
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

    <header class="page-header">

        <div>

            <div class="eyebrow">
                Your Reading Activity
            </div>

            <h1>
                My
                <span>Downloads.</span>
            </h1>

        </div>

        <p class="header-description">
            Keep track of the books you have downloaded
            from the Digitalia collection.
        </p>

    </header>


    <section class="summary">

        <div class="summary-label">
            Total Downloads
        </div>

        <div class="summary-count">
            <?php echo $downloadCount; ?>
        </div>

    </section>


    <section class="downloads-section">

        <div class="section-heading">

            <h2>
                Download History
            </h2>

            <p>
                Your downloaded books
            </p>

        </div>


        <?php if (empty($downloads)): ?>

            <div class="empty-state">

                <div class="empty-state-label">
                    Nothing here yet
                </div>

                <h2>
                    Your downloads will appear here.
                </h2>

                <p>
                    Browse the Digitalia collection and
                    download a book to start building your
                    download history.
                </p>

                <a
                    href="books.php"
                    class="browse-button"
                >
                    Browse Books
                </a>

            </div>

        <?php else: ?>

            <div class="downloads-list">

                <?php foreach ($downloads as $index => $download): ?>

                    <article class="download-item">

                        <div class="download-number">
                            <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                        </div>


                        <div class="download-info">

                            <div class="download-category">
                                <?php echo htmlspecialchars($download['category']); ?>
                            </div>

                            <div class="download-title">
                                <?php echo htmlspecialchars($download['title']); ?>
                            </div>

                            <div class="download-author">
                                by <?php echo htmlspecialchars($download['author']); ?>
                            </div>

                            <div class="download-date">
                                Downloaded
                                <?php
                                echo date(
                                    'F j, Y · g:i A',
                                    strtotime($download['downloaded_at'])
                                );
                                ?>
                            </div>

                        </div>


                        <div class="download-action">

                            <a
                                href="download.php?book_id=<?php echo (int) $download['book_id']; ?>"
                                class="download-button"
                            >
                                Download Again
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

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