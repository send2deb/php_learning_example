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
    <title>Your Cart</title>
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
    <h1>Your Shopping Cart</h1>
    <?php if(count($cartItem) > 0 ): ?>
    <table border="1">
        <thead>
        <tr>
          <th>Item Description</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cartItem as $item): ?>
        <tr>
            <td><?php htmlout($item['desc']); ?></td>
            <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
            <td>
                <form action="" method="post">
                    <div>
                        <input type="hidden" name="id" value="<?php htmlout($item['id']); ?>">
                        <input type="submit" name="action" value="Remove">
                    </div>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td>Total Price:</td>
            <td>Rs. <?php echo number_format($totalPrice,2); ?></td>
            <td>
                <form action="" method="post">
                    <div>
                        <input type="submit" name="action" value="Empty Cart">
                    </div>
                </form>
            </td>
        </tr>
      </tbody>
    </table>
    <p><a href=".">Buy</a> more item</p>
    <?php else: ?>
    <p>Your cart is empty. Continue <a href=".">shopping</a></p>
    <?php endif; ?>
</body>
</html>