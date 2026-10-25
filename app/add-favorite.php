<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$book_id = isset($_GET['book_id'])
    ? (int) $_GET['book_id']
    : 0;

if ($book_id <= 0) {
    die("Invalid book.");
}

/*
 * Check if the book exists.
 */

$bookStmt = $pdo->prepare(
    "SELECT id
     FROM books
     WHERE id = ?"
);

$bookStmt->execute([$book_id]);

$book = $bookStmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Book not found.");
}

/*
 * Check if the book is already in the user's library.
 */

$checkStmt = $pdo->prepare(
    "SELECT id
     FROM favorites
     WHERE user_id = ?
     AND book_id = ?"
);

$checkStmt->execute([
    $user_id,
    $book_id
]);

$existingFavorite = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$existingFavorite) {

    /*
     * Add the book to My Library.
     */

    $insertStmt = $pdo->prepare(
        "INSERT INTO favorites (user_id, book_id)
         VALUES (?, ?)"
    );

    $insertStmt->execute([
        $user_id,
        $book_id
    ]);

    header("Location: my-library.php");
    exit;
}

/*
 * Book is already saved.
 */

header("Location: my-library.php");
exit;