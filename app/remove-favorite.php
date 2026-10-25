<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$favorite_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($favorite_id <= 0) {
    die("Invalid favorite.");
}

/*
 * Check if the favorite belongs to the logged-in user.
 */

$checkStmt = $pdo->prepare(
    "SELECT id
     FROM favorites
     WHERE id = ?
     AND user_id = ?"
);

$checkStmt->execute([
    $favorite_id,
    $user_id
]);

$favorite = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$favorite) {
    die("Favorite not found.");
}

/*
 * Remove the book from My Library.
 */

$deleteStmt = $pdo->prepare(
    "DELETE FROM favorites
     WHERE id = ?
     AND user_id = ?"
);

$deleteStmt->execute([
    $favorite_id,
    $user_id
]);

header("Location: my-library.php");
exit;