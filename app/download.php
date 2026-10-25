<?php

session_start();

require_once __DIR__ . '/config/database.php';

$book_id = isset($_GET['book_id']) ? (int) $_GET['book_id'] : 0;

if ($book_id <= 0) {
    die("Invalid book.");
}

$stmt = $pdo->prepare(
    "SELECT id, title, file_url
     FROM books
     WHERE id = ?"
);

$stmt->execute([$book_id]);

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Book not found.");
}

$download_type = 'guest';
$user_id = null;

/*
 * REGISTERED USER
 */

if (isset($_SESSION['user_id'])) {

    $download_type = 'registered';
    $user_id = $_SESSION['user_id'];

    $countStmt = $pdo->prepare(
        "SELECT COUNT(*)
         FROM downloads
         WHERE user_id = ?
         AND download_type = 'registered'"
    );

    $countStmt->execute([$user_id]);

    $download_count = (int) $countStmt->fetchColumn();

    if ($download_count >= 10) {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>

            <meta charset="UTF-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0"
            >

            <title>Download Limit - Digitalia</title>

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
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 30px;
                    background: var(--cream-light);
                    color: var(--charcoal);
                    font-family: "DM Sans", sans-serif;
                }

                .container {
                    width: 100%;
                    max-width: 620px;
                    text-align: center;
                }

                .brand {
                    margin-bottom: 50px;
                    color: var(--charcoal);
                    font-family: "Cormorant Garamond", serif;
                    font-size: 34px;
                    font-weight: 600;
                    text-decoration: none;
                }

                .brand span {
                    color: var(--red);
                }

                .eyebrow {
                    margin-bottom: 20px;
                    color: var(--red);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 2.5px;
                    text-transform: uppercase;
                }

                h1 {
                    margin-bottom: 20px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: clamp(55px, 8vw, 78px);
                    font-weight: 600;
                    line-height: 0.9;
                }

                .description {
                    max-width: 470px;
                    margin: 0 auto 35px;
                    color: var(--muted);
                    font-size: 13px;
                    line-height: 1.8;
                }

                .usage-card {
                    margin-bottom: 30px;
                    padding: 28px;
                    border: 1px solid var(--border);
                    background: var(--white);
                    text-align: left;
                }

                .usage-label {
                    margin-bottom: 10px;
                    color: var(--muted);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 1.5px;
                    text-transform: uppercase;
                }

                .usage-number {
                    margin-bottom: 18px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: 48px;
                    font-weight: 600;
                }

                .usage-number span {
                    color: #aaa29c;
                    font-size: 27px;
                }

                .progress {
                    width: 100%;
                    height: 4px;
                    background: #e9e3dc;
                }

                .progress-bar {
                    width: 100%;
                    height: 100%;
                    background: var(--red);
                }

                .actions {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                }

                .button {
                    min-height: 45px;
                    padding: 0 22px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    font-weight: 600;
                    text-decoration: none;
                }

                .primary {
                    border: 1px solid var(--red);
                    background: var(--red);
                    color: var(--white);
                }

                .primary:hover {
                    border-color: var(--red-dark);
                    background: var(--red-dark);
                }

                .secondary {
                    border: 1px solid var(--border);
                    background: var(--cream-light);
                    color: var(--charcoal);
                }

                .secondary:hover {
                    border-color: var(--charcoal);
                }

                @media (max-width: 500px) {

                    body {
                        padding: 25px;
                    }

                    .brand {
                        margin-bottom: 40px;
                    }

                    .actions {
                        flex-direction: column;
                    }

                    .button {
                        width: 100%;
                    }

                }

            </style>

        </head>

        <body>

            <main class="container">

                <div class="brand">
                    Digit<span>alia</span>
                </div>

                <div class="eyebrow">
                    Download Limit
                </div>

                <h1>
                    You've reached<br>
                    your limit.
                </h1>

                <p class="description">
                    Your account has reached the maximum of
                    10 downloads. You can continue exploring
                    the Digitalia collection while your current
                    download allowance is fully used.
                </p>

                <div class="usage-card">

                    <div class="usage-label">
                        Account Downloads
                    </div>

                    <div class="usage-number">
                        <?php echo $download_count; ?>
                        <span>/ 10</span>
                    </div>

                    <div class="progress">

                        <div class="progress-bar"></div>

                    </div>

                </div>

                <div class="actions">

                    <a
                        href="books.php"
                        class="button primary"
                    >
                        Browse Books
                    </a>

                    <a
                        href="dashboard.php"
                        class="button secondary"
                    >
                        Dashboard
                    </a>

                </div>

            </main>

        </body>

        </html>

        <?php

        exit;
    }

}

/*
 * GUEST USER
 */

