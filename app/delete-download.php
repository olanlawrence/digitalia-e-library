
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config/database.php';

/*
 * Allow POST requests only.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my-downloads.php");
    exit;
}

/*
 * Validate CSRF token.
 */

$submittedToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['download_csrf_token'] ?? '';

if (
    empty($submittedToken) ||
    empty($sessionToken) ||
    !hash_equals($sessionToken, $submittedToken)
) {
    http_response_code(403);
    exit("Invalid request. Please return to My Downloads and try again.");
}

/*
 * Validate download record ID.
 */

$download_id = filter_input(
    INPUT_POST,
    'download_id',
    FILTER_VALIDATE_INT
);

if (!$download_id || $download_id <= 0) {
    header("Location: my-downloads.php");
    exit;
}

/*
 * Delete only the selected download record
 * belonging to the currently logged-in user.
 */

$deleteStmt = $pdo->prepare(
    "DELETE FROM downloads
     WHERE id = ?
     AND user_id = ?
     AND download_type = 'registered'"
);

$deleteStmt->execute([
    $download_id,
    $_SESSION['user_id']
]);

/*
 * Return to My Downloads.
 */

header("Location: my-downloads.php");
exit;

