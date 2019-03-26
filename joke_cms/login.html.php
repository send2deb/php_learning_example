<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/projects/joke_cms/includes/helpers.inc.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
</head>
<body>
    <?php if($loginError): ?>
        <p><?php htmlout($loginError)?></p>
    <?php endif ;?>
    <form action="" method="POST">
        <div>
            <label for="email">Email: <input type="text" name="email" id="email"></label>
        </div>
        <div>
        <label for="password">Password: <input type="password" name="password" id="email"></label>
        </div>
        <div>
            <input type="hidden" name="action" value="login">
            <input type="submit" value="Log in">
        </div>
        <p><a href="/projects/joke_cms/admin/">Return to JMS Home page</a></p>
    </form>
</body>
</html>