<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/projects/joke_cms/includes/helpers.inc.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php htmlout($pageTitle);?></title>
</head>
<body>
    <h1><?php htmlout($pageTitle);?></h1>
    <form action="?<?php htmlout($action);?>" method="post">
      <div>
        <label for="name">Name: <input type="text" name="name"
        id="name" value="<?php htmlout($name);?>"></label> </div>
      <div>
        <label for="email">Email: <input type="text" name="email"
        id="email" value="<?php htmlout($email);?>"></label> </div>
      <div>
        <label for="password">Set password: <input type="password"
        name="password" id="password"></label></div>
      <fieldset>
        <legend>Roles:</legend>
        <?php for ($i = 0; $i < count($roles); $i++): ?>
          <div>
            <label for="role<?php echo $i; ?>"><input type="checkbox"
            name="roles[]" id="role<?php echo $i; ?>"
            value="<?php htmlout($roles[$i]['id']); ?>"
            <?php
              if ($roles[$i]['selected'])
              {
                echo ' checked';
              }
            ?>>
            <?php htmlout($roles[$i]['id']); ?></label>:
            <?php htmlout($roles[$i]['description']); ?>
          </div>
          <?php endfor; ?> 
        </fieldset>
      <div>
        <input type="hidden" name="id" value="<?php htmlout($id);?>">
        <input type="submit" value="<?php htmlout($button);?>">
      </div>
    </form>
</body>
</html>