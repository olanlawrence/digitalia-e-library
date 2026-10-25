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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        <?php echo $_SESSION['guest_downloads']; ?> / 5
    </p>

    <hr>

    <h3>Digital Library</h3>

    <p>
        Book catalog will appear here.
    </p>

    <br>

    <a href="login.php">Log In</a>

    |

    <a href="register.php">Create an Account</a>

</body>

</html>