else {

    if (!isset($_SESSION['guest'])) {
        $_SESSION['guest'] = true;
    }

    if (!isset($_SESSION['guest_downloads'])) {
        $_SESSION['guest_downloads'] = 0;
    }

    if ($_SESSION['guest_downloads'] >= 5) {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>

            <meta charset="UTF-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0"
            >

            <title>Guest Limit - Digitalia</title>

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
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 30px;
                    background: var(--cream-light);
                    color: var(--charcoal);
                    font-family: "DM Sans", sans-serif;
                }

                .container {
                    width: 100%;
                    max-width: 620px;
                    text-align: center;
                }

                .brand {
                    margin-bottom: 50px;
                    color: var(--charcoal);
                    font-family: "Cormorant Garamond", serif;
                    font-size: 34px;
                    font-weight: 600;
                }

                .brand span {
                    color: var(--red);
                }

                .eyebrow {
                    margin-bottom: 20px;
                    color: var(--red);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 2.5px;
                    text-transform: uppercase;
                }

                h1 {
                    margin-bottom: 20px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: clamp(55px, 8vw, 78px);
                    font-weight: 600;
                    line-height: 0.9;
                }

                .description {
                    max-width: 470px;
                    margin: 0 auto 35px;
                    color: var(--muted);
                    font-size: 13px;
                    line-height: 1.8;
                }

                .usage-card {
                    margin-bottom: 30px;
                    padding: 28px;
                    border: 1px solid var(--border);
                    background: var(--white);
                    text-align: left;
                }

                .usage-label {
                    margin-bottom: 10px;
                    color: var(--muted);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 1.5px;
                    text-transform: uppercase;
                }

                .usage-number {
                    margin-bottom: 18px;
                    font-family: "Cormorant Garamond", serif;
                    font-size: 48px;
                    font-weight: 600;
                }

                .usage-number span {
                    color: #aaa29c;
                    font-size: 27px;
                }

                .progress {
                    width: 100%;
                    height: 4px;
                    background: #e9e3dc;
                }

                .progress-bar {
                    width: 100%;
                    height: 100%;
                    background: var(--red);
                }

                .account-note {
                    margin-bottom: 30px;
                    color: var(--muted);
                    font-size: 12px;
                    line-height: 1.7;
                }

                .actions {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                }

                .button {
                    min-height: 45px;
                    padding: 0 22px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    font-weight: 600;
                    text-decoration: none;
                }

                .primary {
                    border: 1px solid var(--red);
                    background: var(--red);
                    color: var(--white);
                }

                .primary:hover {
                    border-color: var(--red-dark);
                    background: var(--red-dark);
                }

                .secondary {
                    border: 1px solid var(--border);
                    background: var(--cream-light);
                    color: var(--charcoal);
                }

                .secondary:hover {
                    border-color: var(--charcoal);
                }

                @media (max-width: 500px) {

                    body {
                        padding: 25px;
                    }

                    .brand {
                        margin-bottom: 40px;
                    }

                    .actions {
                        flex-direction: column;
                    }

                    .button {
                        width: 100%;
                    }

                }

            </style>

        </head>

        <body>

            <main class="container">

                <div class="brand">
                    Digit<span>alia</span>
                </div>

                <div class="eyebrow">
                    Guest Limit
                </div>

                <h1>
                    You've reached<br>
                    your limit.
                </h1>

                <p class="description">
                    You've used all five downloads available
                    in Guest Mode for this session. Create a
                    Digitalia account to continue with a larger
                    download allowance.
                </p>

                <div class="usage-card">

                    <div class="usage-label">
                        Guest Downloads
                    </div>

                    <div class="usage-number">
                        <?php echo $_SESSION['guest_downloads']; ?>
                        <span>/ 5</span>
                    </div>

                    <div class="progress">

                        <div class="progress-bar"></div>

                    </div>

                </div>

                <p class="account-note">
                    Registered users can download up to 10 books
                    and save books to their personal library.
                </p>

                <div class="actions">

                    <a
                        href="register.php"
                        class="button primary"
                    >
                        Create Account
                    </a>

                    <a
                        href="books.php"
                        class="button secondary"
                    >
                        Browse Books
                    </a>

                </div>

            </main>

        </body>

        </html>

        <?php

        exit;
    }

}

/*
 * Record the download.
 */

$insert = $pdo->prepare(
    "INSERT INTO downloads (user_id, book_id, download_type)
     VALUES (?, ?, ?)"
);

$insert->execute([
    $user_id,
    $book_id,
    $download_type
]);

/*
 * Increase guest session download counter.
 */

