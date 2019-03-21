<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
include_once $docRoot . '/includes/db.inc.php';
//Display post array for testing only
print_r($_POST);
/**
 * This section to handle DELETE action
 */
if (isset($_POST['action']) and $_POST['action'] == 'Delete') {
    // Delete joke category entries
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE categoryid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error deleting jokecategory entries for category.';
        include_once $docRoot . '/error.html.php';
        exit();
    }

    try
    {
        $sql = 'DELETE FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error deleting category entry from database.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
}

/**
 * This section for displaying add new author form
 */
if (isset($_GET['add'])) {
    $pageTitle = 'New Category';
    $action = 'addform';
    $name = '';
    $email = '';
    $id = '';
    $button = 'Add Category';
    include 'form.html.php';
    exit();
}

/**
 * This section is to add the data for add new author
 */
if (isset($_GET['addform'])) {
    try {
        $sql = 'INSERT INTO category SET name = :name';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error adding submitted category.';
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
        $sql = 'SELECT id, name FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching category.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    $pageTitle = 'Edit Category';
    $action = 'editform';
    $name = $row['name'];
    $id = $row['id'];
    $button = 'Update Category';
    include 'form.html.php';
    exit();
}

/**
 * This section is to update the data for add new author
 */
if (isset($_GET['editform'])) {
    try {
        $sql = 'UPDATE category SET
        name = :name
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error updating submitted category.';
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
    $result = $pdo->query('SELECT id, name FROM category');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $error = "Unable to fetch category data from database";
    include_once $docRoot . '/error.html.php';
    die();
}

foreach ($result as $row) {
    $categories[] = array(
        'id' => $row['id'],
        'name' => $row['name']);
}
include 'categories.html.php';
