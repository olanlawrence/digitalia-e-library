<?php

session_start();

require_once __DIR__ . '/config/database.php';

$book_id = isset($_GET['book_id'])
    ? (int) $_GET['book_id']
    : 0;

if ($book_id <= 0) {
    die("Invalid book.");
}

/*
 * Get book information.
 */

$bookStmt = $pdo->prepare(
    "SELECT *
     FROM books
     WHERE id = ?"
);

$bookStmt->execute([$book_id]);

$book = $bookStmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Book not found.");
}

/*
 * Registered user download.
 */

if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];

    /*
     * Count registered user downloads.
     */

    $countStmt = $pdo->prepare(
        "SELECT COUNT(*)
         FROM downloads
         WHERE user_id = ?
         AND download_type = 'registered'"
    );

    $countStmt->execute([$user_id]);

    $downloadCount = (int) $countStmt->fetchColumn();

    /*
     * Registered users are limited to 10 downloads.
     */

    if ($downloadCount >= 10) {
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Download Limit | Digitalia</title>

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                    min-height: 100vh;
                    background: var(--cream);
                    color: var(--charcoal);
                    font-family: "DM Sans", sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 32px 20px;
                }

                .container {
                    width: 100%;
                    max-width: 620px;
                    text-align: center;
                }

                .brand {
                    font-family: "Cormorant Garamond", serif;
                    font-size: 30px;
                    font-weight: 700;
                    color: var(--red);
                    margin-bottom: 52px;
                }

                .card {
                    background: var(--cream-light);
                    border: 1px solid var(--border);
                    padding: 56px 48px;
                }

                .eyebrow {
                    color: var(--red);
                    font-size: 12px;
                    font-weight: 700;
                    letter-spacing: 0.14em;
                    text-transform: uppercase;
                    margin-bottom: 14px;
                }

                h1 {
                    margin: 0 0 16px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: clamp(42px, 7vw, 64px);
                    line-height: 0.95;
                    font-weight: 600;
                }

                p {
                    margin: 0 auto;
                    max-width: 460px;
                    color: var(--muted);
                    line-height: 1.7;
                    font-size: 15px;
                }

                .navigation {
                    margin-top: 34px;
                    display: flex;
                    justify-content: center;
                    gap: 12px;
                    flex-wrap: wrap;
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

                @media (max-width: 600px) {
                    .card {
                        padding: 44px 24px;
                    }
                }
            </style>
        </head>

        <body>

        <main class="container">

            <div class="brand">Digitalia</div>

            <section class="card">

                <div class="eyebrow">Download limit reached</div>

                <h1>Your limit has been reached.</h1>

                <p>
                    Registered accounts can download up to 10 books.
                    You have already reached your current download limit.
                </p>

                <div class="navigation">

                    <a href="books.php" class="button">
                        Browse Books
                    </a>

                    <a href="dashboard.php" class="button secondary">
                        Back to Dashboard
                    </a>

                </div>

            </section>

        </main>

        </body>
        </html>

        <?php
        exit;
    }

    /*
     * Record registered user download.
     */

    $insertStmt = $pdo->prepare(
        "INSERT INTO downloads
            (user_id, guest_session_id, book_id, download_type)
         VALUES
            (?, NULL, ?, 'registered')"
    );

    $insertStmt->execute([
        $user_id,
        $book_id
    ]);

    $downloadCount++;

    $downloadType = "Registered User";
    $remaining = 10 - $downloadCount;

    $backToDashboard = true;

} else {

    /*
     * Guest download.
     */

    if (!isset($_SESSION['guest'])) {
        $_SESSION['guest'] = true;
        $_SESSION['guest_downloads'] = 0;
    }

    /*
     * Count guest downloads.
     */

    $guestDownloadCount = (int) $_SESSION['guest_downloads'];

    /*
     * Guests are limited to 5 downloads.
     */

    if ($guestDownloadCount >= 5) {
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Guest Download Limit | Digitalia</title>

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                    min-height: 100vh;
                    background: var(--cream);
                    color: var(--charcoal);
                    font-family: "DM Sans", sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 32px 20px;
                }

                .container {
                    width: 100%;
                    max-width: 620px;
                    text-align: center;
                }

                .brand {
                    font-family: "Cormorant Garamond", serif;
                    font-size: 30px;
                    font-weight: 700;
                    color: var(--red);
                    margin-bottom: 52px;
                }

                .card {
                    background: var(--cream-light);
                    border: 1px solid var(--border);
                    padding: 56px 48px;
                }

                .eyebrow {
                    color: var(--red);
                    font-size: 12px;
                    font-weight: 700;
                    letter-spacing: 0.14em;
                    text-transform: uppercase;
                    margin-bottom: 14px;
                }

                h1 {
                    margin: 0 0 16px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: clamp(42px, 7vw, 64px);
                    line-height: 0.95;
                    font-weight: 600;
                }

                p {
                    margin: 0 auto;
                    max-width: 460px;
                    color: var(--muted);
                    line-height: 1.7;
                    font-size: 15px;
                }

                .navigation {
                    margin-top: 34px;
                    display: flex;
                    justify-content: center;
                    gap: 12px;
                    flex-wrap: wrap;
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

                @media (max-width: 600px) {
                    .card {
                        padding: 44px 24px;
                    }
                }
            </style>
        </head>

        <body>

        <main class="container">

            <div class="brand">Digitalia</div>

            <section class="card">

                <div class="eyebrow">Guest limit reached</div>

                <h1>You've reached your limit.</h1>

                <p>
                    Guest users can download up to 5 books per session.
                    Create an account to continue exploring Digitalia
                    with a higher download limit and access to My Library.
                </p>

                <div class="navigation">

                    <a href="register.php" class="button">
                        Create an Account
                    </a>

                    <a href="books.php" class="button secondary">
                        Browse Books
                    </a>

                </div>

            </section>

        </main>

        </body>
        </html>

        <?php
        exit;
    }

    /*
     * Make sure the guest has a session ID.
     */

    $guest_session_id = session_id();

    /*
     * Record guest download together with the session ID.
     */

    $insertStmt = $pdo->prepare(
        "INSERT INTO downloads
            (user_id, guest_session_id, book_id, download_type)
         VALUES
            (NULL, ?, ?, 'guest')"
    );

    $insertStmt->execute([
        $guest_session_id,
        $book_id
    ]);

    /*
     * Increase the guest's session download count.
     */

    $_SESSION['guest_downloads']++;

    $downloadCount = (int) $_SESSION['guest_downloads'];

    $downloadType = "Guest";
    $remaining = 5 - $downloadCount;

    $backToDashboard = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Download Recorded | Digitalia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            min-height: 100vh;
            background: var(--cream);
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .container {
            width: 100%;
            max-width: 680px;
        }

        .brand {
            text-align: center;
            font-family: "Cormorant Garamond", serif;
            font-size: 30px;
            font-weight: 700;
            color: var(--red);
            margin-bottom: 52px;
        }

        .card {
            background: var(--cream-light);
            border: 1px solid var(--border);
            padding: 56px 48px;
        }

        .eyebrow {
            color: var(--red);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0 0 20px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(42px, 7vw, 68px);
            line-height: 0.95;
            font-weight: 600;
        }

        .book-title {
            font-family: "Cormorant Garamond", serif;
            font-size: 27px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .author {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 28px;
        }

        .details {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 20px 0;
            display: grid;
            gap: 13px;
            margin-bottom: 28px;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            font-size: 13px;
        }

        .label {
            color: var(--muted);
        }

        .value {
            font-weight: 600;
            text-align: right;
        }

        .message {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 22px;
            background: var(--red);
            border: 1px solid var(--red);
            color: var(--white);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
            transition: 0.2s ease;
        }

        .file-link:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 22px;
            border: 1px solid var(--red);
            background: transparent;
            color: var(--red);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .button:hover {
            background: var(--red);
            color: var(--white);
        }

        .button.primary {
            background: var(--red);
            color: var(--white);
        }

        .button.primary:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        @media (max-width: 600px) {

            .card {
                padding: 44px 24px;
            }

            .detail {
                flex-direction: column;
                gap: 4px;
            }

            .value {
                text-align: left;
            }
        }

    </style>

</head>

<body>

<main class="container">

    <div class="brand">
        Digitalia
    </div>

    <section class="card">

        <div class="eyebrow">
            Download recorded
        </div>

        <h1>
            You're all set.
        </h1>

        <div class="book-title">
            <?= htmlspecialchars($book['title']) ?>
        </div>

        <div class="author">
            <?= htmlspecialchars($book['author']) ?>
        </div>

        <div class="details">

            <div class="detail">
                <span class="label">Download type</span>
                <span class="value">
                    <?= htmlspecialchars($downloadType) ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Downloads used</span>
                <span class="value">
                    <?= $downloadCount ?> / <?= $downloadType === 'Guest' ? 5 : 10 ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Remaining</span>
                <span class="value">
                    <?= $remaining ?>
                </span>
            </div>

        </div>

        <?php if (!empty($book['file_url']) && $book['file_url'] !== '#'): ?>

            <div class="message">
                Your download has been recorded. You may now access the
                legally authorized book file using the button below.
            </div>

            <a
                href="<?= htmlspecialchars($book['file_url']) ?>"
                class="file-link"
                target="_blank"
                rel="noopener noreferrer"
            >
                Access Book File
            </a>

        <?php else: ?>

            <div class="message">
                Your download has been recorded. A legally authorized
                book file is not currently available for this title.
            </div>

        <?php endif; ?>

        <div class="navigation">

            <a href="books.php" class="button primary">
                Back to Book Catalog
            </a>

            <?php if ($backToDashboard): ?>

                <a href="dashboard.php" class="button">
                    Back to Dashboard
                </a>

            <?php endif; ?>

        </div>

    </section>

</main>

</body>
</html>