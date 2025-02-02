<?php
session_start();

require_once 'Database.php';
require_once 'User.php';

$db = new Database("localhost", "kinal_budzet", "KKjX5pg2", "kinal_budzet");
$user = new User($db);

$username = $_POST['username'];
$password = $_POST['password'];

if ($user->login($username, $password)) {
    header("Location: dashboard.php");
} else {
    header("Location: index.php?error=1");
}

$db->close();
?>