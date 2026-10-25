<?php

session_start();

if (!isset($_SESSION['guest'])) {
    $_SESSION['guest'] = true;
    $_SESSION['guest_downloads'] = 0;
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

    <title>Guest Mode - Digitalia</title>

</head>

<body>

    <h1>Welcome to Digitalia!</h1>

    <p>
        You are browsing Digitalia as a guest.
    </p>

    <p>
        You can explore the library without creating an account.
    </p>

    <p>
        Guest downloads used:
        <strong>
            <?php echo $_SESSION['guest_downloads']; ?> / 5
        </strong>
    </p>

    <hr>

    <h2>Digital Library</h2>

    <p>
        Browse the available books in the Digitalia library.
    </p>

    <p>
        <a href="books.php">
            Browse Books
        </a>
    </p>

    <hr>

    <p>
        Want to save books to your personal library?
    </p>

    <p>
        <a href="register.php">
            Create an Account
        </a>
    </p>

    <p>
        Already have an account?
        <a href="login.php">
            Log In
        </a>
    </p>

    <p>
        <a href="index.php">
            Back to Home
        </a>
    </p>

</body>

</html>