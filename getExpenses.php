<?php
session_start();
header('Content-Type: application/json');

require_once 'Database.php';
require_once 'User.php';
require_once 'Expense.php';

$db = new Database("localhost", "kinal_budzet", "KKjX5pg2", "kinal_budzet");
$user = new User($db);
$expense = new Expense($db);

if (!$user->isLoggedIn()) {
    echo json_encode([]);
    exit;
}

$date = $_GET['date'] ?? null;
$userName = $_SESSION['username'];

if (!$date) {
    echo json_encode([]);
    exit;
}

$expenses = $expense->getExpensesByDate($userName, $date);

echo json_encode($expenses);

$db->close();
?>