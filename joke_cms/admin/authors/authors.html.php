<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/projects/joke_cms/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>JMS Author Management</title>
</head>
<body>
    <h1>Manage Authors</h1>
    <p><a href="?add">Add new author</a></p>
    <ul>
        <?php foreach($authors as $author): ?>
        <li>
            <form action="" method="POST">
                <?php htmlout($author["name"]); ?>
                <input type="hidden" name="id" 
                    value="<?php echo $author['id']; ?>">
                <input type="submit" name="action" value="Edit">
                <input type="submit" name="action" value="Delete">
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
    <p><a href="..">Return to JMS Home Page</a></p>
</body>
</html>