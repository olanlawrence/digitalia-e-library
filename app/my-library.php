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

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

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

        /* HEADER */

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

        /* COLLECTION HEADER */

        .collection-header {
            padding: 50px 0 25px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .collection-header h2 {
            font-family: "Cormorant Garamond", serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 1;
        }

        .collection-count {
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 28px;
        }

        .download-button,
        .remove-button {
            min-height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 2px;
            font-size: 10px;
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

        .remove-button {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--muted);
        }

        .remove-button:hover {
            border-color: var(--red);
            color: var(--red);
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 90px 30px;
            border: 1px solid var(--border);
            background: var(--white);
            text-align: center;
        }

        .empty-label {
            margin-bottom: 15px;
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .empty-state h2 {
            margin-bottom: 12px;
            font-family: "Cormorant Garamond", serif;
            font-size: 46px;
            font-weight: 600;
        }

        .empty-state p {
            max-width: 430px;
            margin: 0 auto 28px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .browse-button {
            min-height: 45px;
            padding: 0 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--red);
            background: var(--red);
            color: var(--white);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
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

        @media (max-width: 1000px) {

            .book-grid {
                grid-template-columns: repeat(2, 1fr);
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

            .collection-header {
                display: block;
            }

            .collection-count {
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

            .book-card {
                min-height: 370px;
                padding: 25px;
            }

            .book-actions {
                grid-template-columns: 1fr;
            }

            .empty-state {
                padding: 70px 20px;
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
            href="dashboard.php"
            class="nav-link"
        >
            Dashboard
        </a>

        <a
            href="books.php"
            class="nav-link"
        >
            Browse Books
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

    <section class="page-header">

        <div>

            <div class="eyebrow">
                Personal Collection
            </div>

            <h1>
                My
                <span>Library.</span>
            </h1>

        </div>

        <p class="page-description">
            Your saved books, gathered in one personal
            collection. Return whenever you are ready
            to continue reading.
        </p>

    </section>


    <?php if (count($favorites) > 0): ?>

        <section>

            <div class="collection-header">

                <h2>
                    Saved Books
                </h2>

                <div class="collection-count">

                    <?php echo count($favorites); ?>

                    <?php echo count($favorites) === 1 ? 'book' : 'books'; ?>

                    in your library

                </div>

            </div>


            <div class="book-grid">

                <?php foreach ($favorites as $index => $book): ?>

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
                                href="download.php?book_id=<?php echo $book['book_id']; ?>"
                                class="download-button"
                            >
                                Download
                            </a>

                            <a
                                href="remove-favorite.php?id=<?php echo $book['favorite_id']; ?>"
                                class="remove-button"
                            >
                                Remove
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    <?php else: ?>

        <section class="empty-state">

            <div class="empty-label">
                Your collection
            </div>

            <h2>
                Nothing saved yet.
            </h2>

            <p>
                Start exploring the Digitalia collection
                and save the books you want to keep in
                your personal library.
            </p>

            <a
                href="books.php"
                class="browse-button"
            >
                Browse Books
            </a>

        </section>

    <?php endif; ?>


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