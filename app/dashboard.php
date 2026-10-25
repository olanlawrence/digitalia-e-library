<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Digitalia</title>

</head>

<body>

    <h1>Digitalia Dashboard</h1>

    <h2>
        Hello, <?php echo htmlspecialchars($full_name); ?>!
    </h2>

    <p>
        Welcome to your Digitalia digital library dashboard.
    </p>

    <hr>

    <h3>Account Information</h3>

    <p>
        <strong>Name:</strong>
        <?php echo htmlspecialchars($full_name); ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?php echo htmlspecialchars($email); ?>
    </p>

    <p>
        <strong>Role:</strong>
        <?php echo htmlspecialchars($role); ?>
    </p>

    <hr>

    <h3>Digitalia Library</h3>

    <p>
        <a href="books.php">
            Browse Books
        </a>
    </p>

    <p>
        <a href="my-library.php">
            My Library
        </a>
    </p>

    <p>
        AI Assistant will be available here in a later phase.
    </p>

    <hr>

    <p>
        <a href="logout.php">
            Log Out
        </a>
    </p>

</body>

</html>