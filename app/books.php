<?php

require_once __DIR__ . '/config/database.php';

$stmt = $pdo->query(
    "SELECT id, title, author, category, description, file_url
     FROM books
     ORDER BY id ASC"
);

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Books - Digitalia</title>
</head>

<body>

    <h1>Digitalia Book Catalog</h1>

    <p>
        Explore the available books in the Digitalia library.
    </p>

    <hr>

    <?php if (count($books) > 0): ?>

        <?php foreach ($books as $book): ?>

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

                <?php if (!empty($book['file_url']) && $book['file_url'] !== '#'): ?>

                    <p>
                        <a
                            href="<?php echo htmlspecialchars($book['file_url']); ?>"
                            target="_blank"
                        >
                            Access Book
                        </a>
                    </p>

                <?php else: ?>

                    <p>
                        Book access link will be available soon.
                    </p>

                <?php endif; ?>

            </div>

            <hr>

        <?php endforeach; ?>

    <?php else: ?>

        <p>
            No books are currently available.
        </p>

    <?php endif; ?>

    <p>
        <a href="index.php">Back to Home</a>
    </p>

</body>

</html>