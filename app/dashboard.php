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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Digitalia</title>
</head>

<body>

    <h1>Welcome to Digitalia!</h1>

    <h2>Hello, <?php echo htmlspecialchars($full_name); ?>!</h2>

    <p>
        You are currently logged in to your Digitalia account.
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

    <h3>Digitalia</h3>

    <p>Book catalog will be available here.</p>
    <p>My Library will be available here.</p>
    <p>AI Assistant will be available here.</p>

    <br>

    <a href="logout.php">Log Out</a>

</body>

</html>