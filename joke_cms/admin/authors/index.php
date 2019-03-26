<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
include_once $docRoot . '/includes/db.inc.php';
require_once $docRoot . '/includes/access.inc.php';
//Display post array for testing only
print_r($_POST);

//Handle uer login, logout and role
if (!userIsLoggedIn()) {
    include '../../login.html.php';
    exit(); 
}
if (!userHasRole('Account Administrator')) {
    $error = 'Only Account Administrators may access this page.';
    include '../../accessdenied.html.php';
    exit();
}

/**
 * This section to handle DELETE action
 */
if (isset($_POST['action']) and $_POST['action'] == 'Delete') {
    // Delete role assignments for this author
    try {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error removing author from roles.';
        include_once $docRoot . '/error.html.php';
        exit();
    }

    // Get jokes belonging to author
    try {
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
        print "Error!: " . $e->getMessage() . "<br/>";
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
    // Build the list of roles
    try {
        $result = $pdo->query('SELECT id, description FROM role');
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of roles.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        $roles[] = array(
        'id' => $row['id'],
        'description' => $row['description'],
        'selected' => FALSE);
    }

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

    $authorid = $pdo->lastInsertId();
    if ($_POST['password'] != '')
    {
        $password = md5($_POST['password'] . 'ijdb');
        try {
            $sql = 'UPDATE author SET
                password = :password
                WHERE id = :id';
            $s = $pdo->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $authorid);
            $s->execute();
            }
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            $error = 'Error setting author password.';
            include_once $docRoot . '/error.html.php';
            exit();
        } 
    }
    if (isset($_POST['roles']))
    {
        foreach ($_POST['roles'] as $role) {
            try {
                $sql = 'INSERT INTO authorrole SET
                    authorid = :authorid,
                    roleid = :roleid';
                $s = $pdo->prepare($sql);
                $s->bindValue(':authorid', $authorid);
                $s->bindValue(':roleid', $role);
                $s->execute();
            }
            catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                $error = 'Error assigning selected role to author.';
                include_once $docRoot . '/error.html.php';
                exit();
            } 
        }
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

    // Get list of roles assigned to this author
    try {
        $sql = 'SELECT roleid FROM authorrole WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of assigned roles.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    
    $selectedRoles = array();
    foreach ($s as $row) {
        $selectedRoles[] = $row['roleid'];
    }

    // Build the list of all roles
    try {
        $result = $pdo->query('SELECT id, description FROM role');
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching list of roles.';
        include_once $docRoot . '/error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $roles[] = array(
            'id' => $row['id'],
            'description' => $row['description'],
            'selected' => in_array($row['id'], $selectedRoles));
    }
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

    if ($_POST['password'] != '') {
        $password = md5($_POST['password'] . 'ijdb');
        try {
            $sql = 'UPDATE author SET
                password = :password
                WHERE id = :id';
            $s = $pdo->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $_POST['id']);
            $s->execute();
        }
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            $error = 'Error setting author password.';
            include_once $docRoot . '/error.html.php';
            exit();
        }
    }

    //First delete all existing roles
    try {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error removing obsolete author role entries.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    //Now add newly selected roles
    if (isset($_POST['roles'])) {
        foreach ($_POST['roles'] as $role) {
            try {
                $sql = 'INSERT INTO authorrole SET
                    authorid = :authorid,
                    roleid = :roleid';
                $s = $pdo->prepare($sql);
                $s->bindValue(':authorid', $_POST['id']);
                $s->bindValue(':roleid', $role);
                $s->execute();
            }
            catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                $error = 'Error assigning selected role to author.';
                include_once $docRoot . '/error.html.php';
                exit();
            }
        }
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
