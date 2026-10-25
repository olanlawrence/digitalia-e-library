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

    /*
     * Check registered user's total downloads.
     */

    $countStmt = $pdo->prepare(
        "SELECT COUNT(*)
         FROM downloads
         WHERE user_id = ?
         AND download_type = 'registered'"
    );

    $countStmt->execute([$user_id]);

    $download_count = (int) $countStmt->fetchColumn();

    /*
     * Registered users are limited to 10 downloads.
     */

    if ($download_count >= 10) {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Download Limit Reached - Digitalia</title>
        </head>

        <body>

            <h1>Download Limit Reached</h1>

            <p>
                You have reached the maximum of
                <strong>10 downloads</strong>
                allowed for your account.
            </p>

            <p>
                You have used:
                <strong><?php echo $download_count; ?> / 10</strong>
                downloads.
            </p>

            <p>
                <a href="books.php">
                    Back to Book Catalog
                </a>
            </p>

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

    /*
     * Initialize guest session if needed.
     */

    if (!isset($_SESSION['guest'])) {
        $_SESSION['guest'] = true;
    }

    if (!isset($_SESSION['guest_downloads'])) {
        $_SESSION['guest_downloads'] = 0;
    }

    /*
     * Check guest download limit.
     */

    if ($_SESSION['guest_downloads'] >= 5) {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Download Limit Reached - Digitalia</title>
        </head>

        <body>

            <h1>Download Limit Reached</h1>

            <p>
                You have reached the maximum of
                <strong>5 guest downloads</strong>
                for this session.
            </p>

            <p>
                You have used:
                <strong><?php echo $_SESSION['guest_downloads']; ?> / 5</strong>
                downloads.
            </p>

            <p>
                Create an account to access the registered-user download limit.
            </p>

            <p>
                <a href="register.php">
                    Create an Account
                </a>
            </p>

            <p>
                <a href="books.php">
                    Back to Book Catalog
                </a>
            </p>

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Download - Digitalia</title>
</head>

<body>

    <h1>Download Recorded</h1>

    <p>
        Your download request for
        <strong>
            <?php echo htmlspecialchars($book['title']); ?>
        </strong>
        has been recorded.
    </p>

    <?php if ($download_type === 'guest'): ?>

        <p>
            Download type:
            <strong>Guest</strong>
        </p>

        <p>
            Guest downloads used:
            <strong>
                <?php echo $_SESSION['guest_downloads']; ?> / 5
            </strong>
        </p>

    <?php else: ?>

        <p>
            Download type:
            <strong>Registered User</strong>
        </p>

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

        <p>
            Account downloads used:
            <strong>
                <?php echo $updated_download_count; ?> / 10
            </strong>
        </p>

    <?php endif; ?>

    <?php if (!empty($book['file_url']) && $book['file_url'] !== '#'): ?>

        <p>
            <a
                href="<?php echo htmlspecialchars($book['file_url']); ?>"
                target="_blank"
            >
                Access Book File
            </a>
        </p>

    <?php else: ?>

        <p>
            The book file will be available once a legally authorized
            download link is added.
        </p>

    <?php endif; ?>

    <p>
        <a href="books.php">
            Back to Book Catalog
        </a>
    </p>

</body>

</html>