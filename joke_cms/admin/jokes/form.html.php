<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/projects/joke_cms/includes/helpers.inc.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add new Joke</title>
</head>
<body>
    <h1><?php htmlout($pageTitle);?></h1>
    <form action="?<?php htmlout($action);?>" method="post">
        <div>
            <label for="text">Type your joke here:</label>
            <textarea id="text" name="text" rows="3" cols="40"><?php htmlout($text);?></textarea>
        </div>
        <div>
            <label for="author">Author:</label>
            <select name="author" id="author">
                <option value="">Select one</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php htmlout($author['id']);?>
                    "<?php if ($author['id'] == $authorid) {
                        echo ' selected';
                    } ?>><?php htmlout($author['name']);?></option>
                <?php endforeach;?>
            </select>
        </div>
        <fieldset>
            <legend>Categories:</legend>
            <?php foreach ($categories as $category): ?>
                <div>
                    <label for="category<?php htmlout($category['id']);?>">
                    <input type="checkbox" name="categories[]" id="category<?php htmlout($category['id']);?>"
                    value="<?php htmlout($category['id']);?>"<?php
                    if ($category['selected']) {
                        echo ' checked';
                    } ?>><?php htmlout($category['name']);?>
                    </label>
                </div>
            <?php endforeach;?>
        </fieldset>
        <div>
            <input type="hidden" name="id" value="<?php htmlout($id);?>">
            <input type="submit" value="<?php htmlout($button);?>">
        </div>
    </form>
</body>
</html>