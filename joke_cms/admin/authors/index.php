<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
include_once $docRoot . '/includes/db.inc.php';
//Display post array for testing only
print_r($_POST);
/**
 * This section to handle DELETE action
 */
if (isset($_POST['action']) and $_POST['action'] == 'Delete') {
    // Get jokes belonging to author
    try
    {
        $sql = 'SELECT id FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error getting list of jokes to delete.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $result = $s->fetchAll();

    // Delete joke category entries
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        // For each joke
        foreach ($result as $row) {
            $jokeId = $row['id'];
            $s->bindValue(':id', $jokeId);
            $s->execute();
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error deleting category entries for joke.';
        include_once $docRoot . '/error.html.php';
        exit();
    }

    // Delete jokes belonging to author
    try {
        $sql = 'DELETE FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error deleting jokes for author.';
        include_once $docRoot . '/error.html.php';
        exit();
    }

    // Delete the author
    try {
        $sql = 'DELETE FROM author WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error deleting author.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}

/**
 * This section for displaying add new author form
 */
if (isset($_GET['add'])) {
    $pageTitle = 'New Author';
    $action = 'addform';
    $name = '';
    $email = '';
    $id = '';
    $button = 'Add author';
    include 'form.html.php';
    exit();
}

/**
 * This section is to add the data for add new author
 */
if (isset($_GET['addform'])) {
    try {
        $sql = 'INSERT INTO author SET name = :name, email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error adding submitted author.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}

/**
 * This section displays form for author edit
 */
if (isset($_POST['action']) and $_POST['action'] == 'Edit') {
    try {
        $sql = 'SELECT id, name, email FROM author WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching author details.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    $pageTitle = 'Edit Author';
    $action = 'editform';
    $name = $row['name'];
    $email = $row['email'];
    $id = $row['id'];
    $button = 'Update author';
    include 'form.html.php';
    exit();
}

/**
 * This section is to update the data for add new author
 */
if (isset($_GET['editform'])) {
    try {
        $sql = 'UPDATE author SET
        name = :name,
        email = :email
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error updating submitted author.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}

/**
 * This section for display author
 */
try {
    $result = $pdo->query('SELECT id, name FROM author');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $error = "Unable to fetch author data from database";
    include_once $docRoot . '/error.html.php';
    die();
}

foreach ($result as $row) {
    $authors[] = array(
        'id' => $row['id'],
        'name' => $row['name']);
}
include 'authors.html.php';
