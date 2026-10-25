<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Digitalia - E-Library</title>

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
        }

        body {
            font-family: "DM Sans", sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            min-height: 100vh;
        }

        header {
            width: 100%;
            padding: 28px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(111, 39, 51, 0.12);
        }

        .logo {
            color: var(--charcoal);
            font-family: "Cormorant Garamond", serif;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-decoration: none;
        }

        .header-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero {
            min-height: calc(100vh - 90px);
            display: flex;
            align-items: center;
            padding: 70px 7%;
        }

        .hero-content {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            align-items: center;
            gap: 80px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            color: var(--red);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .eyebrow-line {
            width: 38px;
            height: 1px;
            background: var(--red);
        }

        h1 {
            max-width: 700px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(64px, 7vw, 104px);
            font-weight: 600;
            line-height: 0.9;
            letter-spacing: -2px;
            color: var(--charcoal);
            margin-bottom: 30px;
        }

        .highlight {
            color: var(--red);
        }

        .description {
            max-width: 590px;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 0 25px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition:
                transform 0.2s ease,
                background 0.2s ease,
                color 0.2s ease,
                border-color 0.2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .primary {
            background: var(--red);
            color: #ffffff;
            border: 1px solid var(--red);
        }

        .primary:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        .secondary {
            background: transparent;
            color: var(--charcoal);
            border: 1px solid var(--border);
        }

        .secondary:hover {
            background: var(--cream-light);
            border-color: var(--red);
            color: var(--red);
        }

        .guest {
            color: var(--muted);
            border: 1px solid transparent;
        }

        .guest:hover {
            color: var(--red);
            background: rgba(255, 255, 255, 0.4);
        }

        .hero-visual {
            position: relative;
            min-height: 480px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .book-frame {
            position: relative;
            width: 290px;
            height: 390px;
            background: var(--red);
            padding: 16px;
            transform: rotate(3deg);
            box-shadow:
                0 30px 60px rgba(37, 35, 38, 0.14),
                0 8px 20px rgba(111, 39, 51, 0.12);
        }

        .book-cover {
            width: 100%;
            height: 100%;
            background: var(--cream-light);
            border: 1px solid rgba(111, 39, 51, 0.18);
            padding: 34px 27px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-top {
            color: var(--red);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .book-title {
            font-family: "Cormorant Garamond", serif;
            font-size: 43px;
            font-weight: 600;
            line-height: 0.95;
            color: var(--charcoal);
        }

        .book-bottom {
            padding-top: 18px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .circle-detail {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 1px solid rgba(155, 61, 74, 0.25);
            border-radius: 50%;
            top: 30px;
            right: 25px;
        }

        .circle-detail::after {
            content: "";
            position: absolute;
            width: 70px;
            height: 70px;
            border: 1px solid rgba(155, 61, 74, 0.2);
            border-radius: 50%;
            top: 24px;
            left: 24px;
        }

        .small-note {
            position: absolute;
            bottom: 25px;
            left: 0;
            padding: 14px 18px;
            background: rgba(252, 250, 246, 0.92);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .footer-note {
            margin-top: 35px;
            color: #8b837d;
            font-size: 11px;
            letter-spacing: 0.8px;
        }

        @media (max-width: 900px) {

            header {
                padding: 24px 6%;
            }

            .hero {
                padding: 60px 6%;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .hero-visual {
                min-height: 400px;
            }

            h1 {
                font-size: clamp(60px, 14vw, 90px);
            }

        }

        @media (max-width: 600px) {

            header {
                padding: 20px 5%;
            }

            .logo {
                font-size: 26px;
            }

            .header-label {
                display: none;
            }

            .hero {
                padding: 50px 5%;
            }

            h1 {
                font-size: 62px;
                letter-spacing: -1px;
            }

            .description {
                font-size: 15px;
                line-height: 1.7;
            }

            .buttons {
                align-items: stretch;
                flex-direction: column;
            }

            .button {
                width: 100%;
            }

            .hero-visual {
                min-height: 360px;
            }

            .book-frame {
                width: 235px;
                height: 320px;
            }

            .book-title {
                font-size: 35px;
            }

            .circle-detail {
                right: 0;
            }

        }

    </style>

</head>

<body>

<header>

    <a
        href="index.php"
        class="logo"
    >
        DIGITALIA
    </a>

    <span class="header-label">
        Digital Library
    </span>

</header>


<main class="hero">

    <div class="hero-content">


        <section>

            <div class="eyebrow">

                <span class="eyebrow-line"></span>

                Your Digital Library

            </div>


            <h1>

                Discover.
                <span class="highlight">
                    Read.
                </span>
                Explore.

            </h1>


            <p class="description">

                A modern digital library designed to make
                discovering, exploring, and accessing books
                simple — wherever you are.

            </p>


            <div class="buttons">

                <a
                    href="login.php"
                    class="button primary"
                >
                    Log In
                </a>


                <a
                    href="register.php"
                    class="button secondary"
                >
                    Create an Account
                </a>


                <a
                    href="guest.php"
                    class="button guest"
                >
                    Continue as Guest
                </a>

            </div>


            <p class="footer-note">
                Available across web and Android platforms.
            </p>

        </section>


        <section class="hero-visual">

            <div class="circle-detail"></div>


            <div class="book-frame">

                <div class="book-cover">

                    <div class="book-top">
                        Digitalia Library
                    </div>


                    <div class="book-title">
                        A world
                        of stories
                        awaits.
                    </div>


                    <div class="book-bottom">
                        Read · Discover · Learn
                    </div>

                </div>

            </div>


            <div class="small-note">
                200+ books to discover
            </div>

        </section>


    </div>

</main>

</body>

</html>