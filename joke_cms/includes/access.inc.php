<?php
// $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
// include_once $docRoot . '/includes/db.inc.php';

//Define the GLOBAL variable and assign with space to avoid notice issued in html login form
$GLOBALS['loginError'] = '';

//Hanlde login & logout
function userIsLoggedIn() {
    //Hanlde login request
    if (isset($_POST['action']) and $_POST['action'] == 'login') {
        if (!isset($_POST['email']) or $_POST['email'] == '' or
        !isset($_POST['password']) or $_POST['password'] == '') {
            $GLOBALS['loginError'] = 'Please fill in both fields';
            return FALSE;
        }
        $password = md5($_POST['password'] . 'ijdb');
        $email = $_POST['email'];
        if(databaseContainsAuthor($email, $password)) {
            session_start();
            $_SESSION['loggedIn'] = TRUE;
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['password'] = $password;
            return TRUE;
        } else {
            session_start();
            unset($_SESSION['loggedIn']);
            unset($_SESSION['email']);
            unset($_SESSION['password']);
            $GLOBALS['loginError'] = 'The specified email address or password was incorrect.';
            return FALSE;
        }
    }

    //Hanlde logout
    if (isset($_POST['action']) and $_POST['action'] == 'logout') {
        session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        header('Location: ' . $_POST['goto']);
        exit();
    }

    //Check if user is already loggedin (i.e. no form submitted)
    session_start();
    if (isset($_SESSION['loggedIn'])) {
        return databaseContainsAuthor($_SESSION['email'], $_SESSION['password']);
    }
}

//Hanlde role
function userHasRole($role)
{
    $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
    include $docRoot . '/includes/db.inc.php';
    try {
        $sql = "SELECT COUNT(*) FROM author
            INNER JOIN authorrole ON author.id = authorid
            INNER JOIN role ON roleid = role.id
            WHERE email = :email AND role.id = :roleId";
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_SESSION['email']);
        $s->bindValue(':roleId', $role);
        $s->execute();
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error searching for author roles.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    if ($row[0] > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

//Internal function for database check
function databaseContainsAuthor($email, $password) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
    include $docRoot . '/includes/db.inc.php';
    try {
        $sql = 'SELECT COUNT(*) FROM author
            WHERE email = :email AND password = :password';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->bindValue(':password', $password);
        $s->execute();
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $error = 'Error fetching author login credentials.';
        include_once $docRoot . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    if ($row[0] > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}