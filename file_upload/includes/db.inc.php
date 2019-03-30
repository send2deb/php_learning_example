<?php 
try {
  $pdo = new PDO('mysql:host=localhost;dbname=filestore', 'phpuser', 'phpmainuser');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  $error = 'Unable to connect to the database server.';
  include_once $_SERVER['DOCUMENT_ROOT'] . 
  '/projects/file_upload/includes/error.html.php';
  die();
}