<?php
session_start();

require_once 'Database.php';
require_once 'User.php';

$db = new Database("localhost", "kinal_budzet", "KKjX5pg2", "kinal_budzet");
$user = new User($db);

$user->logout();
header("Location: index.php");

$db->close();
?>