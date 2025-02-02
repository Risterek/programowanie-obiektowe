<?php
session_start();

require_once 'Database.php';
require_once 'User.php';
require_once 'Expense.php';

$db = new Database("localhost", "kinal_budzet", "KKjX5pg2", "kinal_budzet");
$user = new User($db);
$expense = new Expense($db);

if (!$user->isLoggedIn()) {
    header("Location: index.php?error=1");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cost']) && !empty($_POST['category']) && !empty($_POST['date'])) {
        $userName = $_SESSION['username'];
        $cost = $_POST['cost'];
        $category = $_POST['category'];
        $date = $_POST['date'];
        $expense->addExpense($userName, $cost, $category, $date);
    }
}

$sum = $expense->getYearlySum($_SESSION['username'], date('Y'));


$db->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planowanie budżetu</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header">
        <img id="logo" src="assets/logo.png" alt="Logo">
        <span id="logout">
            <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
        </span>
    </div>

    <div class="container">
        <div class="left_container">
            <div class="addCost_container">
                <form action="dashboard.php" method="post">
                    <input type="text" name="cost" id="cost" placeholder="Wpisz kwotę" required>
                    <select name="category" id="category">
                        <option value="food">Jedzenie</option>
                        <option value="clothes">Ubranie</option>
                        <option value="entertainment">Rozrywka</option>
                        <option value="bills">Rachunki</option>
                        <option value="other">Inne</option>
                    </select>
                    <input type="date" name="date" id="date" required>
                    <button type="submit" id="addCost">Dodaj</button>
                </form>
            </div>

            <div class="calendar_container">
                <div class="calendar_header">
                    <button id="previesBtn">
                        <img src="assets/chevron_left_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg" alt="Prev">
                    </button>
                    <div class="monthYear" id="monthYear"></div>
                    <button id="nextBtn">
                        <img src="assets/chevron_right_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg" alt="Next">
                    </button>
                </div>

                <div class="days">
                    <div class="day">Pon</div>
                    <div class="day">Wt</div>
                    <div class="day">Śr</div>
                    <div class="day">Czw</div>
                    <div class="day">Pt</div>
                    <div class="day">Sob</div>
                    <div class="day">Ndz</div>
                </div>
                <div class="dates" id="dates"></div>
            </div>
        </div>

        <div class="right_container">
          <div class="graph_container">
    <!-- Podsumowanie roczne. Na górze ten interfejs. -->
   <div class="fiance_summary">
    <div class="year_navigation">
        <button id="prevYearBtn">
            <img src="assets/chevron_left_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg" alt="Poprzedni rok">
        </button>
        <span id="currentYear">W roku 2025</span>
        <button id="nextYearBtn">
            <img src="assets/chevron_right_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg" alt="Następny rok">
        </button>
    </div>
    <div class="full_spend">
        <span id="Spending"><?php echo (int)$sum; ?></span>
        <span id="curency">zł</span>
    </div>
</div>


    <!-- Szczegóły dnia. Wydatki danego dnia. -->
    <div class="day_summary">
        <h1 id="dayTitle">Wybierz dzień</h1>
        <div id="dayExpenses"></div>
    </div>
</div>
        </div>
    </div>

   
    <script src="calendar_main.js"></script>
	<script src="yearNavigation.js"></script>
</body>
</html>