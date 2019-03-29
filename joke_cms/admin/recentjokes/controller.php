<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
include_once $docRoot . '/includes/db.inc.php';
try {
    $sql = 'SELECT id, joketext FROM joke
        ORDER BY jokedate DESC
        LIMIT 3';
    $result = $pdo->query($sql);
}
catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $error = 'Error fetching jokes.';
    include_once $docRoot . '/error.html.php';
    exit();
}

foreach ($result as $row) {
    $jokes[] = array('text' => $row['joketext']);
}

include 'jokes.html.php';