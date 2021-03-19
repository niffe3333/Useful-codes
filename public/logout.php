<?php
session_start();
ob_start();
function autoloader($class) {
    include '../' . $class . '.php';
  }
  spl_autoload_register('autoloader');
$db = new classes\db();
$user = new classes\user();
$user->logout();
header('Location: index.php');
ob_end_flush();
?>