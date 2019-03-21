<?php 
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/shoppingcart';
include_once $docRoot . '/includes/helpers.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Catalog</title>
    <style>
    table {
      border-collapse: collapse;
    }
    td, th {
      border: 1px solid black;
    }
    </style>
</head>
<body>
    <p>Your shopping cart contains <?php echo count($_SESSION['cart']); ?> items.</p>
    <p><a href="?cart">View your cart</a></p>
    <table border="1">
        <thead>
        <tr>
          <th>Item Description</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php htmlout($item['desc']); ?></td>
            <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
            <td>
              <form action="" method="post">
                <div>
                  <input type="hidden" name="id" value="<?php htmlout($item['id']); ?>">
                  <input type="submit" name="action" value="Buy">
                </div>
              </form>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
</table>
<p>All prices are in imaginary Indian Rupees.</p>
</body>
</html>