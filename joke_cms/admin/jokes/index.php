<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
include_once $docRoot . '/includes/db.inc.php';
//Display GET array for testing only
print_r($_GET);

//Add new Joke form building
if (isset($_GET['add'])) {
    $pageTitle = 'New Joke';
    $action = 'addform';
    $text = '';
    $authorid = '';
    $id = '';
    $button = 'Add joke';
    // Build the list of authors
    try
    {
        $result = $pdo->query('SELECT id, name FROM author');
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of authors.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        $authors[] = array('id' => $row['id'], 'name' => $row['name']);
    }
    // Build the list of categories
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of categories.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        // Set 'selected' as false so that nothing is set in the form
        $categories[] = array('id' => $row['id'], 'name' => $row['name'], 'selected' => false);
    }
    include 'form.html.php';
    exit();
}

//Edit joke form building
if (isset($_POST['action']) and $_POST['action'] == 'Edit') {
    try {
        $sql = 'SELECT id, joketext, authorid FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching joke details.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    $pageTitle = 'Edit Joke';
    $action = 'editform';
    $text = $row['joketext'];
    $authorid = $row['authorid'];
    $id = $row['id'];
    $button = 'Update joke';
    // Build the list of authors
    try
    {
        $result = $pdo->query('SELECT id, name FROM author');
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of authors.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        $authors[] = array('id' => $row['id'], 'name' => $row['name']);
    }
    // Get list of categories containing this joke
    try
    {
        $sql = 'SELECT categoryid FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of selected categories.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($s as $row) {
        $selectedCategories[] = $row['categoryid'];
    }
    // Build the list of all categories
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of categories.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        $categories[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'selected' => in_array($row['id'], $selectedCategories));
    }
    include 'form.html.php';
    exit();
}

//Add the joke to database
if (isset($_GET['addform'])) {
    if ($_POST['author'] == '') {
        $error = 'You must choose an author for this joke.
        Click &lsquo;back&rsquo; and try again.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    try {
        $sql = 'INSERT INTO joke SET
        joketext = :joketext,
        jokedate = CURDATE(),
        authorid = :authorid';
        $s = $pdo->prepare($sql);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error adding submitted joke.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    // This gives the id of the last inserted record - use AUTO_INCREMENT feature in MySQL
    $jokeid = $pdo->lastInsertId();
    if (isset($_POST['categories'])) { //Checkbox handling
        try {
            $sql = 'INSERT INTO jokecategory SET
            jokeid = :jokeid,
            categoryid = :categoryid';
            $s = $pdo->prepare($sql);
            foreach ($_POST['categories'] as $categoryid) {
                $s->bindValue(':jokeid', $jokeid);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            $error = 'Error inserting joke into selected categories.';
            include_once $docRoot . '/error.html.php';
            exit();
        }
    }
    header('Location: .');
    exit();
}

//Edit the joke
if (isset($_GET['editform'])) {
    if ($_POST['author'] == '') {
        $error = 'You must choose an author for this joke.
        Click &lsquo;back&rsquo; and try again.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    try {
        $sql = 'UPDATE joke SET
        joketext = :joketext,
        authorid = :authorid
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error updating submitted joke.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    try {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error removing obsolete joke category entries.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    if (isset($_POST['categories'])) {
        try {
            $sql = 'INSERT INTO jokecategory SET
            jokeid = :jokeid,
            categoryid = :categoryid';
            $s = $pdo->prepare($sql);
            foreach ($_POST['categories'] as $categoryid) {
                $s->bindValue(':jokeid', $_POST['id']);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            $error = 'Error inserting joke into selected categories.';
            include_once $docRoot . '/error.html.php';
            exit();
        }
    }
    header('Location: .');
    exit();
}

//Delete a Joke
if (isset($_POST['action']) and $_POST['action'] == 'Delete') {
    // Delete category assignments for this joke
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error removing joke from categories.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    // Delete the joke
    try
    {
        $sql = 'DELETE FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error deleting joke.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}

//Search based on user input, if no input provided then search all
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    $select = 'SELECT id, joketext';
    $from = ' FROM joke';
    $where = ' WHERE TRUE';

    // Define a placeholder to hold user provided data
    $placeholders = array();
    if ($_GET['author'] != '') // An author is selected
    {
        $where .= " AND authorid = :authorid";
        $placeholders[':authorid'] = $_GET['author'];
    }
    if ($_GET['category'] != '') // A category is selected
    {
        $from .= ' INNER JOIN jokecategory ON id = jokeid';
        $where .= " AND categoryid = :categoryid";
        $placeholders[':categoryid'] = $_GET['category'];
    }
    if ($_GET['text'] != '') // Some search text was specified
    {
        $where .= " AND joketext LIKE :joketext";
        $placeholders[':joketext'] = '%' . $_GET['text'] . '%';
    }
    //Print for testing purpose only
    echo $select . '<br/>';
    echo $from . '<br/>';
    echo $where . '<br/>';

    try {
        $sql = $select . $from . $where;
        $data = $pdo->prepare($sql);
        $data->execute($placeholders); // Passing palceholders as array helps avoid individual filed binding
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching jokes.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($data as $row) {
        $jokes[] = array('id' => $row['id'], 'text' => $row['joketext']);
    }
    include 'jokes.html.php';
    exit();
}

//Get all authors
try {
    $result = $pdo->query('SELECT id, name FROM author');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $error = 'Error getting list of authors.';
    include_once $docRoot . '/error.html.php';
    exit();
}

foreach ($result as $row) {
    $authors[] = ['id' => $row['id'], 'name' => $row['name']];
}

//Get all categories
try {
    $result = $pdo->query('SELECT id, name FROM category');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $error = 'Error getting list of categories.';
    include_once $docRoot . '/error.html.php';
    exit();
}
foreach ($result as $row) {
    $categories[] = array('id' => $row['id'], 'name' => $row['name']);
}
include 'searchform.html.php';
