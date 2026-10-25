<?php

require_once __DIR__ . '/config/database.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "
    SELECT id, title, author, category, description, file_url
    FROM books
    WHERE 1=1
";

$params = [];

if ($search !== '') {
    $sql .= "
        AND (
            title LIKE ?
            OR author LIKE ?
        )
    ";

    $searchTerm = '%' . $search . '%';

    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryStmt = $pdo->query(
    "SELECT DISTINCT category
     FROM books
     WHERE category IS NOT NULL
     AND category != ''
     ORDER BY category ASC"
);

$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

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

    <h2>Search and Filter</h2>

    <form method="GET">

        <div>

            <label for="search">
                Search by title or author:
            </label>

            <input
                type="text"
                id="search"
                name="search"
                placeholder="Enter title or author"
                value="<?php echo htmlspecialchars($search); ?>"
            >

        </div>

        <br>

        <div>

            <label for="category">
                Category:
            </label>

            <select
                id="category"
                name="category"
            >

                <option value="">
                    All Categories
                </option>

                <?php foreach ($categories as $bookCategory): ?>

                    <option
                        value="<?php echo htmlspecialchars($bookCategory); ?>"
                        <?php echo ($category === $bookCategory) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($bookCategory); ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <br>

        <button type="submit">
            Search
        </button>

        <a href="books.php">
            Clear Filters
        </a>

    </form>

    <hr>

    <h2>Available Books</h2>

    <?php if (count($books) > 0): ?>

        <p>
            <?php echo count($books); ?> book(s) found.
        </p>

        <?php foreach ($books as $book): ?>

            <div>

                <h3>
                    <?php echo htmlspecialchars($book['title']); ?>
                </h3>

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
            No books matched your search or filter.
        </p>

    <?php endif; ?>

    <p>
        <a href="index.php">
            Back to Home
        </a>
    </p>

</body>

</html>