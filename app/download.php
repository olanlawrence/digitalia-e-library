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

if (isset($_SESSION['user_id'])) {

    $download_type = 'registered';
    $user_id = $_SESSION['user_id'];

}

$insert = $pdo->prepare(
    "INSERT INTO downloads (user_id, book_id, download_type)
     VALUES (?, ?, ?)"
);

$insert->execute([
    $user_id,
    $book_id,
    $download_type
]);

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

    <?php else: ?>

        <p>
            Download type:
            <strong>Registered User</strong>
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