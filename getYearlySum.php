<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';
require_once 'User.php';
require_once 'Expense.php';

$db = new Database("localhost", "kinal_budzet", "KKjX5pg2", "kinal_budzet");
$user = new User($db);
$expense = new Expense($db);

if (!$user->isLoggedIn()) {
    echo json_encode(['sum' => 0]);
    exit;
}

$year = $_GET['year'] ?? date('Y');
$userName = $_SESSION['username'];
$sum = $expense->getYearlySum($userName, $year);

// Dodajemy informacje debugujące
$debug = [
    'sum' => $sum,
    'year' => $year,
    'user' => $userName
];

echo json_encode($debug);
$db->close();
?>