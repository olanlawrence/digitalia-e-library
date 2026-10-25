<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT
        favorites.id AS favorite_id,
        books.id AS book_id,
        books.title,
        books.author,
        books.category,
        books.description
     FROM favorites
     INNER JOIN books
        ON favorites.book_id = books.id
     WHERE favorites.user_id = ?
     ORDER BY favorites.created_at DESC"
);

$stmt->execute([$user_id]);

$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Library - Digitalia</title>

</head>

<body>

    <h1>My Library</h1>

    <p>
        Books you have saved to your Digitalia library.
    </p>

    <hr>

    <?php if (count($favorites) > 0): ?>

        <p>
            <?php echo count($favorites); ?> saved book(s).
        </p>

        <?php foreach ($favorites as $book): ?>

            <div>

                <h2>
                    <?php echo htmlspecialchars($book['title']); ?>
                </h2>

                <p>
                    <strong>Author:</strong>
                    <?php echo htmlspecialchars($book['author']); ?>
                </p>

                <p>
                    <strong>Category:</strong>
                    <?php echo htmlspecialchars($book['category']); ?>
                </p>

                <p>
                    <?php echo htmlspecialchars($book['description']); ?>
                </p>

                <p>

                    <a
                        href="download.php?book_id=<?php echo $book['book_id']; ?>"
                    >
                        Download Book
                    </a>

                    |

                    <a
                        href="remove-favorite.php?id=<?php echo $book['favorite_id']; ?>"
                    >
                        Remove from My Library
                    </a>

                </p>

            </div>

            <hr>

        <?php endforeach; ?>

    <?php else: ?>

        <h2>Your library is empty.</h2>

        <p>
            You have not saved any books yet.
        </p>

        <p>
            <a href="books.php">
                Browse Books
            </a>
        </p>

    <?php endif; ?>

    <p>
        <a href="dashboard.php">
            Back to Dashboard
        </a>
    </p>

</body>

</html>