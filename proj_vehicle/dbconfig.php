<?php 
#Create a connection
$dbhost = "localhost";
$dbuser = "phpuser";
$dbpassword = "phpmainuser";
$database = "Cars";
$conn = new mysqli($dbhost,$dbuser,$dbpassword,$database);
if(mysqli_connect_errno()) {
    echo "Connection failed due to error " . mysqli_connect_error();
    exit();
}

echo "Connected to database successfully <br/>";
?>