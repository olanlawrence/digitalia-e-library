<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digitalia - E-Library</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        header {
            background: #ffffff;
            padding: 20px 60px;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            max-width: 700px;
        }

        h1 {
            font-size: 52px;
            margin-bottom: 20px;
        }

        .highlight {
            color: #2563eb;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .button {
            display: inline-block;
            padding: 13px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .primary {
            background: #2563eb;
            color: white;
        }

        .secondary {
            background: white;
            color: #2563eb;
            border: 1px solid #2563eb;
        }
    </style>
</head>

<body>

<header>
    <div class="logo">Digitalia</div>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to <span class="highlight">Digitalia</span></h1>

        <p>
            An integrated digital library for discovering, exploring,
            and accessing books through web and Android platforms.
        </p>

        <div class="buttons">
            <a href="login.php" class="button primary">Log In</a>
            <a href="register.php" class="button secondary">Register</a>
        </div>
    </div>
</section>

</body>
</html>