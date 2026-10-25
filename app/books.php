<?php

session_start();

require_once __DIR__ . '/config/database.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

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

/*
 * Get the books already saved by the logged-in user.
 */

$savedBooks = [];

if ($isLoggedIn) {

    $favoriteStmt = $pdo->prepare(
        "SELECT book_id
         FROM favorites
         WHERE user_id = ?"
    );

    $favoriteStmt->execute([$user_id]);

    $savedBooks = $favoriteStmt->fetchAll(PDO::FETCH_COLUMN);
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

    <title>Books - Digitalia</title>

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
            gap: 28px;
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

        /* PAGE HEADER */

        .page-header {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 60px;
            align-items: end;
            padding-bottom: 55px;
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
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(62px, 7vw, 94px);
            font-weight: 600;
            line-height: 0.86;
            letter-spacing: -2px;
        }

        .page-header h1 span {
            color: var(--red);
        }

        .page-description {
            max-width: 390px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        /* SEARCH */

        .search-section {
            padding: 42px 0 55px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 210px auto auto;
            gap: 10px;
        }

        .input-wrap {
            position: relative;
        }

        .search-input,
        .category-select {
            width: 100%;
            height: 50px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 2px;
            outline: none;
            background: var(--white);
            color: var(--charcoal);
            font-family: "DM Sans", sans-serif;
            font-size: 13px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .search-input::placeholder {
            color: #9c9690;
        }

        .search-input:focus,
        .category-select:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(155, 61, 74, 0.08);
        }

        .search-button {
            height: 50px;
            padding: 0 24px;
            border: 1px solid var(--red);
            border-radius: 2px;
            background: var(--red);
            color: var(--white);
            font-family: "DM Sans", sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .search-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        .clear-button {
            height: 50px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 2px;
            background: transparent;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition:
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .clear-button:hover {
            border-color: var(--charcoal);
            color: var(--charcoal);
        }

        /* RESULTS HEADER */

        .results-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .results-header h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 1;
        }

        .results-count {
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        /* BOOK GRID */

        .book-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .book-card {
            min-height: 400px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
            background: var(--white);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .book-card:hover {
            transform: translateY(-3px);
            border-color: rgba(155, 61, 74, 0.35);
            box-shadow: 0 15px 35px rgba(50, 40, 35, 0.07);
        }

        .book-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 38px;
        }

        .book-number {
            color: #a9a19a;
            font-family: "Cormorant Garamond", serif;
            font-size: 22px;
        }

        .book-category {
            padding: 6px 9px;
            border: 1px solid rgba(155, 61, 74, 0.2);
            color: var(--red);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .book-info {
            flex: 1;
        }

        .book-title {
            margin-bottom: 9px;
            font-family: "Cormorant Garamond", serif;
            font-size: 34px;
            font-weight: 600;
            line-height: 0.98;
        }

        .book-author {
            margin-bottom: 22px;
            color: var(--red);
            font-size: 12px;
            font-weight: 600;
        }

        .book-description {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .book-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 28px;
        }

        .download-button,
        .save-button,
        .saved-button {
            min-height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            border-radius: 2px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .download-button {
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
        }

        .download-button:hover {
            border-color: var(--red-dark);
            background: var(--red-dark);
        }

        .save-button {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--charcoal);
        }

        .save-button:hover {
            border-color: var(--red);
            color: var(--red);
        }

        .saved-button {
            border: 1px solid rgba(83, 102, 75, 0.25);
            background: #f1f4ee;
            color: #53664b;
            cursor: default;
        }

        .login-save {
            padding: 12px 0 0;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }

        .login-save a {
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
        }

        .login-save a:hover {
            text-decoration: underline;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 80px 30px;
            border: 1px solid var(--border);
            background: var(--white);
            text-align: center;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            font-family: "Cormorant Garamond", serif;
            font-size: 38px;
            font-weight: 600;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
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

        @media (max-width: 1000px) {

            .book-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-form {
                grid-template-columns: 1fr 1fr;
            }

        }

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
                grid-template-columns: 1fr;
                gap: 30px;
            }

        }

        @media (max-width: 650px) {

            .book-grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .results-header {
                display: block;
            }

            .results-count {
                display: block;
                margin-top: 8px;
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
                font-size: 60px;
            }

            .search-section {
                padding: 35px 0 45px;
            }

            .book-card {
                min-height: 370px;
                padding: 25px;
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
        href="<?php echo $isLoggedIn ? 'dashboard.php' : 'index.php'; ?>"
        class="brand"
    >
        Digit<span>alia</span>
    </a>

    <div class="nav-right">

        <?php if ($isLoggedIn): ?>

            <a
                href="dashboard.php"
                class="nav-link"
            >
                Dashboard
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

        <?php else: ?>

            <a
                href="login.php"
                class="nav-link"
            >
                Log In
            </a>

            <a
                href="register.php"
                class="logout-link"
            >
                Create Account
            </a>

        <?php endif; ?>

    </div>

</nav>


<main class="main">

    <section class="page-header">

        <div>

            <div class="eyebrow">
                Digitalia Collection
            </div>

            <h1>
                Discover
                <span>something.</span>
            </h1>

        </div>

        <p class="page-description">
            Explore the Digitalia collection by title,
            author, or category. Find books to read,
            download, and keep in your personal library.
        </p>

    </section>


    <section class="search-section">

        <form
            method="GET"
            class="search-form"
        >

            <div class="input-wrap">

                <input
                    type="text"
                    id="search"
                    name="search"
                    class="search-input"
                    placeholder="Search by title or author..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

            </div>


            <select
                id="category"
                name="category"
                class="category-select"
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


            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>


            <a
                href="books.php"
                class="clear-button"
            >
                Clear
            </a>

        </form>

    </section>


    <section>

        <div class="results-header">

            <h2>
                Available Books
            </h2>

            <div class="results-count">

                <?php echo count($books); ?>

                <?php echo count($books) === 1 ? 'book' : 'books'; ?>

                found

            </div>

        </div>


        <?php if (count($books) > 0): ?>

            <div class="book-grid">

                <?php foreach ($books as $index => $book): ?>

                    <article class="book-card">

                        <div class="book-top">

                            <div class="book-number">

                                <?php
                                echo str_pad(
                                    $index + 1,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                );
                                ?>

                            </div>

                            <div class="book-category">

                                <?php
                                echo htmlspecialchars(
                                    $book['category']
                                );
                                ?>

                            </div>

                        </div>


                        <div class="book-info">

                            <h3 class="book-title">

                                <?php
                                echo htmlspecialchars(
                                    $book['title']
                                );
                                ?>

                            </h3>


                            <div class="book-author">

                                <?php
                                echo htmlspecialchars(
                                    $book['author']
                                );
                                ?>

                            </div>


                            <p class="book-description">

                                <?php
                                echo htmlspecialchars(
                                    $book['description']
                                );
                                ?>

                            </p>

                        </div>


                        <div class="book-actions">

                            <a
                                href="download.php?book_id=<?php echo $book['id']; ?>"
                                class="download-button"
                            >
                                Download Book
                            </a>


                            <?php if ($isLoggedIn): ?>

                                <?php if (in_array($book['id'], $savedBooks)): ?>

                                    <div class="saved-button">
                                        Already in My Library
                                    </div>

                                <?php else: ?>

                                    <a
                                        href="add-favorite.php?book_id=<?php echo $book['id']; ?>"
                                        class="save-button"
                                    >
                                        Save to My Library
                                    </a>

                                <?php endif; ?>

                            <?php else: ?>

                                <div class="login-save">

                                    <a href="login.php">
                                        Log in
                                    </a>

                                    to save this book

                                </div>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <h3>
                    No books found.
                </h3>

                <p>
                    Try adjusting your search or selecting
                    a different category.
                </p>

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