<?php
class Expense {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addExpense($user, $cost, $category, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO expenses (user, amount, category, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $user, $cost, $category, $date);
        $stmt->execute();
        $stmt->close();
    }

    public function getMonthlySum($user) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
            SELECT SUM(amount) AS total 
            FROM expenses 
            WHERE user = ? 
              AND MONTH(date) = MONTH(CURRENT_DATE()) 
              AND YEAR(date) = YEAR(CURRENT_DATE())
        ");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $sum = 0;
        if ($row = $result->fetch_assoc()) {
            $sum = $row['total'] ?? 0;
        }
        $stmt->close();
        return $sum;
    }

    public function getCategorySums($user) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
            SELECT category, SUM(amount) AS total 
            FROM expenses 
            WHERE user = ? 
              AND MONTH(date) = MONTH(CURRENT_DATE()) 
              AND YEAR(date) = YEAR(CURRENT_DATE())
            GROUP BY category
        ");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $categorySums = [];
        while ($row = $result->fetch_assoc()) {
            $categorySums[$row['category']] = $row['total'];
        }
        $stmt->close();
        return $categorySums;
    }

    public function getExpensesByDate($user, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare(
            "SELECT category, amount 
             FROM expenses 
             WHERE user = ? 
               AND date = ?"
        );
        $stmt->bind_param("ss", $user, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
        $stmt->close();
        return $expenses;
    }

    public function getYearlySum($user, $year) {
		$conn = $this->db->getConnection();
		$stmt = $conn->prepare("
        SELECT SUM(amount) AS total 
        FROM expenses 
        WHERE user = ? 
        AND YEAR(date) = ?
		");
    
		// Traktujemy rok jako integer
		$yearInt = (int)$year;
    
		$stmt->bind_param("si", $user, $yearInt);
		$stmt->execute();
		$result = $stmt->get_result();
		$sum = 0;
		if ($row = $result->fetch_assoc()) {
			$sum = $row['total'] ?? 0;
		}
		$stmt->close();
		return $sum;
	}
}
?>