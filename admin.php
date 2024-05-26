<?php
include 'db.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_feed'])) {
        $url = $_POST['url'];
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $stmt = $pdo->prepare('INSERT INTO feeds (url, title, category_id) VALUES (?, ?, ?)');
        $stmt->execute([$url, $title, $category_id]);
    } elseif (isset($_POST['delete_feed'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM feeds WHERE id = ?');
        $stmt->execute([$id]);
    } elseif (isset($_POST['add_category'])) {
        $name = $_POST['name'];
        $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([$name]);
    } elseif (isset($_POST['delete_category'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }
}

$feeds = fetchFeeds($pdo);
$categories = fetchCategories($pdo);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin - RSS Feeds</title>
    <link rel="stylesheet" href="css/stylesheet.css">
</head>
<body>
     <div class="sidebar">
        <h2>Navigation</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php foreach ($categories as $category): ?>
                <li><a href="category.php?id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
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
        <h1>Admin - RSS Feeds</h1>
        <form method="post">
            <h2>Feed hinzufügen</h2>
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" required>
            <label for="url">URL:</label>
            <input type="url" id="url" name="url" required>
            <label for="category_id">Kategorie:</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="add_feed">Hinzufügen</button>
        </form>
        <h2>Bestehende Feeds</h2>
        <ul>
            <?php foreach ($feeds as $feed): ?>
                <li>
                    <?php echo $feed['title']; ?> (Kategorie: <?php echo $feed['category_name']; ?>) - <?php echo $feed['url']; ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $feed['id']; ?>">
                        <button type="submit" name="delete_feed">Löschen</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="post">
            <h2>Kategorie hinzufügen</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="add_category">Hinzufügen</button>
        </form>
        <h2>Bestehende Kategorien</h2>
        <p>Um bestehende Kategorien löschen zu können, müssen sie erst die entsprechenden RSS-Feeds in der Kategorie entfernen</p>
		<ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <?php echo $category['name']; ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                        <button type="submit" name="delete_category">Löschen</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