if ($download_type === 'guest') {

    $_SESSION['guest_downloads']++;

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

    <title>Download Recorded - Digitalia</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: var(--cream-light);
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
        }

        .container {
            width: 100%;
            max-width: 680px;
        }

        .brand {
            margin-bottom: 55px;
            text-align: center;
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
        }

        .brand span {
            color: var(--red);
        }

        .card {
            padding: 45px;
            border: 1px solid var(--border);
            background: var(--white);
        }

        .eyebrow {
            margin-bottom: 18px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        h1 {
            margin-bottom: 18px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(52px, 8vw, 75px);
            font-weight: 600;
            line-height: 0.9;
        }

        .intro {
            margin-bottom: 35px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .book-title {
            color: var(--charcoal);
            font-weight: 700;
        }

        .details {
            margin-bottom: 30px;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .detail-row {
            min-height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid var(--border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .detail-value {
            color: var(--charcoal);
            font-size: 12px;
            font-weight: 600;
            text-align: right;
        }

        .usage {
            margin-bottom: 30px;
            padding: 22px;
            background: var(--cream);
        }

        .usage-label {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .usage-number {
            margin-bottom: 15px;
            font-family: "Cormorant Garamond", serif;
            font-size: 39px;
            font-weight: 600;
        }

        .usage-number span {
            color: #aaa29c;
            font-size: 24px;
        }

        .progress {
            width: 100%;
            height: 4px;
            background: #e4ddd5;
        }

        .progress-bar {
            height: 100%;
            background: var(--red);
        }

        .file-note {
            margin-bottom: 30px;
            padding: 18px 20px;
            border-left: 2px solid var(--red);
            background: var(--cream-light);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .file-button {
            width: 100%;
            min-height: 48px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .file-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        .back-button {
            width: 100%;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            background: var(--cream-light);
            color: var(--charcoal);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .back-button:hover {
            border-color: var(--charcoal);
        }

        @media (max-width: 550px) {

            body {
                padding: 20px;
            }

            .brand {
                margin-bottom: 35px;
            }

            .card {
                padding: 30px 24px;
            }

            .detail-row {
                align-items: flex-start;
                flex-direction: column;
                justify-content: center;
                gap: 5px;
                padding: 13px 0;
            }

            .detail-value {
                text-align: left;
            }

        }

    </style>

</head>

<body>

    <main class="container">

        <div class="brand">
            Digit<span>alia</span>
        </div>

        <div class="card">

            <div class="eyebrow">
                Download Recorded
            </div>

            <h1>
                You're all set.
            </h1>

            <p class="intro">
                Your download request for
                <span class="book-title">
                    <?php echo htmlspecialchars($book['title']); ?>
                </span>
                has been successfully recorded.
            </p>


            <div class="details">

                <div class="detail-row">

                    <span class="detail-label">
                        Download Type
                    </span>

                    <span class="detail-value">
                        <?php
                        echo $download_type === 'guest'
                            ? 'Guest'
                            : 'Registered User';
                        ?>
                    </span>

                </div>

            </div>


            <?php if ($download_type === 'guest'): ?>

                <div class="usage">

                    <div class="usage-label">
                        Guest Downloads
                    </div>

                    <div class="usage-number">

                        <?php echo $_SESSION['guest_downloads']; ?>

                        <span>/ 5</span>

                    </div>

                    <div class="progress">

                        <div
                            class="progress-bar"
                            style="width: <?php echo min(100, ($_SESSION['guest_downloads'] / 5) * 100); ?>%;"
                        ></div>

                    </div>

                </div>

            <?php else: ?>

                <?php

                $updatedCountStmt = $pdo->prepare(
                    "SELECT COUNT(*)
                     FROM downloads
                     WHERE user_id = ?
                     AND download_type = 'registered'"
                );

                $updatedCountStmt->execute([$user_id]);

                $updated_download_count = (int) $updatedCountStmt->fetchColumn();

                ?>

                <div class="usage">

                    <div class="usage-label">
                        Account Downloads
                    </div>

                    <div class="usage-number">

                        <?php echo $updated_download_count; ?>

                        <span>/ 10</span>

                    </div>

                    <div class="progress">

                        <div
                            class="progress-bar"
                            style="width: <?php echo min(100, ($updated_download_count / 10) * 100); ?>%;"
                        ></div>

                    </div>

                </div>

            <?php endif; ?>


            <?php if (!empty($book['file_url']) && $book['file_url'] !== '#'): ?>

                <a
                    href="<?php echo htmlspecialchars($book['file_url']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="file-button"
                >
                    Access Book File
                </a>

            <?php else: ?>

                <div class="file-note">
                    The book file will be available once a legally
                    authorized download link is added.
                </div>

            <?php endif; ?>


            <a
                href="books.php"
                class="back-button"
            >
                Back to Book Catalog
            </a>

        </div>

    </main>

</body>

</html>