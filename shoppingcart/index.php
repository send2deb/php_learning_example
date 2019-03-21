<?php
//Include the required files
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/shoppingcart';
include_once $docRoot . '/includes/magicquotes.inc.php';

//Start the session
session_start();
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
//Debug info
print_r($_SESSION);
echo '<br>'.'Session id: ' . session_id();

//Create a data array with shopping options
$items = array(
    array('id' => '1', 'desc' => 'India Gate Rice', 'price' => 100.50),
    array('id' => '2', 'desc' => 'Sunrise Oil', 'price' => 90.25),
    array('id' => '3', 'desc' => 'Modern Bread', 'price' => 44.75),
    array('id' => '4', 'desc' => 'Nescafe Coffee', 'price' => 290.00),
    array('id' => '5', 'desc' => 'Coca-Cola', 'price' => 85.90),
);

//Grab the buy action - Add an item
if(isset($_POST['action']) and $_POST['action'] == "Buy") {
    $_SESSION['cart'][] = $_POST['id'];
    header('Location: .');
    exit();
}

//Grab the remove action - Remove an item
if(isset($_POST['action']) and $_POST['action'] == "Remove") {
    foreach($_SESSION['cart'] as $key => $val) {
        if($val === $_POST['id']) {
            unset($_SESSION['cart'][$key]);
        }
        $index++;
    }
    header('Location: .?cart');
    exit();
}

//Grab the empty cart action - Remove all item
if(isset($_POST['action']) and $_POST['action'] == "Empty Cart") {

    unset($_SESSION['cart']);
    header('Location: .?cart');
    exit();
}

//Grab the view cart GET link click
if(isset($_GET['cart'])) {
    $cartItem = array();
    $totalPrice = 0;
    foreach($_SESSION['cart'] as $selectedItemId) {
        foreach($items as $item) {
            if($item['id'] === $selectedItemId) {
                $cartItem[] = $item;
                $totalPrice += $item['price'];
            }
        }
    }
    include 'cart.html.php';
    exit();
}

//Add the catalog template, this is the landing page
include 'catalog.html.php';