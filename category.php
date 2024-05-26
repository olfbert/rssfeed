<?php
include 'db.php';
include 'functions.php';

$category_id = $_GET['id'];
$category = fetchCategory($pdo, $category_id);
$allNews = fetchAllNews($pdo, $category_id);
$categories = fetchCategories($pdo); // Diese Zeile fügt die Kategorien hinzu
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($category['name']); ?> - RSS News</title>
    <link rel="stylesheet" href="css/stylesheet.css">
</head>
<body>
    <div class="sidebar">
        <h2>Navigation</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php foreach ($categories as $cat): ?>
                <li><a href="category.php?id=<?php echo htmlspecialchars($cat['id']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="admin-link">
            <a href="admin.php">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#5f6368">
                    <path d="M96-588v-155.85Q96-776 118.56-796q22.57-20 54.25-20h614.5q31.69 0 54.19 20 22.5 20 22.5 52.15V-588h-72v-156H168v156H96Zm76.69 324q-31.69 0-54.19-20Q96-304 96-336v-180h72v180h624v-180h72v180q0 32-22.56 52-22.57 20-54.25 20h-614.5ZM48-144v-72h864v72H48Zm432-396ZM96-516v-72h233q14 0 25 7t17 18l39 72 112-176q5-8 12.42-12.5 7.43-4.5 16.5-4.5 9.08 0 17.08 3.5 8 3.5 13 10.5l61 82h222v72H629q-11 0-21-5t-17-14l-37-50-116 184q-5 8-13.06 12.5-8.07 4.5-16.94 4.5-9.9 0-18.45-5.5Q381-395 376-403l-62-113H96Z"/>
                </svg>
            </a>
        </div>
    </div>
    <div class="content">
        <h1><?php echo htmlspecialchars($category['name']); ?> - RSS News</h1>
        <?php foreach ($allNews as $newsItem): ?>
             <div class="news-item">
                    <h4><a href="<?php echo $newsItem['link']; ?>"><?php echo $newsItem['title']; ?></a></h4>
                    <?php if (!empty($newsItem['image'])): ?>
                        <img src="<?php echo $newsItem['image']; ?>" alt="<?php echo $newsItem['title']; ?>">
                    <?php endif; ?>
                    <p><?php echo $newsItem['description']; ?></p>
                </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
