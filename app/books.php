
<?php
session_start();

require_once __DIR__ . '/config/database.php';

// --------------------------------------------------
// USER SESSION
// --------------------------------------------------

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $isLoggedIn ? (int) $_SESSION['user_id'] : null;

$search = trim($_GET['search'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');

// --------------------------------------------------
// IT BOOK PLACEHOLDER COLLECTION
// 10 categories x 20 books = 200 books
// --------------------------------------------------

$bookTopics = [
    'Programming Fundamentals' => [
        'Introduction to Programming',
        'Variables and Data Types',
        'Operators and Expressions',
        'Conditional Statements',
        'Loops and Iteration',
        'Functions and Methods',
        'Arrays and Collections',
        'Object-Oriented Programming',
        'Classes and Objects',
        'Inheritance and Polymorphism',
        'Encapsulation and Abstraction',
        'Exception Handling',
        'File Handling',
        'Algorithms and Problem Solving',
        'Recursion and Backtracking',
        'Data Structures',
        'Debugging Techniques',
        'Programming Logic',
        'Clean Code Principles',
        'Practical Programming Projects'
    ],

    'Web Development' => [
        'Introduction to Web Development',
        'HTML Fundamentals',
        'CSS Fundamentals',
        'JavaScript Essentials',
        'Responsive Web Design',
        'Frontend Development',
        'Backend Development',
        'PHP Programming',
        'Modern Web Frameworks',
        'Website Layout and Design',
        'Forms and User Input',
        'Web Authentication',
        'REST API Development',
        'Web Application Security',
        'Web Performance Optimization',
        'Browser Technologies',
        'Full-Stack Development',
        'Website Deployment',
        'Web Accessibility',
        'Practical Web Development Projects'
    ],

    'Mobile App Development' => [
        'Introduction to Mobile Development',
        'Android Development Fundamentals',
        'Kotlin Programming',
        'Java for Android',
        'Mobile User Interface Design',
        'Jetpack Compose Fundamentals',
        'Mobile App Navigation',
        'Mobile App Architecture',
        'Mobile Database Integration',
        'Mobile API Integration',
        'Mobile App Authentication',
        'Mobile App Security',
        'Mobile App Testing',
        'Mobile Notifications',
        'Mobile App Performance',
        'Cross-Platform Development',
        'Mobile App Deployment',
        'Android Studio Essentials',
        'Mobile Application Design Patterns',
        'Practical Mobile App Projects'
    ],

    'Database Management' => [
        'Introduction to Database Systems',
        'Relational Database Concepts',
        'SQL Fundamentals',
        'Database Design Principles',
        'Entity Relationship Diagrams',
        'Database Normalization',
        'MySQL Administration',
        'Database Queries and Joins',
        'Database Indexing',
        'Database Transactions',
        'Database Security',
        'Database Backup and Recovery',
        'Stored Procedures and Triggers',
        'Database Optimization',
        'NoSQL Database Concepts',
        'MongoDB Fundamentals',
        'Data Modeling',
        'Database Integration',
        'Database Management Systems',
        'Practical Database Projects'
    ],

    'Computer Networks' => [
        'Introduction to Computer Networks',
        'Network Fundamentals',
        'OSI Model and TCP/IP',
        'IP Addressing and Subnetting',
        'Routing and Switching',
        'Wireless Networking',
        'Network Topologies',
        'Network Devices and Equipment',
        'LAN and WAN Technologies',
        'Network Protocols',
        'DNS and DHCP Services',
        'Network Configuration',
        'Network Troubleshooting',
        'Network Administration',
        'Virtual Private Networks',
        'Network Monitoring',
        'Network Infrastructure',
        'Cloud Networking',
        'Network Performance',
        'Practical Networking Projects'
    ],

    'Cybersecurity' => [
        'Introduction to Cybersecurity',
        'Information Security Fundamentals',
        'Ethical Hacking Concepts',
        'Network Security',
        'Web Application Security',
        'Cryptography Fundamentals',
        'Malware and Threat Analysis',
        'Phishing and Social Engineering',
        'Password Security',
        'Access Control and Authentication',
        'Security Policies and Procedures',
        'Digital Forensics',
        'Incident Response',
        'Vulnerability Assessment',
        'Penetration Testing Concepts',
        'Operating System Security',
        'Data Privacy and Protection',
        'Cloud Security',
        'Cybersecurity Risk Management',
        'Practical Cybersecurity Projects'
    ],

    'Artificial Intelligence' => [
        'Introduction to Artificial Intelligence',
        'AI Fundamentals',
        'Machine Learning Concepts',
        'Supervised Learning',
        'Unsupervised Learning',
        'Deep Learning Fundamentals',
        'Neural Networks',
        'Natural Language Processing',
        'Computer Vision',
        'Generative Artificial Intelligence',
        'Large Language Models',
        'AI Model Training',
        'AI Model Evaluation',
        'Reinforcement Learning',
        'AI Ethics and Responsible AI',
        'Intelligent Systems',
        'AI Applications in Business',
        'AI Development Tools',
        'Prompt Engineering',
        'Practical Artificial Intelligence Projects'
    ],

    'Data Science and Analytics' => [
        'Introduction to Data Science',
        'Data Analysis Fundamentals',
        'Python for Data Science',
        'Statistics for Computing',
        'Data Collection Techniques',
        'Data Cleaning and Preparation',
        'Data Visualization',
        'Exploratory Data Analysis',
        'Data Mining Concepts',
        'Predictive Analytics',
        'Data Interpretation',
        'Big Data Fundamentals',
        'Data Warehousing',
        'Business Intelligence',
        'Excel for Data Analysis',
        'Pandas and NumPy',
        'Data Reporting',
        'Data-Driven Decision Making',
        'Data Science Workflows',
        'Practical Data Science Projects'
    ],

    'Software Engineering' => [
        'Introduction to Software Engineering',
        'Software Development Life Cycle',
        'Agile Software Development',
        'Requirements Engineering',
        'System Analysis and Design',
        'Software Architecture',
        'Software Design Patterns',
        'Software Testing Fundamentals',
        'Quality Assurance',
        'Version Control with Git',
        'Software Documentation',
        'Project Management for IT',
        'Software Maintenance',
        'Code Review Practices',
        'Continuous Integration',
        'Continuous Deployment',
        'Software Requirements Specification',
        'User Experience Engineering',
        'Software Quality Management',
        'Practical Software Engineering Projects'
    ],

    'Operating Systems and Cloud Computing' => [
        'Introduction to Operating Systems',
        'Windows Operating System',
        'Linux Fundamentals',
        'Linux Command Line',
        'Process Management',
        'Memory Management',
        'File Systems',
        'Operating System Security',
        'Virtualization Fundamentals',
        'Cloud Computing Concepts',
        'Cloud Service Models',
        'Cloud Infrastructure',
        'Cloud Storage Systems',
        'Cloud Database Services',
        'Cloud Deployment',
        'Containerization Fundamentals',
        'Docker Essentials',
        'DevOps Fundamentals',
        'Server Administration',
        'Practical Cloud Computing Projects'
    ]
];

// Generate 200 unique placeholder books.

$placeholderBooks = [];
$placeholderId = 1;

foreach ($bookTopics as $category => $topics) {
    foreach ($topics as $topic) {
        $placeholderBooks[] = [
            'id' => 'placeholder-' . $placeholderId,
            'title' => $category . ': ' . $topic,
            'author' => 'Digitalia Placeholder Collection',
            'category' => $category,
            'description' => 'Placeholder learning resource about ' . strtolower($topic) . '. This entry is part of the Digitalia Information Technology book collection. The actual book file and full content will be added soon.',
            'file_url' => null,
            'is_placeholder' => true
        ];

        $placeholderId++;
    }
}

// --------------------------------------------------
// FETCH REAL BOOKS FROM DATABASE
// --------------------------------------------------

$databaseBooks = [];
$favorites = [];

try {
    $sql = "SELECT id, title, author, category, description, file_url
            FROM books";

    $params = [];

    if ($search !== '') {
        $sql .= " WHERE (title LIKE :search OR author LIKE :search OR description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    if ($selectedCategory !== '') {
        $sql .= $search !== '' ? " AND category = :category" : " WHERE category = :category";
        $params[':category'] = $selectedCategory;
    }

    $sql .= " ORDER BY title ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $databaseBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Only show existing database books that belong to IT categories.
    $itCategories = array_keys($bookTopics);

    $databaseBooks = array_filter($databaseBooks, function ($book) use ($itCategories) {
        return in_array($book['category'], $itCategories, true);
    });

    // Load favorites for logged-in users.
    if ($isLoggedIn) {
        $favoriteStmt = $pdo->prepare(
            "SELECT book_id FROM favorites WHERE user_id = :user_id"
        );

        $favoriteStmt->execute([':user_id' => $user_id]);

        $favorites = $favoriteStmt->fetchAll(PDO::FETCH_COLUMN);
        $favorites = array_map('intval', $favorites);
    }

} catch (PDOException $e) {
    error_log('Digitalia books error: ' . $e->getMessage());
}

// --------------------------------------------------
// FILTER PLACEHOLDER BOOKS
// --------------------------------------------------

$filteredPlaceholders = array_filter(
    $placeholderBooks,
    function ($book) use ($search, $selectedCategory) {
        $matchesSearch = true;
        $matchesCategory = true;

        if ($search !== '') {
            $searchText = strtolower(
                $book['title'] . ' ' .
                $book['author'] . ' ' .
                $book['description'] . ' ' .
                $book['category']
            );

            $matchesSearch = str_contains(
                $searchText,
                strtolower($search)
            );
        }

        if ($selectedCategory !== '') {
            $matchesCategory = $book['category'] === $selectedCategory;
        }

        return $matchesSearch && $matchesCategory;
    }
);

// Combine real database books and placeholders.
$allBooks = [];

foreach ($databaseBooks as $book) {
    $book['is_placeholder'] = false;
    $allBooks[] = $book;
}

$allBooks = array_merge($allBooks, array_values($filteredPlaceholders));

// --------------------------------------------------
// CATEGORY LIST
// --------------------------------------------------

$categories = array_keys($bookTopics);

// --------------------------------------------------
// HELPER
// --------------------------------------------------

function escapeOutput($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$totalBooks = count($allBooks);
$totalPlaceholders = count($placeholderBooks);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Browse Books | Digitalia IT Library</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f8f5ef;
            color: #332b29;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .navbar {
            background: #4b2029;
            color: #fff;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .logo span {
            color: #e8c9a7;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
            font-size: 14px;
        }

        .nav-links a {
            color: #f8eeee;
            transition: .2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #e8c9a7;
        }

        .hero {
            padding: 55px 7% 35px;
        }

        .eyebrow {
            color: #a16b55;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 52px;
            color: #4b2029;
            margin-bottom: 10px;
        }

        .hero p {
            max-width: 700px;
            color: #786d68;
            line-height: 1.7;
            font-size: 14px;
        }

        .catalog-info {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .info-pill {
            background: #efe4d8;
            color: #704535;
            padding: 9px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
        }

        .content {
            padding: 10px 7% 70px;
        }

        .filter-section {
            background: #fff;
            border: 1px solid #eee5dc;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            flex: 1;
            min-width: 220px;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            min-width: 0;
            padding: 13px 15px;
            border: 1px solid #e5d9d0;
            border-radius: 8px;
            font-family: inherit;
            outline: none;
        }

        .search-form input:focus,
        .category-select:focus {
            border-color: #8b5159;
        }

        .search-btn,
        .filter-btn {
            background: #4b2029;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
        }

        .search-btn:hover,
        .filter-btn:hover {
            background: #68333d;
        }

        .category-select {
            padding: 13px 15px;
            border: 1px solid #e5d9d0;
            border-radius: 8px;
            background: white;
            font-family: inherit;
            min-width: 210px;
            outline: none;
        }

        .clear-link {
            color: #9b6259;
            font-size: 13px;
            font-weight: 600;
            padding: 10px;
        }

        .results-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 22px;
        }

        .results-heading h2 {
            font-family: 'Cormorant Garamond', serif;
            color: #4b2029;
            font-size: 30px;
        }

        .results-heading p {
            font-size: 13px;
            color: #8a7d76;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .book-card {
            background: #fff;
            border: 1px solid #eee5dc;
            border-radius: 13px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(60, 35, 30, .08);
        }

        .book-cover {
            min-height: 175px;
            padding: 22px;
            background: linear-gradient(135deg, #542832, #8b5159);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-cover.placeholder {
            background: linear-gradient(135deg, #344b53, #718d8d);
        }

        .cover-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: .8;
        }

        .cover-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 25px;
            line-height: 1.15;
            font-weight: 700;
            max-width: 250px;
        }

        .cover-footer {
            font-size: 10px;
            letter-spacing: 1px;
            opacity: .8;
        }

        .book-details {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .category-tag {
            align-self: flex-start;
            background: #f2e7df;
            color: #8b5159;
            border-radius: 20px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 13px;
        }

        .book-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 23px;
            line-height: 1.2;
            color: #4b2029;
            margin-bottom: 7px;
        }

        .book-author {
            color: #98766b;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .book-description {
            color: #786d68;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 18px;
            flex: 1;
        }

        .book-actions {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-top: auto;
        }

        .action-btn {
            display: block;
            text-align: center;
            border-radius: 7px;
            padding: 11px 12px;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .download-btn {
            background: #4b2029;
            color: white;
        }

        .download-btn:hover {
            background: #68333d;
        }

        .save-btn {
            border: 1px solid #d9c4b6;
            color: #754b43;
            background: white;
        }

        .save-btn:hover {
            background: #f8f0e9;
        }

        .saved-btn {
            background: #e7eee7;
            color: #49664d;
            cursor: default;
        }

        .placeholder-btn {
            background: #edf0f0;
            color: #61777a;
            cursor: default;
        }

        .empty-state {
            background: white;
            border: 1px solid #eee5dc;
            padding: 50px 20px;
            border-radius: 13px;
            text-align: center;
            grid-column: 1 / -1;
        }

        .empty-state h3 {
            font-family: 'Cormorant Garamond', serif;
            color: #4b2029;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #8a7d76;
            font-size: 13px;
        }

        footer {
            background: #4b2029;
            color: #e8d9d3;
            text-align: center;
            padding: 25px 7%;
            font-size: 12px;
        }

        @media (max-width: 950px) {
            .book-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .navbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav-links {
                flex-wrap: wrap;
                gap: 14px;
            }

            .hero h1 {
                font-size: 40px;
            }

            .book-grid {
                grid-template-columns: 1fr;
            }

            .filter-section {
                align-items: stretch;
                flex-direction: column;
            }

            .search-form {
                width: 100%;
            }

            .category-select,
            .search-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <a href="<?= $isLoggedIn ? 'dashboard.php' : 'index.php' ?>" class="logo">
        Digitalia<span>.</span>
    </a>

    <div class="nav-links">

        <a href="books.php" class="active">Browse Books</a>

        <?php if ($isLoggedIn): ?>

            <a href="my-library.php">My Library</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>

        <?php else: ?>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>

        <?php endif; ?>

    </div>

</nav>

<section class="hero">

    <div class="eyebrow">Information Technology Library</div>

    <h1>Explore IT Knowledge.</h1>

    <p>
        Discover learning resources covering programming, web development,
        mobile applications, databases, cybersecurity, artificial intelligence,
        networking, software engineering, and other fields of Information Technology.
    </p>

    <div class="catalog-info">

        <span class="info-pill">
            <?= $totalPlaceholders ?> IT Placeholder Books
        </span>

        <span class="info-pill">
            <?= count($categories) ?> IT Categories
        </span>

        <span class="info-pill">
            Explore. Learn. Innovate.
        </span>

    </div>

</section>

<main class="content">

    <form method="GET" action="books.php" class="filter-section">

        <div class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search IT books, authors, or topics..."
                value="<?= escapeOutput($search) ?>"
            >

            <button type="submit" class="search-btn">
                Search
            </button>

        </div>

        <select
            name="category"
            class="category-select"
            onchange="this.form.submit()"
        >

            <option value="">All IT Categories</option>

            <?php foreach ($categories as $category): ?>

                <option
                    value="<?= escapeOutput($category) ?>"
                    <?= $selectedCategory === $category ? 'selected' : '' ?>
                >
                    <?= escapeOutput($category) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <?php if ($search !== '' || $selectedCategory !== ''): ?>

            <a href="books.php" class="clear-link">
                Clear Filters
            </a>

        <?php endif; ?>

    </form>

    <div class="results-heading">

        <h2>IT Book Collection</h2>

        <p>
            <?= $totalBooks ?> book<?= $totalBooks === 1 ? '' : 's' ?> found
        </p>

    </div>

    <div class="book-grid">

        <?php if (empty($allBooks)): ?>

            <div class="empty-state">

                <h3>No Books Found</h3>

                <p>
                    Try another keyword or select a different IT category.
                </p>

            </div>

        <?php else: ?>

            <?php foreach ($allBooks as $book): ?>

                <?php
                    $isPlaceholder = !empty($book['is_placeholder']);
                    $bookId = $book['id'];
                    $isSaved = !$isPlaceholder &&
                        in_array((int) $bookId, $favorites, true);
                ?>

                <article class="book-card">

                    <div class="book-cover <?= $isPlaceholder ? 'placeholder' : '' ?>">

                        <div class="cover-label">
                            Digitalia IT Library
                        </div>

                        <div class="cover-title">
                            <?= escapeOutput($book['title']) ?>
                        </div>

                        <div class="cover-footer">
                            EXPLORE · LEARN · INNOVATE
                        </div>

                    </div>

                    <div class="book-details">

                        <span class="category-tag">
                            <?= escapeOutput($book['category']) ?>
                        </span>

                        <h3 class="book-title">
                            <?= escapeOutput($book['title']) ?>
                        </h3>

                        <p class="book-author">
                            By <?= escapeOutput($book['author']) ?>
                        </p>

                        <p class="book-description">
                            <?= escapeOutput($book['description']) ?>
                        </p>

                        <div class="book-actions">

                            <?php if ($isPlaceholder): ?>

                                <span class="action-btn placeholder-btn">
                                    Book Content Coming Soon
                                </span>

                            <?php else: ?>

                                <a
                                    href="download.php?book_id=<?= (int) $bookId ?>"
                                    class="action-btn download-btn"
                                >
                                    Download Book
                                </a>

                                <?php if ($isLoggedIn): ?>

                                    <?php if ($isSaved): ?>

                                        <span class="action-btn saved-btn">
                                            Already in My Library
                                        </span>

                                    <?php else: ?>

                                        <a
                                            href="add-favorite.php?book_id=<?= (int) $bookId ?>"
                                            class="action-btn save-btn"
                                        >
                                            Save to My Library
                                        </a>

                                    <?php endif; ?>

                                <?php else: ?>

                                    <a
                                        href="login.php"
                                        class="action-btn save-btn"
                                    >
                                        Login to Save Book
                                    </a>

                                <?php endif; ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</main>

<footer>
    Digitalia IT Library · Explore. Learn. Innovate.
</footer>

</body>
</html>
