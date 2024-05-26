<?php
function fetchFeeds($pdo) {
    $stmt = $pdo->prepare('SELECT feeds.id, feeds.url, feeds.title, feeds.category_id, categories.name as category_name FROM feeds JOIN categories ON feeds.category_id = categories.id');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchCategories($pdo) {
    $stmt = $pdo->prepare('SELECT id, name FROM categories');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchLatestNews($pdo, $category_id) {
    $stmt = $pdo->prepare('SELECT feeds.url FROM feeds WHERE feeds.category_id = ?');
    $stmt->execute([$category_id]);
    $feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newsItems = [];
    foreach ($feeds as $feed) {
        $rss = simplexml_load_file($feed['url']);
        foreach ($rss->channel->item as $item) {
            $newsItems[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'description' => (string) $item->description,
                'pubDate' => strtotime((string) $item->pubDate),
                'category_id' => $category_id
            ];
        }
    }
    usort($newsItems, function($a, $b) {
        return $b['pubDate'] - $a['pubDate'];
    });
    return array_slice($newsItems, 0, 5);
}

function fetchAllNews($pdo, $category_id) {
    $stmt = $pdo->prepare('SELECT feeds.url FROM feeds WHERE feeds.category_id = ?');
    $stmt->execute([$category_id]);
    $feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newsItems = [];
    foreach ($feeds as $feed) {
        $rss = simplexml_load_file($feed['url']);
        foreach ($rss->channel->item as $item) {
            $newsItems[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'description' => (string) $item->description,
                'pubDate' => strtotime((string) $item->pubDate),
                'category_id' => $category_id
            ];
        }
    }
    usort($newsItems, function($a, $b) {
        return $b['pubDate'] - $a['pubDate'];
    });
    return $newsItems;
}

function fetchCategory($pdo, $category_id) {
    $stmt = $pdo->prepare('SELECT id, name FROM categories WHERE id = ?');
    $stmt->execute([$category_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
